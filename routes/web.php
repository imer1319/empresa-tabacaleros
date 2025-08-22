<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductorController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\ImportacionController;
use App\Http\Controllers\CitaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/productores/importar', [\App\Http\Controllers\ImportacionController::class, 'importarProductores'])
        ->name('productores.importar');

    // Rutas para Productores
    Route::resource('productores', ProductorController::class);

    // Rutas para Documentos
    Route::resource('documentos', DocumentoController::class);
    Route::get('documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');

    // Rutas para Tipos de Documento
    Route::resource('tipos-documento', TipoDocumentoController::class)->parameters([
        'tipos-documento' => 'tipoDocumento'
    ]);

    // Rutas para Importación
    Route::get('/importacion', [ImportacionController::class, 'index'])->name('importacion.index');
    Route::post('/importacion/procesar-excel', [ImportacionController::class, 'procesarExcel'])->name('importacion.procesar-excel');
    Route::post('/importacion/importar-productores', [ImportacionController::class, 'importarProductores'])->name('importacion.importar-productores');
    Route::post('/importacion/generar-documentos', [ImportacionController::class, 'generarDocumentos'])->name('importacion.generar-documentos');
    Route::get('/importacion/descargar/{archivo}', [ImportacionController::class, 'descargarDocumento'])->name('importacion.descargar');
    Route::post('/importacion/generar-pdf-consolidado', [ImportacionController::class, 'generarPdfConsolidado'])->name('importacion.generar-pdf-consolidado');
    Route::post('/importacion/generar-pdf-individual', [ImportacionController::class, 'generarPdfIndividual'])->name('importacion.generar-pdf-individual');
    Route::post('/importacion/enviar-email-pdf', [ImportacionController::class, 'enviarEmailPdf'])->name('importacion.enviar-email-pdf');

    // Rutas para Citas
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
});

require __DIR__ . '/auth.php';
