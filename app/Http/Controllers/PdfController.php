<?php

namespace App\Http\Controllers;

use App\Models\AddCourse;
use App\Models\Cotizacion;
use App\Models\Course;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
