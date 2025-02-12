<?php

namespace App\Http\Controllers;

use App\Mail\SendInvitaciones;
use App\Models\Followup;
use App\Models\Invitacion;
use App\Models\LogInvitacion;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InvitacionesController extends Controller
{
    public function readEmails(Request $request, $id)
    {
        $invitaciones = Invitacion::findOrFail($id);
        $followp = Followup::findOrFail($invitaciones->follow_id);

        if (empty($followp->doc_participant)) {
            Notification::make()
                ->title('No se encuentra el archivo con los participantes')
                ->warning()
                ->send();

            return redirect()->back();
        }

        // Ruta Local
       // $ruta = storage_path('app/public' . DIRECTORY_SEPARATOR . $followp->doc_participant);

        //Ruta Nube
        $ruta = public_path('uploads' . DIRECTORY_SEPARATOR . 'agenda' . DIRECTORY_SEPARATOR . 'participantes' . DIRECTORY_SEPARATOR . $followp->doc_participant);
        
        $filePath = str_replace('\\', '/', $ruta);



        // dd($filePath);

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $emails = [];

        foreach ($worksheet->getRowIterator(2) as $row) {
            $cell = $worksheet->getCell("F" . $row->getRowIndex());
            $email = $cell->getValue();

            // Elimina todos los espacios en blanco dentro del correo
            $email = str_replace(' ', '', $email);

            // Normaliza el valor eliminando caracteres invisibles y espacios externos
            $email = trim($email);

            // Verifica si el valor es un array o una cadena separada por comas
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Si hay más de un correo, separa por coma y agrega todos al array
                $emailList = array_map('trim', explode(',', $email));
                $emails = array_merge($emails, $emailList);  // Fusiona los correos nuevos con los anteriores
            }
        }

        //dd($emails);

        $data = [
            'name_course' => ucwords(strtolower($followp->name_course)),
            'link_clases' => $invitaciones->link_clases,
            'link_moodle' => $invitaciones->link_moodle,
            'password' => $invitaciones->password,
            'horarios' => $followp->week,
            'f_star' => Carbon::parse($followp->f_star)->format('d/m/Y'),
            'f_end' => Carbon::parse($followp->f_end)->format('d/m/Y'),
            'h_star' => $followp->h_star,
            'h_end' => $followp->h_end,
        ];

        //dd($data);
        try {

            Mail::bcc($emails)->queue(new SendInvitaciones($data));
        } catch (\Exception $e) {

            Notification::make()
                ->title('Error al enviar el correo')
                ->body('Ha ocurrido un problema durante el envío de las invitaciones. Por favor, inténtelo nuevamente.')
                ->warning()
                ->send();

            return redirect()->back()->with('error', 'No se pudo enviar el correo. Inténtalo de nuevo.');
        }
        try {

            $totalEmails = count($emails);


            $invitaciones->date_execution = now();
            $invitaciones->save();

            $log = new LogInvitacion();
            $log->invitacion_id = $invitaciones->id;
            $log->type = 'Manual';
            $log->count = $totalEmails;
            $log->emails = json_encode($emails);
            $log->status = 'Completado';
            $log->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
        $invitaciones->date_execution = now();
        $invitaciones->save();

        Notification::make()
            ->title('Invitaciones enviadas')
            ->body('Las invitaciones se han enviado correctamente.')
            ->success()
            ->send();

        return redirect()->back()->with('success', 'Correos enviados exitosamente.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
