<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\Writer\HTML as HTMLWriter;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportacionController extends Controller
{
    public function index()
    {
        return Inertia::render('Importacion/Index');
    }

    public function procesarExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo_excel' => 'required|file|mimes:xlsx,xls|max:10240',
            'plantilla_word' => 'required|file|mimes:docx,doc|max:10240'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Procesar archivo Excel directamente desde el archivo temporal
            $excelFile = $request->file('archivo_excel');
            $spreadsheet = SpreadsheetIOFactory::load($excelFile->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $datos = [];
            $filaInicio = 2; // Asumiendo que la fila 1 tiene encabezados

            foreach ($worksheet->getRowIterator($filaInicio) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $fila = [];
                foreach ($cellIterator as $cell) {
                    $fila[] = $cell->getValue();
                }

                // Verificar que la fila no esté vacía
                if (!empty(array_filter($fila))) {
                    $datos[] = [
                        'fet_numero' => $fila[0] ?? '',
                        'razon_social' => $fila[1] ?? '',
                        'calle_dom_real' => $fila[2] ?? '',
                        'localidad' => $fila[3] ?? '',
                        'cuit' => $fila[4] ?? ''
                    ];
                }
            }

            // Guardar plantilla Word en sesión para uso posterior
            $wordFile = $request->file('plantilla_word');
            $wordContent = file_get_contents($wordFile->getPathname());
            $request->session()->put('plantilla_word_content', base64_encode($wordContent));
            $request->session()->put('plantilla_word_name', $wordFile->getClientOriginalName());

            return response()->json([
                'success' => true,
                'datos' => $datos,
                'message' => 'Archivo Excel procesado correctamente. Se encontraron ' . count($datos) . ' registros.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importarProductores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datos' => 'required|array',
            'datos.*.fet_numero' => 'required',
            'datos.*.razon_social' => 'required|string|max:255',
            'datos.*.calle_dom_real' => 'required|string|max:255',
            'datos.*.localidad' => 'required|string|max:255',
            'datos.*.cuit' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $productoresCreados = 0;
            $errores = [];

            foreach ($request->datos as $index => $dato) {
                try {
                    // Verificar si ya existe un productor con el mismo CUIT
                    $existente = Productor::where('cuit', $dato['cuit'])->first();

                    if ($existente) {
                        $errores[] = "Fila " . ($index + 1) . ": Ya existe un productor con CUIT " . $dato['cuit'];
                        continue;
                    }

                    Productor::create([
                        'nombre' => $dato['razon_social'],
                        'cuit' => $dato['cuit'],
                        'direccion' => $dato['calle_dom_real'],
                        'localidad' => $dato['localidad'],
                        'telefono' => '',
                        'email' => '',
                        'estado' => 'activo',
                        'observaciones' => 'Importado desde Excel - FET N°: ' . $dato['fet_numero']
                    ]);

                    $productoresCreados++;
                } catch (\Exception $e) {
                    $errores[] = "Fila " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'productores_creados' => $productoresCreados,
                'errores' => $errores,
                'message' => "Importación completada. Se crearon {$productoresCreados} productores."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error durante la importación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarDocumentos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datos' => 'required|array',
            'campos_reemplazo' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Log para depuración
            Log::info('Datos recibidos para generar documentos:', [
                'datos_count' => count($request->datos ?? []),
                'campos_reemplazo' => $request->campos_reemplazo ?? [],
                'primer_dato' => $request->datos[0] ?? null
            ]);

            // Verificar que la plantilla esté en sesión
            $plantillaContent = $request->session()->get('plantilla_word_content');
            $plantillaNombre = $request->session()->get('plantilla_word_name');

            if (!$plantillaContent) {
                throw new \Exception('No se encontró la plantilla Word en la sesión. Por favor, vuelva a cargar los archivos.');
            }

            $documentosGenerados = [];

            // Crear archivo temporal para la plantilla
            $tempPlantillaPath = tempnam(sys_get_temp_dir(), 'plantilla_') . '.docx';
            file_put_contents($tempPlantillaPath, base64_decode($plantillaContent));

            foreach ($request->datos as $index => $dato) {
                // Crear una copia de la plantilla para cada registro
                $templateProcessor = new TemplateProcessor($tempPlantillaPath);

                // Reemplazar campos en el documento
                foreach ($request->campos_reemplazo as $campo => $valorCampo) {
                    $valor = $dato[$valorCampo] ?? '';
                    // Log para depuración
                    Log::info("Reemplazando campo: {$campo} con valor: {$valor} (campo origen: {$valorCampo})");
                    $templateProcessor->setValue($campo, $valor);
                }

                // Generar nombre único para el archivo usando razón social
                $razonSocialLimpia = preg_replace('/[^A-Za-z0-9_-]/', '_', $dato['razon_social'] ?? 'Sin_nombre');
                $nombreArchivo = $razonSocialLimpia . '_' . time() . '.docx';
                $rutaDestino = 'documentos_generados/' . $nombreArchivo;

                // Guardar el documento procesado
                $templateProcessor->saveAs(storage_path('app/' . $rutaDestino));

                $documentosGenerados[] = [
                    'nombre' => $nombreArchivo,
                    'ruta' => $rutaDestino,
                    'productor' => $dato['razon_social'] ?? 'Sin nombre'
                ];
            }

            // Limpiar archivo temporal de la plantilla
            if (file_exists($tempPlantillaPath)) {
                unlink($tempPlantillaPath);
            }

            // Limpiar datos de sesión
            $request->session()->forget(['plantilla_word_content', 'plantilla_word_name']);

            return response()->json([
                'success' => true,
                'documentos' => $documentosGenerados,
                'message' => 'Se generaron ' . count($documentosGenerados) . ' documentos correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function descargarDocumento($archivo)
    {
        $rutaArchivo = storage_path('app/documentos_generados/' . $archivo);

        if (!file_exists($rutaArchivo)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($rutaArchivo);
    }

    public function generarPdfConsolidado(Request $request)
    {
        try {
            // Obtener la lista de documentos generados desde la sesión o parámetros
            $documentos = $request->input('documentos', []);

            if (empty($documentos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay documentos para consolidar'
                ], 400);
            }

            // Configurar dompdf
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);

            $htmlConsolidado = '<html><head><meta charset="UTF-8"><style>';
            $htmlConsolidado .= 'body { font-family: Arial, sans-serif; margin: 1.5cm; padding: 0; line-height: 1.4; font-size: 14px; }';
            $htmlConsolidado .= '.documento { margin: 0; padding: 0; }';
            $htmlConsolidado .= '.separador { page-break-before: always; }';
            $htmlConsolidado .= 'table { border-collapse: collapse; width: 100%; margin: 0; }';
            $htmlConsolidado .= 'td, th { border: 1px solid #000; padding: 6px; text-align: left; font-size: 14px; }';
            $htmlConsolidado .= 'p { margin: 10px 0; font-size: 14px; }';
            $htmlConsolidado .= 'h1, h2, h3, h4, h5, h6 { margin: 12px 0; font-size: 16px; }';
            $htmlConsolidado .= 'div img { display: table-cell; vertical-align:middle; text-align:center !important; max-width: 4cm !important; height: auto !important; }';
            $htmlConsolidado .= 'div:has(img), p:has(img) { text-align: center !important; }';
            $htmlConsolidado .= '.center, [align="center"] { text-align: center !important; }';
            $htmlConsolidado .= '.right, [align="right"] { text-align: right !important; }';
            $htmlConsolidado .= 'div[style*="text-align: center"], p[style*="text-align: center"] { text-align: center !important; }';
            $htmlConsolidado .= '</style></head><body>';

            $primerDocumento = true;

            foreach ($documentos as $documento) {
                $rutaDocumento = storage_path('app/' . $documento['ruta']);

                if (!file_exists($rutaDocumento)) {
                    continue;
                }

                try {
                    // Cargar el documento Word
                    $phpWord = IOFactory::load($rutaDocumento);

                    // Convertir a HTML
                    Log::info('Iniciando conversión a HTML');
                    $htmlWriter = new HTMLWriter($phpWord);
                    $htmlContent = $htmlWriter->getContent();
                    Log::info('Conversión a HTML completada, longitud: ' . strlen($htmlContent));
                    Log::info('HTML generado (primeros 500 caracteres): ' . substr($htmlContent, 0, 500));

                    // Limpiar el HTML y extraer solo el contenido del body
                    $dom = new \DOMDocument();
                    @$dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $body = $dom->getElementsByTagName('body')->item(0);

                    if ($body) {
                        $contenidoLimpio = '';
                        foreach ($body->childNodes as $child) {
                            $contenidoLimpio .= $dom->saveHTML($child);
                        }
                        Log::info('Contenido limpio extraído (primeros 300 caracteres): ' . substr($contenidoLimpio, 0, 300));
                    } else {
                        $contenidoLimpio = $htmlContent;
                        Log::warning('No se encontró elemento body, usando HTML completo');
                    }

                    // Agregar separador de página si no es el primer documento
                    if (!$primerDocumento) {
                        $htmlConsolidado .= '<div class="separador"></div>';
                    }

                    // Agregar el contenido del documento sin título adicional
                    $htmlConsolidado .= '<div class="documento">';
                    $htmlConsolidado .= $contenidoLimpio;
                    $htmlConsolidado .= '</div>';

                    $primerDocumento = false;
                } catch (\Exception $e) {
                    Log::error('Error procesando documento: ' . $documento['nombre'], ['error' => $e->getMessage()]);
                    continue;
                }
            }

            $htmlConsolidado .= '</body></html>';

            // Generar PDF
            $dompdf->loadHtml($htmlConsolidado);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Generar nombre del archivo
            $nombreArchivo = 'documentos_consolidados_' . date('Y-m-d_H-i-s') . '.pdf';

            // Retornar el PDF como descarga
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            Log::error('Error generando PDF consolidado: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar PDF consolidado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarPdfIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo_excel' => 'required|file|mimes:xlsx,xls|max:10240',
            'plantilla_word' => 'required|file|mimes:docx,doc|max:10240',
            'fila_index' => 'required|integer|min:0',
            'campos_reemplazo' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Procesar archivo Excel para obtener los datos de la fila específica
            $excelFile = $request->file('archivo_excel');
            $spreadsheet = SpreadsheetIOFactory::load($excelFile->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $filaIndex = $request->input('fila_index');
            $filaInicio = 2; // Asumiendo que la fila 1 tiene encabezados
            $filaReal = $filaInicio + $filaIndex;

            // Obtener los datos de la fila específica
            $row = $worksheet->getRowIterator($filaReal, $filaReal)->current();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $fila = [];
            foreach ($cellIterator as $cell) {
                $fila[] = $cell->getValue();
            }

            // Crear array de datos con la estructura esperada
            $dato = [
                'fet_numero' => $fila[0] ?? '',
                'razon_social' => $fila[1] ?? '',
                'calle_dom_real' => $fila[2] ?? '',
                'localidad' => $fila[3] ?? '',
                'cuit' => $fila[4] ?? ''
            ];

            // Verificar que la fila no esté vacía
            if (empty(array_filter($fila))) {
                return response()->json([
                    'success' => false,
                    'message' => 'La fila seleccionada está vacía'
                ], 400);
            }

            // Procesar plantilla Word
            $archivoWord = $request->file('plantilla_word');

            // Crear directorio temporal si no existe
            $directorioTemporal = storage_path('app/temp/plantillas');
            if (!file_exists($directorioTemporal)) {
                mkdir($directorioTemporal, 0755, true);
            }

            // Generar nombre único para el archivo temporal
            $nombreArchivo = 'plantilla_' . time() . '_' . $archivoWord->getClientOriginalName();
            $rutaPlantillaCompleta = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

            // Mover el archivo a la ubicación temporal
            $archivoWord->move($directorioTemporal, $nombreArchivo);

            Log::info('Archivo guardado en: ' . $rutaPlantillaCompleta);
            Log::info('Archivo existe: ' . (file_exists($rutaPlantillaCompleta) ? 'Sí' : 'No'));

            if (!file_exists($rutaPlantillaCompleta)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento no encontrado'
                ], 404);
            }

            // Crear una copia de la plantilla para procesar
            $templateProcessor = new TemplateProcessor($rutaPlantillaCompleta);

            // Reemplazar campos en el documento
            foreach ($request->campos_reemplazo as $campo => $valorCampo) {
                $valor = $dato[$valorCampo] ?? '';
                Log::info("Reemplazando campo: {$campo} con valor: {$valor} (campo origen: {$valorCampo})");
                $templateProcessor->setValue($campo, $valor);
            }

            // Generar nombre único para el archivo procesado
            $razonSocialLimpia = preg_replace('/[^A-Za-z0-9_-]/', '_', $dato['razon_social'] ?? 'Sin_nombre');
            $nombreArchivoWord = $razonSocialLimpia . '_' . time() . '.docx';
            $rutaArchivoWord = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivoWord;

            // Guardar el documento procesado
            $templateProcessor->saveAs($rutaArchivoWord);

            // Configurar dompdf
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);

            // Cargar el documento Word procesado
            $phpWord = IOFactory::load($rutaArchivoWord);

            // Convertir a HTML
            $htmlWriter = new HTMLWriter($phpWord);
            $htmlContent = $htmlWriter->getContent();

            // Limpiar el HTML y extraer solo el contenido del body
            $dom = new \DOMDocument();
            @$dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $body = $dom->getElementsByTagName('body')->item(0);

            if ($body) {
                $contenidoLimpio = '';
                foreach ($body->childNodes as $child) {
                    $contenidoLimpio .= $dom->saveHTML($child);
                }
            } else {
                $contenidoLimpio = $htmlContent;
            }

            // Crear HTML completo con estilos para mantener el diseño original
            $htmlFinal = '<html><head><meta charset="UTF-8"><style>';
            $htmlFinal .= 'body { font-family: Arial, sans-serif; margin: 1cm; padding: 0; line-height: 1.4; font-size: 14px; }';
            $htmlFinal .= 'table { border-collapse: collapse; width: 100%; margin: 0; }';
            $htmlFinal .= 'td, th { border: 1px solid #000; padding: 6px; text-align: left; font-size: 14px; }';
            $htmlFinal .= 'p { margin: 10px 0; font-size: 14px; }';
            $htmlFinal .= 'h1, h2, h3, h4, h5, h6 { margin: 12px 0; font-size: 16px; }';
            $htmlFinal .= 'img { display: block !important; margin: 0 auto !important; max-width: 4cm !important; height: auto !important; }';
            $htmlFinal .= 'div:has(img), p:has(img) { text-align: center !important; }';
            $htmlFinal .= '.center, [align="center"] { text-align: center !important; }';
            $htmlFinal .= '.right, [align="right"] { text-align: right !important; }';
            $htmlFinal .= 'div[style*="text-align: center"], p[style*="text-align: center"] { text-align: center !important; }';
            $htmlFinal .= '</style></head><body>';
            $htmlFinal .= $contenidoLimpio;
            $htmlFinal .= '</body></html>';

            // Generar PDF
            $dompdf->loadHtml($htmlFinal);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Limpiar archivos temporales
            if (file_exists($rutaPlantillaCompleta)) {
                unlink($rutaPlantillaCompleta);
            }
            if (file_exists($rutaArchivoWord)) {
                unlink($rutaArchivoWord);
            }

            // Generar nombre del archivo PDF
            $nombreArchivoPdf = $razonSocialLimpia . '_' . date('Y-m-d_H-i-s') . '.pdf';

            // Retornar el PDF como descarga
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivoPdf . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            Log::error('Error generando PDF individual: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
