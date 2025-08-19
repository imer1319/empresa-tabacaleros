<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TipoDocumento::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === 'true');
        }

        $tiposDocumento = $query->ordenados()->paginate(15);

        return Inertia::render('TiposDocumento/Index', [
            'tiposDocumento' => $tiposDocumento,
            'filters' => $request->only(['search', 'activo'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('TiposDocumento/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_documentos,nombre',
            'descripcion' => 'nullable|string',
            'es_obligatorio' => 'boolean',
            'formatos_permitidos' => 'nullable|array',
            'formatos_permitidos.*' => 'string|in:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            'tamaño_maximo' => 'nullable|integer|min:1|max:51200', // Max 50MB
            'instrucciones' => 'nullable|string',
            'activo' => 'boolean',
            'orden' => 'integer|min:0'
        ]);

        TipoDocumento::create($request->all());

        return redirect()->route('tipos-documento.index')
                        ->with('success', 'Tipo de documento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoDocumento $tipoDocumento)
    {
        $tipoDocumento->load(['documentos.productor']);
        
        return Inertia::render('TiposDocumento/Show', [
            'tipoDocumento' => $tipoDocumento
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoDocumento $tipoDocumento)
    {
        return Inertia::render('TiposDocumento/Edit', [
            'tipoDocumento' => $tipoDocumento
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoDocumento $tipoDocumento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_documentos,nombre,' . $tipoDocumento->id,
            'descripcion' => 'nullable|string',
            'es_obligatorio' => 'boolean',
            'formatos_permitidos' => 'nullable|array',
            'formatos_permitidos.*' => 'string|in:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            'tamaño_maximo' => 'nullable|integer|min:1|max:51200',
            'instrucciones' => 'nullable|string',
            'activo' => 'boolean',
            'orden' => 'integer|min:0'
        ]);

        $tipoDocumento->update($request->all());

        return redirect()->route('tipos-documento.show', $tipoDocumento)
                        ->with('success', 'Tipo de documento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoDocumento $tipoDocumento)
    {
        // Verificar si hay documentos asociados
        if ($tipoDocumento->documentos()->count() > 0) {
            return redirect()->route('tipos-documento.index')
                            ->with('error', 'No se puede eliminar el tipo de documento porque tiene documentos asociados.');
        }

        $tipoDocumento->delete();

        return redirect()->route('tipos-documento.index')
                        ->with('success', 'Tipo de documento eliminado exitosamente.');
    }
}
