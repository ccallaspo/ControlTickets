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
        $pdf = PDF::loadView('pdf.cotizacion', $data)->setPaper('letter');


        $fileName = 'cotizacion_' . $cotizacion->name . '.pdf';


        return $pdf->stream($fileName);
        //return $pdf->download($fileName);
    }

    /////////////////////////

    public function sendPdf(Request $request, $id)
    {

        // Obtener los datos necesarios
        $cotizacion = Cotizacion::findOrFail($id);
        $course = Course::findOrFail($cotizacion->course_id);
        $addCourse = AddCourse::findOrFail($cotizacion->add_course_id);
        $costs = is_string($cotizacion->costs) ? json_decode($cotizacion->costs, true) : $cotizacion->costs;

        // Preparar los datos para la vista
        $data = [
            'cotizacion' => $cotizacion,
            'course' => $course,
            'addCourse' => $addCourse,
            'costs' => $costs,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('pdf.cotizacion', $data)->setPaper('letter');

        // Ruta donde se guardará el PDF
        $directory = public_path('storage' . DIRECTORY_SEPARATOR . 'agenda' . DIRECTORY_SEPARATOR . 'oc');
        $fileName = 'cotizacion_' . $cotizacion->name . '.pdf';
        $pdfPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        // Crear la carpeta si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Guardar el PDF
        $pdf->save($pdfPath);

        // Obtener el correo del usuario logueado
        $userEmail = auth()->user()->email;

        // Extraer los correos como un array
        $emails = array_merge([$userEmail], $request->emails);

        // dd($request->emails);

        // 🔹 **Verificar si hay correos antes de enviar el email**
        if (empty($emails)) {
            //    return response()->json(['error' => 'Debe ingresar al menos un correo válido.'], 400);
        }

        // Enviar el correo con múltiples destinatarios
        Mail::send('mails.sendCotizacion', $data, function ($message) use ($emails, $pdfPath, $cotizacion, $userEmail) {
            $message->to($emails)
                    ->subject('OTEC Proyecta - Cotización #' . $cotizacion->id)
                    ->cc($userEmail)
                    ->attach($pdfPath, [
                        'as' => 'cotizacion_' . $cotizacion->id . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });

        // Eliminar el archivo PDF temporal después de enviarlo (opcional)
        unlink($pdfPath);

        // Respuesta JSON
        // ✅ Enviar la notificación en la vista
        Notification::make()
            ->title('Correo enviado con éxito')
            ->success()
            ->send();

        return redirect()->back(); // O redirige a otra página
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
