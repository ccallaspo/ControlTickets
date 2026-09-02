<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeToJsonArray('id_sence');
        $this->normalizeToJsonArray('exec_id_sence');

        DB::statement('ALTER TABLE followups MODIFY id_sence JSON NULL');
        DB::statement('ALTER TABLE followups MODIFY exec_id_sence JSON NULL');
    }

    public function down(): void
    {
        $rows = DB::table('followups')->select('id', 'id_sence', 'exec_id_sence')->get();

        foreach ($rows as $row) {
            DB::table('followups')->where('id', $row->id)->update([
                'id_sence' => $this->flattenToString($row->id_sence),
                'exec_id_sence' => $this->flattenToString($row->exec_id_sence),
            ]);
        }

        DB::statement('ALTER TABLE followups MODIFY id_sence VARCHAR(255) NULL');
        DB::statement('ALTER TABLE followups MODIFY exec_id_sence VARCHAR(255) NULL');
    }

    private function normalizeToJsonArray(string $column): void
    {
        $rows = DB::table('followups')->select('id', $column)->get();

        foreach ($rows as $row) {
            $value = $row->{$column};

            if ($value === null || $value === '') {
                DB::table('followups')->where('id', $row->id)->update([$column => null]);
                continue;
            }

            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                continue;
            }

            DB::table('followups')->where('id', $row->id)->update([
                $column => json_encode([(string) $value]),
            ]);
        }
    }

    private function flattenToString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decoded = is_array($value) ? $value : json_decode((string) $value, true);
        if (is_array($decoded)) {
            $parts = array_filter(array_map('strval', $decoded));

            return $parts === [] ? null : implode(', ', $parts);
        }

        return (string) $value;
    }
};
