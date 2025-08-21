<?php

namespace App\Http\Controllers;

use App\Events\DocumentoActualizado;
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
    public function store(Request $request)
    {
        $request->validate([
            'productor_id' => 'required|exists:productors,id',
            'nombre' => 'required|string|max:255',
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'observaciones' => 'nullable|string',
            'es_requerido' => 'boolean',
            'estado' => 'nullable|string|in:pendiente,entregado,aprobado,rechazado,vencido',
            'fecha_entrega' => 'nullable|date',
            'fecha_revision' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date'
        ]);

        $documento = new Documento($request->except('archivo'));

        // Manejar archivo si se subió
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();

            // Crear nombre del archivo: nombre_documento_extension
            $nombreDocumento = str_replace(' ', '_', $request->nombre);
            $nombreArchivo = $nombreDocumento . '.' . $extension;

            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $nombreArchivo;
            $documento->archivo_tamaño = $archivo->getSize();

            // Si no se especificó estado y se subió archivo, marcar como entregado
            if (!$request->estado) {
                $documento->estado = 'entregado';
            }
        } else {
            // Si no se especificó estado y no hay archivo, marcar como pendiente
            if (!$request->estado) {
                $documento->estado = 'pendiente';
            }
        }

        $documento->save();

        event(new DocumentoActualizado(
            $documento,
            $documento->getAttributes(),
            [],
            Auth::id()
        ));

        // Verificar si la petición viene de la vista del productor
        $referer = request()->headers->get('referer');
        $fromProductor = str_contains($referer, '/productores/');
        if ($fromProductor) {
            return redirect()->route('productores.show', $documento->productor_id)
                ->with('success', 'Documento creado exitosamente.');
        }

        return redirect()->route('documentos.index')
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
    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
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
            $extension = $archivo->getClientOriginalExtension();

            // Crear nombre del archivo: nombre_documento_extension
            $nombreDocumento = str_replace(' ', '_', $request->nombre);
            $nombreArchivo = $nombreDocumento . '.' . $extension;

            $rutaArchivo = $archivo->storeAs('documentos', $nombreArchivo, 'public');

            $documento->archivo_path = $rutaArchivo;
            $documento->archivo_nombre = $nombreArchivo;
            $documento->archivo_tamaño = $archivo->getSize();
        }

        // Guardar valores anteriores antes de actualizar
        $valoresAnteriores = $documento->getAttributes();

        // Actualizar fecha de revisión si se cambió el estado
        if ($request->filled('estado') && $documento->isDirty('estado')) {
            $documento->fecha_revision = now();
            $documento->revisado_por = Auth::user()->id;
        }

        $documento->save();

        // Identificar cambios significativos
        $cambios = array_diff_assoc(
            $documento->only(['estado', 'fecha_entrega', 'fecha_vencimiento', 'fecha_revision', 'revisado_por']),
            $valoresAnteriores
        );

        // Disparar evento si hubo cambios significativos
        if (!empty($cambios)) {
            event(new DocumentoActualizado(
                $documento,
                $cambios,
                $valoresAnteriores,
                Auth::id()
            ));
        }

        return redirect()->route('documentos.show', $documento)
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        // Guardar valores antes de eliminar
        $valoresAnteriores = $documento->getAttributes();

        // Eliminar archivo si existe
        if ($documento->archivo_path) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        event(new DocumentoActualizado(
            $documento,
            [],
            $valoresAnteriores,
            Auth::id()
        ));

        return redirect()->route('documentos.index')
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
