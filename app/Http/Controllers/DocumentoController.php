<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
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
                    ->orWhereHas('tipoDocumento', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                    })
                    ->orWhereHas('productor', function ($q) use ($search) {
                        $q->where('nombre_completo', 'like', "%{$search}%")
                            ->orWhere('numero_productor', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_documento_id')) {
            $query->where('tipo_documento_id', $request->tipo_documento_id);
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
            'filters' => $request->only(['search', 'estado', 'tipo_documento_id', 'vencidos'])
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
        $productores = Productor::orderBy('numero_productor')->get();

        return Inertia::render('Documentos/Create', [
            'productor' => $productor,
            'tiposDocumento' => $tiposDocumento,
            'productores' => $productores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentoRequest $request)
    {
        $validated = $request->validated();

        $documento = new Documento($validated);

        // Manejar archivo si se subió
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();

            // Crear nombre único del archivo usando timestamp y hash
            $timestamp = time();
            $hash = substr(md5(uniqid()), 0, 8);
            $nombreBase = str_replace(' ', '_', $request->nombre);
            $nombreArchivo = $timestamp . '_' . $hash . '_' . $nombreBase . '.' . $extension;

            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $nombreArchivo;
            $documento->archivo_tamaño = $archivo->getSize();
        }

        $documento->save();

        // Verificar si la petición viene de la vista del productor
        $referer = request()->headers->get('referer');
        $fromProductor = str_contains($referer, '/productores/');
        if ($fromProductor) {
            return redirect()->route('productores.show', $documento->productor_id)
                ->with('success', "Documento: {$documento->nombre}\nEstado: {$documento->estado}");
        }

        return redirect()->route('documentos.index')
            ->with('success', "Documento: {$documento->nombre}\nEstado: {$documento->estado}");
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
        $productores = Productor::orderBy('numero_productor')->get();
        return Inertia::render('Documentos/Edit', [
            'documento' => $documento,
            'tiposDocumento' => $tiposDocumento,
            'productores' => $productores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentoRequest $request, Documento $documento)
    {
        $validated = $request->validated();
        $documento->fill($validated);
        // Manejar nuevo archivo si se subió
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($documento->archivo_path) {
                Storage::disk('public')->delete($documento->archivo_path);
            }

            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();

            // Crear nombre único del archivo usando timestamp y hash
            $timestamp = time();
            $hash = substr(md5(uniqid()), 0, 8);
            $nombreBase = str_replace(' ', '_', $request->nombre);
            $nombreArchivo = $timestamp . '_' . $hash . '_' . $nombreBase . '.' . $extension;

            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $nombreArchivo;
            $documento->archivo_tamaño = $archivo->getSize();
        }

        // Actualizar fecha de revisión si se cambió el estado
        if ($request->filled('estado') && $documento->isDirty('estado')) {
            $documento->fecha_revision = now();
            $documento->revisado_por = Auth::user()->id;
        }

        $documento->save();

        // Verificar si la petición viene de la vista del productor
        $referer = request()->headers->get('referer');
        $fromProductor = str_contains($referer, '/productores/');
        if ($fromProductor) {
            return redirect()->route('productores.show', $documento->productor_id)
                ->with('success', "Documento: {$documento->nombre}\nEstado: {$documento->estado}");
        }

        return redirect()->route('documentos.index')
            ->with('success', "Documento: {$documento->nombre}\nEstado: {$documento->estado}");
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

        $documento->delete();

        return redirect()->route('documentos.index')
            ->with('success', "Documento: {$documento->nombre} eliminado");
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
