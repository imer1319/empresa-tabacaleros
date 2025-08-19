<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Productor::query();

        // Búsqueda por nombre, número de productor o CUIT
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'ILIKE', "%{$search}%")
                    ->orWhere('numero_productor', 'ILIKE', "%{$search}%")
                    ->orWhere('cuit_cuil', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por estado de documentación
        if ($request->has('estado') && $request->estado) {
            $query->where('estado_documentacion', $request->estado);
        }

        $productores = $query->orderBy('nombre_completo')->paginate(10);

        return Inertia::render('Productores/Index', [
            'productores' => $productores,
            'filters' => $request->only(['search', 'estado'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Productores/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_productor' => 'required|string|unique:productors',
            'nombre_completo' => 'required|string|max:255',
            'cuit_cuil' => 'required|string|unique:productors',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'required|string',
            'localidad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'estado_documentacion' => 'required|in:En proceso,Aprobado,Faltante'
        ]);

        Productor::create($validated);

        return redirect()->route('productores.index')
            ->with('success', 'Productor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Productor $productore)
    {
        return Inertia::render('Productores/Show', [
            'productor' => $productore->load(['documentos'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Productor $productore)
    {
        return Inertia::render('Productores/Edit', [
            'productor' => $productore
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Productor $productore)
    {
        $validated = $request->validate([
            'numero_productor' => 'required|string|unique:productors,numero_productor,' . $productore->id,
            'nombre_completo' => 'required|string|max:255',
            'cuit_cuil' => 'required|string|unique:productors,cuit_cuil,' . $productore->id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'required|string',
            'localidad' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'estado_documentacion' => 'required|in:En proceso,Aprobado,Faltante'
        ]);

        $productore->update($validated);

        return redirect()->route('productores.index')
            ->with('success', 'Productor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Productor $productore)
    {
        $productore->delete();

        return redirect()->route('productores.index')
            ->with('success', 'Productor eliminado exitosamente.');
    }
}
