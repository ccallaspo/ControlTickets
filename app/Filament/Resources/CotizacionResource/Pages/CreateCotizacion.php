<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CreateCotizacion extends CreateRecord
{
    protected static string $resource = CotizacionResource::class;
    
    public function getHeading(): string
    {
        return '  ';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }
    
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $prefix = 'OT/';

            // Bloquea la búsqueda del último número hasta que se inserte el nuevo registro
            $latestNumber = Cotizacion::where('name', 'like', $prefix . '%')
                ->lockForUpdate()
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(name, '/', -1) AS UNSIGNED)) as max_num")
                ->value('max_num');

            $nextNumber = ((int) $latestNumber) + 1 ?: 1;
            $data['name'] = $prefix . $nextNumber;

            // Crea el registro dentro de la misma transacción/bloqueo
            return Cotizacion::create($data);
        });
    }
}
