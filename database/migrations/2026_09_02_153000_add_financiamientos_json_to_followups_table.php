<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('followups', function (Blueprint $table) {
            $table->json('financiamientos')->nullable()->after('n_hours');
        });

        $rows = DB::table('followups')->select([
            'id',
            'cod_sence_course',
            'name_course',
            'id_sence',
            'modalily',
            'f_star',
            'f_end',
            'n_hours',
        ])->get();

        foreach ($rows as $row) {
            DB::table('followups')->where('id', $row->id)->update([
                'financiamientos' => json_encode([
                    [
                        'cod_sence_course' => $row->cod_sence_course,
                        'name_course' => $row->name_course,
                        'id_sence' => $this->normalizeIds($row->id_sence),
                        'modalily' => $row->modalily,
                        'f_star' => $row->f_star,
                        'f_end' => $row->f_end,
                        'n_hours' => $row->n_hours,
                    ],
                ]),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('followups', function (Blueprint $table) {
            $table->dropColumn('financiamientos');
        });
    }

    private function normalizeIds(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
        }

        $decoded = json_decode((string) $value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded, fn ($item) => $item !== null && $item !== ''));
        }

        return [(string) $value];
    }
};
