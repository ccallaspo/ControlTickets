<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Models\Cotizacion;


class CotizacionController extends Controller
{

public function clonar(Cotizacion $cotizacion)
    {
        try {
            $newCotizacion = $cotizacion->replicate();
            
            $prefix = 'OT/';
            $latestNumber = Cotizacion::where('name', 'like', $prefix . '%')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(name, '/', -1) AS UNSIGNED)) as max_num")
                ->value('max_num');
            $newNumber = ((int) $latestNumber + 1 ?: 1);
            $newCotizacion->name = $prefix . $newNumber;

            if ($cotizacion->costs) {
                $newCotizacion->costs = $cotizacion->costs;
            }

            $newCotizacion->save();

            // Lógica para éxito
            Notification::make()
                ->title('Cotización clonada con éxito')
                ->body("La cotización #{$cotizacion->name} ha sido clonada como #{$newCotizacion->name}.")
                ->success()
                ->sendToDatabase(auth()->user());

            // Redirige al formulario de edición del nuevo registro
            return redirect()->route('filament.admin.resources.cotizacions.edit', ['record' => $newCotizacion]);

        } catch (\Exception $e) {
            // Lógica para error
            Notification::make()
                ->title('Error al clonar la cotización')
                ->body('Ocurrió un error inesperado. Por favor, inténtalo de nuevo.')
                ->danger()
                ->sendToDatabase(auth()->user());
            
            // Redirige a la página anterior en caso de error
            return redirect()->back();
        }
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
