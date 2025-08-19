<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Productor;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Documento::with(['productor', 'tipoDocumento', 'revisor']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('tipo', 'like', "%{$search}%")
                    ->orWhereHas('productor', function ($q) use ($search) {
                        $q->where('nombre_completo', 'like', "%{$search}%")
                            ->orWhere('numero_productor', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('vencidos')) {
            if ($request->vencidos === 'si') {
                $query->vencidos();
            } elseif ($request->vencidos === 'proximos') {
                $query->proximosAVencer();
            }
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);
        $tiposDocumento = TipoDocumento::activos()->ordenados()->get();

        return Inertia::render('Documentos/Index', [
            'documentos' => $documentos,
            'tiposDocumento' => $tiposDocumento,
            'filters' => $request->only(['search', 'estado', 'tipo', 'vencidos'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productorId = $request->get('productor_id');
        $productor = $productorId ? Productor::findOrFail($productorId) : null;
        $tiposDocumento = TipoDocumento::activos()->ordenados()->get();

        return Inertia::render('Documentos/Create', [
            'productor' => $productor,
            'tiposDocumento' => $tiposDocumento
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'productor_id' => 'required|exists:productors,id',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'observaciones' => 'nullable|string',
            'es_requerido' => 'boolean',
            'fecha_entrega' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after:today'
        ]);

        $documento = new Documento($request->except('archivo'));

        // Manejar archivo si se subió
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $archivo->getClientOriginalName();
            $documento->archivo_tamaño = $archivo->getSize();
            $documento->estado = 'entregado';
        } else {
            $documento->estado = 'pendiente';
        }

        $documento->save();

        return redirect()->route('productores.show', $documento->productor_id)
            ->with('success', 'Documento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        $documento->load(['productor', 'tipoDocumento', 'revisor']);

        return Inertia::render('Documentos/Show', [
            'documento' => $documento
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documento $documento)
    {
        $documento->load(['productor', 'tipoDocumento']);
        $tiposDocumento = TipoDocumento::activos()->ordenados()->get();

        return Inertia::render('Documentos/Edit', [
            'documento' => $documento,
            'tiposDocumento' => $tiposDocumento
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,entregado,aprobado,rechazado,vencido',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'observaciones' => 'nullable|string',
            'es_requerido' => 'boolean',
            'fecha_entrega' => 'nullable|date',
            'fecha_revision' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date'
        ]);

        $documento->fill($request->except('archivo'));

        // Manejar nuevo archivo si se subió
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($documento->archivo_path) {
                Storage::disk('public')->delete($documento->archivo_path);
            }

            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $archivo->getClientOriginalName();
            $documento->archivo_tamaño = $archivo->getSize();
        }

        // Actualizar fecha de revisión si se cambió el estado
        if ($request->filled('estado') && $documento->isDirty('estado')) {
            $documento->fecha_revision = now();
            $documento->revisado_por = Auth::user()->id;
        }

        $documento->save();

        return redirect()->route('documentos.show', $documento)
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        // Eliminar archivo si existe
        if ($documento->archivo_path) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $productorId = $documento->productor_id;
        $documento->delete();

        return redirect()->route('productores.show', $productorId)
            ->with('success', 'Documento eliminado exitosamente.');
    }

    /**
     * Descargar archivo del documento
     */
    public function download(Documento $documento)
    {
        if (!$documento->archivo_path || !Storage::disk('public')->exists($documento->archivo_path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download(
            $documento->archivo_path,
            $documento->archivo_nombre
        );
    }
}
