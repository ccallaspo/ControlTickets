<?php

namespace Tests\Feature;

use App\Mail\CursoActualizacionMail;
use App\Models\Event;
use App\Models\Followup;
use App\Models\User;
use App\Services\TicketUpdateNotifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TicketUpdateNotifierTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
        });
    }

    private function ticket(string $status, ?string $coordinator = 'coordinadora@example.com'): Followup
    {
        $ticket = new Followup(['name' => '123', 'name_course' => 'Curso de prueba']);
        $ticket->setRelation('event', new Event(['name' => $status]));
        $ticket->setRelation('ejecutivo', $coordinator ? new User(['name' => 'Coordinadora', 'email' => $coordinator]) : null);
        return $ticket;
    }

    private function inMemoryTicket(): Followup
    {
        return new class extends Followup {
            public function load($relations)
            {
                return $this;
            }
        };
    }

    public function test_matricular_includes_actor_coordinator_and_only_support_users(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'email' => 'soporte@example.com'],
            ['id' => 2, 'email' => 'ajeno@example.com'],
            ['id' => 3, 'email' => 'ACTOR@example.com'],
        ]);
        DB::table('roles')->insert(['id' => 1, 'name' => 'Soporte']);
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_id' => 1, 'model_type' => User::class],
            ['role_id' => 1, 'model_id' => 3, 'model_type' => User::class],
        ]);

        $this->assertSame([
            'actor@example.com', 'coordinadora@example.com', 'soporte@example.com',
        ], app(TicketUpdateNotifier::class)->recipients($this->ticket('Matricular Curso'), 'actor@example.com'));
    }

    public function test_approved_includes_configured_responsible_and_removes_duplicates(): void
    {
        config(['mail.cotizacion_aprobada_to' => 'responsable@example.com']);
        $this->assertSame(['actor@example.com', 'responsable@example.com'],
            app(TicketUpdateNotifier::class)->recipients($this->ticket('Cotización Aprobada', 'ACTOR@example.com'), 'actor@example.com'));
    }

    public function test_missing_coordinator_or_support_does_not_exclude_actor(): void
    {
        $this->assertSame(['actor@example.com'],
            app(TicketUpdateNotifier::class)->recipients($this->ticket('Matricular Curso', null), 'actor@example.com'));
    }

    public function test_other_states_include_actor_and_coordinator_and_template_renders_changes(): void
    {
        $ticket = $this->ticket('Curso en Proceso');
        $this->assertSame(['actor@example.com', 'coordinadora@example.com'],
            app(TicketUpdateNotifier::class)->recipients($ticket, 'actor@example.com'));
        $ticket->changes = ['documento' => 'participantes.xlsx', 'description' => '<script>test</script>'];
        $html = (new CursoActualizacionMail($ticket, new User(['name' => 'Actor', 'email' => 'actor@example.com'])))->render();
        $this->assertStringContainsString('participantes.xlsx', $html);
        $this->assertStringContainsString('Actor', $html);
        $this->assertStringContainsString('Curso en Proceso', $html);
        $this->assertStringNotContainsString('<script>test</script>', $html);
    }

    public function test_no_email_is_sent_without_authenticated_user(): void
    {
        Mail::fake();
        app(TicketUpdateNotifier::class)->send($this->ticket('Matricular Curso'), ['documento' => 'participantes.xlsx']);
        Mail::assertNothingSent();
    }

    public function test_authenticated_update_sends_one_mail_to_all_recipients(): void
    {
        Mail::fake();
        $this->actingAs(new User(['name' => 'Actor', 'email' => 'actor@example.com']));
        $ticket = $this->inMemoryTicket();
        $ticket->setRelation('event', new Event(['name' => 'Cotización Aprobada']));
        $ticket->setRelation('ejecutivo', new User(['email' => 'coordinadora@example.com']));
        config(['mail.cotizacion_aprobada_to' => 'responsable@example.com']);

        app(TicketUpdateNotifier::class)->send($ticket, ['description' => 'Cambio de horario']);

        Mail::assertSent(CursoActualizacionMail::class, fn ($mail) =>
            $mail->hasTo('actor@example.com') && $mail->hasTo('coordinadora@example.com')
            && $mail->hasTo('responsable@example.com')
            && $mail->data->changes === ['description' => 'Cambio de horario']);
        Mail::assertSentCount(1);
    }

    public function test_document_upload_notifies_using_its_ticket(): void
    {
        $ticket = $this->ticket('Matricular Curso');
        $document = new \App\Models\Document(['document_archive' => 'documentos/123/participantes.xlsx']);
        $document->wasRecentlyCreated = true;
        $document->setRelation('followup', $ticket);
        $this->mock(TicketUpdateNotifier::class)->shouldReceive('scheduleDocument')->once()
            ->with($document);

        (new \App\Observers\DocumentObserver)->saved($document);
    }

    private function assertObserverRecipients(bool $changesState): void
    {
        \Illuminate\Support\Facades\Notification::fake();
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        DB::table('users')->insert(['id' => 1, 'email' => 'soporte@example.com']);
        DB::table('roles')->insert(['id' => 1, 'name' => 'Soporte']);
        DB::table('model_has_roles')->insert(['role_id' => 1, 'model_id' => 1, 'model_type' => User::class]);
        config(['mail.cotizacion_aprobada_to' => 'responsable@example.com']);
        $actor = new User(['name' => 'Actor', 'email' => 'actor@example.com']);
        $actor->id = 10;
        $this->actingAs($actor);

        $states = [
            'Cotización Aprobada' => \App\Mail\CotizacionAprobadaMail::class,
            'Cotización actualizada' => CursoActualizacionMail::class,
            'Coordinar Curso' => \App\Mail\CursoCoordinarMail::class,
            'Matricular Curso' => \App\Mail\CursoMatriculadoMail::class,
            'Curso en Proceso' => \App\Mail\CursoEnProcesoMail::class,
            'Curso Finalizado' => \App\Mail\CursoFinalizadoMail::class,
            'Generar DJ' => \App\Mail\DjOtecMail::class,
            'Por Facturar' => \App\Mail\PorFacturarMail::class,
        ];

        foreach ($states as $state => $specificMail) {
            Mail::fake();
            $eventId = DB::table('events')->insertGetId(['name' => $state]);
            $ticket = $this->inMemoryTicket();
            $ticket->name = '123';
            $ticket->event_id = $changesState ? 0 : $eventId;
            $ticket->description = 'Antes';
            $ticket->syncOriginal();
            $ticket->event_id = $eventId;
            $ticket->description = 'Después';
            $ticket->setRelation('event', new Event(['name' => $state]));
            $ticket->setRelation('ejecutivo', new User(['email' => 'coordinadora@example.com']));

            (new \App\Observers\FollowupObserver)->updated($ticket);
            app(TicketUpdateNotifier::class)->flush();

            $responsible = match ($state) {
                'Matricular Curso' => 'soporte@example.com',
                'Cotización Aprobada' => 'responsable@example.com',
                default => 'coordinadora@example.com',
            };
            Mail::assertSent($changesState ? $specificMail : CursoActualizacionMail::class, function ($mail) use ($responsible) {
                $emails = collect([...$mail->to, ...$mail->cc])->pluck('address')->all();
                $this->assertContains($responsible, $emails);
                $this->assertContains('actor@example.com', $emails);
                $this->assertContains('coordinadora@example.com', $emails);
                $this->assertCount(count(array_unique(array_map('strtolower', $emails))), $emails);
                return true;
            });
            Mail::assertSentCount(1);
        }
    }

    public function test_all_state_updates_keep_original_responsible_actor_and_coordinator(): void
    {
        $this->assertObserverRecipients(false);
    }

    public function test_all_state_transitions_keep_original_mail_responsible_actor_and_coordinator(): void
    {
        $this->assertObserverRecipients(true);
    }

    public function test_multiple_changes_and_documents_are_combined_with_original_values_in_one_email(): void
    {
        Mail::fake();
        $this->actingAs(new User(['name' => 'Actor', 'email' => 'actor@example.com']));
        $ticket = $this->inMemoryTicket();
        $ticket->id = 123;
        $ticket->id_sence = ['1234567890'];
        $ticket->f_star = '2026-10-22';
        $ticket->description = 'Descripción inicial';
        $ticket->syncOriginal();
        $ticket->setRelation('event', new Event(['name' => 'Coordinar Curso']));
        $ticket->setRelation('ejecutivo', new User(['name' => 'Coordinadora', 'email' => 'coordinadora@example.com']));
        $ticket->setRelation('note', collect());
        $ticket->id_sence = ['1234567891'];
        $ticket->f_star = '2026-10-26';
        $ticket->description = 'Descripción final';
        $notifier = app(TicketUpdateNotifier::class);
        $notifier->schedule($ticket, ['id_sence' => $ticket->id_sence, 'f_star' => $ticket->f_star, 'description' => $ticket->description]);
        $ticket->syncOriginal(); // Simula el final del guardado de Eloquent.

        foreach (['participantes.xlsx', 'orden_compra.pdf', 'programa.pdf'] as $index => $file) {
            $document = new \App\Models\Document(['document_archive' => 'documentos/123/'.$file]);
            $document->id = $index + 1;
            $document->wasRecentlyCreated = true;
            $document->setRelation('followup', $ticket);
            $document->setRelation('typedocument', new \App\Models\Typedocument(['name' => ['Nómina', 'Orden de compra', 'Programa'][$index]]));
            (new \App\Observers\DocumentObserver)->saved($document);
        }
        Mail::assertNothingSent();
        $notifier->flush();
        Mail::assertSentCount(1);
        Mail::assertSent(CursoActualizacionMail::class, function ($mail) {
            $this->assertCount(6, $mail->updateRows);
            $this->assertSame(['label' => 'Código ID', 'before' => '1234567890', 'after' => '1234567891'], $mail->updateRows[0]);
            $this->assertSame('22/10/2026', $mail->updateRows[1]['before']);
            $this->assertSame('26/10/2026', $mail->updateRows[1]['after']);
            $html = $mail->render();
            foreach (['participantes.xlsx', 'orden_compra.pdf', 'programa.pdf', 'Nómina', 'Anterior:', 'Nuevo:', 'Este ticket no tiene notas.'] as $text) {
                $this->assertStringContainsString($text, $html);
            }
            return true;
        });
    }

    public function test_email_loads_all_ticket_notes_in_reverse_date_order_and_escapes_content(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->text('note');
            $table->string('author');
            $table->timestamps();
        });
        DB::table('notes')->insert([
            ['followup_id' => 123, 'note' => '<p>Nota antigua</p>', 'author' => 'Coordinadora', 'created_at' => '2026-10-19 09:00:00', 'updated_at' => '2026-10-19 09:00:00'],
            ['followup_id' => 123, 'note' => '<p>Nota reciente</p><p>&lt;img src=x onerror=alert(1)&gt;</p>', 'author' => 'Soporte', 'created_at' => '2026-10-20 10:35:00', 'updated_at' => '2026-10-20 10:35:00'],
            ['followup_id' => 999, 'note' => 'Nota de otro ticket', 'author' => 'Otro', 'created_at' => '2026-10-20 10:35:00', 'updated_at' => '2026-10-20 10:35:00'],
        ]);
        $ticket = $this->ticket('Coordinar Curso');
        $ticket->id = 123;
        $ticket->exists = true;
        $ticket->changes = ['description' => 'Modificada'];
        $mail = new CursoActualizacionMail($ticket, new User(['name' => 'Actor', 'email' => 'actor@example.com']));
        $html = $mail->render();
        $this->assertCount(2, $mail->ticketNotes);
        $this->assertLessThan(strpos($html, 'Nota antigua'), strpos($html, 'Nota reciente'));
        $this->assertStringNotContainsString('Nota de otro ticket', $html);
        $this->assertStringNotContainsString('<img src=x', $html);
        $this->assertStringContainsString('20/10/2026 · 10:35', $html);
    }

    public function test_financing_changes_show_each_changed_field_and_skip_unchanged_values(): void
    {
        $rows = \App\Services\TicketUpdateDetails::rows($this->ticket('Coordinar Curso'), [
            'financiamientos' => [['id_sence' => ['222'], 'name_course' => 'Mismo curso', 'f_star' => '2026-10-26']],
        ], [
            'financiamientos' => [['id_sence' => ['111'], 'name_course' => 'Mismo curso', 'f_star' => '2026-10-22']],
        ]);
        $this->assertCount(2, $rows);
        $this->assertSame('Financiamiento 1 · Código ID', $rows[0]['label']);
        $this->assertSame('111', $rows[0]['before']);
        $this->assertSame('222', $rows[0]['after']);
    }

    public function test_rolled_back_changes_do_not_send_email(): void
    {
        Mail::fake();
        $this->actingAs(new User(['email' => 'actor@example.com']));
        DB::beginTransaction();
        app(TicketUpdateNotifier::class)->schedule($this->ticket('Coordinar Curso'), ['description' => 'Cambio cancelado']);
        DB::rollBack();
        app(TicketUpdateNotifier::class)->flush();
        Mail::assertNothingSent();
    }

    public function test_saved_ticket_documents_and_notes_are_included_together_after_commit(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\Notification::fake();
        $this->actingAs(new User(['name' => 'Actor', 'email' => 'actor@example.com']));
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('id_sence')->nullable();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('ejecutivo_id');
            $table->timestamps();
        });
        Schema::create('typedocuments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->unsignedBigInteger('typedocument_id');
            $table->string('document_archive');
            $table->timestamps();
        });
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('followup_id');
            $table->text('note');
            $table->string('author');
            $table->timestamps();
        });
        DB::table('events')->insert(['id' => 1, 'name' => 'Coordinar Curso']);
        DB::table('users')->insert(['id' => 1, 'email' => 'coordinadora@example.com']);
        DB::table('typedocuments')->insert(['id' => 1, 'name' => 'Nómina']);
        DB::table('followups')->insert(['id' => 123, 'name' => 'TK-123', 'description' => 'Antes', 'id_sence' => '["111"]', 'event_id' => 1, 'ejecutivo_id' => 1]);

        DB::transaction(function () {
            $ticket = Followup::findOrFail(123);
            $ticket->update(['id_sence' => ['222'], 'description' => 'Después']);
            foreach (['archivo_uno.xlsx', 'archivo_dos.pdf'] as $file) {
                \App\Models\Document::create(['followup_id' => 123, 'typedocument_id' => 1, 'document_archive' => 'documentos/123/'.$file]);
            }
            DB::table('notes')->insert(['followup_id' => 123, 'author' => 'Autor nota', 'note' => '<p>Nota guardada junto al cambio</p>', 'created_at' => now(), 'updated_at' => now()]);
            Mail::assertNothingSent();
        });
        app()->terminate();

        Mail::assertSentCount(1);
        Mail::assertSent(CursoActualizacionMail::class, function ($mail) {
            $this->assertCount(4, $mail->updateRows);
            $this->assertCount(1, $mail->ticketNotes);
            $codeRow = collect($mail->updateRows)->firstWhere('label', 'Código ID');
            $this->assertSame('111', $codeRow['before']);
            $this->assertSame('222', $codeRow['after']);
            $html = $mail->render();
            foreach (['archivo_uno.xlsx', 'archivo_dos.pdf', 'Nota guardada junto al cambio'] as $text) {
                $this->assertStringContainsString($text, $html);
            }
            return true;
        });
    }
}
