<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use App\Models\Documento;
use App\Models\Cita;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas generales
        $totalProductores = Productor::count();
        $nuevosProductoresMes = Productor::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        $documentosAprobados = Documento::where('estado', 'aprobado')->count();
        $documentosAprobadosSemana = Documento::where('estado', 'aprobado')
            ->where('updated_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        $documentosPendientes = Documento::where('estado', 'pendiente')->count();
        $documentosPendientesAyer = Documento::where('estado', 'pendiente')
            ->where('updated_at', '>=', Carbon::yesterday())
            ->count();

        $comunicacionesEnviadas = 156; // TODO: Implementar modelo de comunicaciones
        $comunicacionesEnviadasHoy = 23;

        // Obtener actividades recientes
        $actividadesRecientes = collect();

        // Documentos recientes
        $documentosRecientes = Documento::with(['productor', 'tipoDocumento'])
            ->whereIn('estado', ['aprobado', 'rechazado', 'entregado'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($documento) {
                $tipo = $documento->estado === 'aprobado' ? 'aprobado' : 'rechazado';
                $accion = $documento->estado === 'aprobado' ? 'aprobó' : 'rechazó';
                if ($documento->estado === 'entregado') {
                    $tipo = 'aprobado';
                    $accion = 'entregó';
                }

                return [
                    'id' => 'doc_' . $documento->id,
                    'tipo' => $tipo,
                    'descripcion' => "<span class='font-medium'>{$documento->productor->nombre_completo}</span> {$accion} <span class='font-medium'>{$documento->tipoDocumento->nombre}</span>",
                    'tiempo' => $documento->updated_at->diffForHumans(),
                    'fecha' => $documento->updated_at
                ];
            });

        $actividadesRecientes = $actividadesRecientes->concat($documentosRecientes);

        // Documentos por vencer
        $documentosPorVencer = Documento::with(['productor', 'tipoDocumento'])
            ->whereDate('fecha_vencimiento', '>', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(7))
            ->take(3)
            ->get()
            ->map(function ($documento) {
                return [
                    'id' => 'venc_' . $documento->id,
                    'tipo' => 'vencimiento',
                    'descripcion' => "<span class='font-medium'>{$documento->productor->nombre_completo}</span> tiene <span class='font-medium'>{$documento->tipoDocumento->nombre}</span> por vencer",
                    'tiempo' => 'Vence ' . $documento->fecha_vencimiento->diffForHumans(),
                    'fecha' => $documento->fecha_vencimiento
                ];
            });

        $actividadesRecientes = $actividadesRecientes->concat($documentosPorVencer);

        // Citas recientes
        $citasRecientes = Cita::with('productor')
            ->whereIn('estado', ['programada', 'completada', 'cancelada'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($cita) {
                $tipo = 'comunicacion';
                $accion = match ($cita->estado) {
                    'programada' => 'programó',
                    'completada' => 'completó',
                    'cancelada' => 'canceló'
                };

                return [
                    'id' => 'cita_' . $cita->id,
                    'tipo' => $tipo,
                    'descripcion' => "<span class='font-medium'>{$cita->productor->nombre_completo}</span> {$accion} una cita para {$cita->fecha_visita->format('d/m/Y')}",
                    'tiempo' => $cita->updated_at->diffForHumans(),
                    'fecha' => $cita->updated_at
                ];
            });

        $actividadesRecientes = $actividadesRecientes->concat($citasRecientes);

        // Ordenar por fecha y limitar a 10 actividades
        $actividadesRecientes = $actividadesRecientes->sortByDesc('fecha')->take(10)->values();
        $totalSumaKilosEntregados = Productor::sum('kilos_entregados');
        $totalSumaSuperficieMedida = Productor::sum('superficie_medida');
        $cantidadProductordifmenor = Productor::where('dif_jornales_x_has', '<', 0)->count();
        $productoresDifMenor = Productor::where('dif_jornales_x_has', '<', 0)->get();

        // Obtener estado de documentos por tipo
        $tiposDocumento = [
            'Contratos de Siembra' => $this->obtenerEstadisticasDocumento(1),
            'Certificados AFIP' => $this->obtenerEstadisticasDocumento(2),
            'Licencias Municipales' => $this->obtenerEstadisticasDocumento(3),
            'Seguros Agrícolas' => $this->obtenerEstadisticasDocumento(4)
        ];

        return Inertia::render('Dashboard', [
            'estadisticas' => [
                'cantidadProductordifmenor' => $cantidadProductordifmenor,
                'productoresDifMenor' => $productoresDifMenor,
                'totalSumaSuperficieMedida' => $totalSumaSuperficieMedida,
                'totalSumaKilosEntregados' => $totalSumaKilosEntregados,
                'totalProductores' => $totalProductores,
                'nuevosProductoresMes' => $nuevosProductoresMes,
                'documentosAprobados' => $documentosAprobados,
                'documentosAprobadosSemana' => $documentosAprobadosSemana,
                'documentosPendientes' => $documentosPendientes,
                'documentosPendientesAyer' => $documentosPendientesAyer,
                'comunicacionesEnviadas' => $comunicacionesEnviadas,
                'comunicacionesEnviadasHoy' => $comunicacionesEnviadasHoy
            ],
            'actividadesRecientes' => $actividadesRecientes,
            'estadoDocumentos' => $tiposDocumento
        ]);
    }

    private function obtenerEstadisticasDocumento($tipoDocumentoId)
    {
        $total = Documento::where('tipo_documento_id', $tipoDocumentoId)->count();
        $completados = Documento::where('tipo_documento_id', $tipoDocumentoId)
            ->whereIn('estado', ['aprobado', 'entregado'])
            ->count();
        $pendientes = $total - $completados;

        $porcentaje = $total > 0 ? round(($completados / $total) * 100) : 0;

        return [
            'total' => $total,
            'completados' => $completados,
            'pendientes' => $pendientes,
            'porcentaje' => $porcentaje
        ];
    }
}
