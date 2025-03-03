<?php

namespace App\Http\Controllers;

use App\Mail\SendCotizacion;
use App\Models\AddCourse;
use App\Models\Cotizacion;
use App\Models\Course;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function download($id)
    {

        $cotizacion = Cotizacion::findOrFail($id);
        $course = Course::findOrFail($cotizacion->course_id);
        $addCourse = AddCourse::findOrFail($cotizacion->add_course_id);
        $costs = is_string($cotizacion->costs) ? json_decode($cotizacion->costs, true) : $cotizacion->costs;


        $data = [
            'cotizacion' => $cotizacion,
            'course' => $course,
            'addCourse' => $addCourse,
            'costs' => $costs,
        ];

        //dd($costs);
        //$pdf = PDF::loadView('pdf.cotizacion', $data)->setPaper('letter');
        $pdf = PDF::loadView('pdf.cotizacion', $data)
        ->setPaper('letter')
        ->setOption('encoding', 'UTF-8');
    

        $fileName = 'cotizacion_' . $cotizacion->name . '.pdf';


        return $pdf->stream($fileName);
        //return $pdf->download($fileName);
    }

    //////////////////////////////////////////////////////////////////////////////

    public function sendPdf(Request $request, $id)
    {
        // Obtener los datos
        $cotizacion = Cotizacion::findOrFail($id);
        $course = Course::findOrFail($cotizacion->course_id);
        $addCourse = AddCourse::findOrFail($cotizacion->add_course_id);
        $costs = is_string($cotizacion->costs) ? json_decode($cotizacion->costs, true) : $cotizacion->costs;

        $data = [
            'cotizacion' => $cotizacion,
            'course' => $course,
            'addCourse' => $addCourse,
            'costs' => $costs,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pdf.cotizacion', $data)->setPaper('letter');

        // Ruta donde se guardará el PDF en storage/app/public/agenda/oc/
        $directory = storage_path('uploads/agenda/coti');
        $fileName = 'cotizacion_' . $cotizacion->id . '.pdf';
        $pdfPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        // Crear la carpeta si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Guardar el PDF
        $pdf->save($pdfPath);
       // dd($pdfPath);
        // Obtener el correo del usuario autenticado
        $userEmail = auth()->user()->email;



        // Obtener los correos del request y agregar el del usuario autenticado
        $emails = array_merge([$userEmail], $request->emails ?? []);

        // Enviar el correo a la cola
        Mail::to($emails)->queue(new SendCotizacion($data, $pdfPath));
        
        
        // Eliminar el archivo después de enviarlo
if (file_exists($pdfPath)) {
    unlink($pdfPath);
}

        // Notificación de éxito
        Notification::make()
            ->title('Correo enviado con éxito')
            ->success()
            ->send();

        return redirect()->back();
        
       
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
