<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Productor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'productor_id' => 'required|exists:productors,id',
            'descripcion' => 'required|string',
            'fecha_visita' => 'required|date',
            'fecha_proxima_cita' => 'nullable|date|after:fecha_visita',
            'estado' => 'required|in:pendiente,asistio,no_asistio',
        ]);

        $cita = Cita::create($validated);

        return redirect()->back()
            ->with('success', 'Cita registrada exitosamente.');
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string',
            'fecha_visita' => 'required|date',
            'fecha_proxima_cita' => 'nullable|date|after:fecha_visita',
            'estado' => 'required|in:pendiente,asistio,no_asistio',
        ]);

        $cita->update($validated);

        return redirect()->back()
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->back()
            ->with('success', 'Cita eliminada exitosamente.');
    }
}