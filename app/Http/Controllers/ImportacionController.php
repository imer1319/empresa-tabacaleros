<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\Writer\HTML as HTMLWriter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\DB;

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
            'archivo' => 'required|file|mimes:xlsx,xls|max:5120'
        ], [
            'archivo.required' => 'Debe seleccionar un archivo Excel.',
            'archivo.file' => 'El archivo debe ser un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser un archivo Excel (.xlsx, .xls).',
            'archivo.max' => 'El archivo no debe pesar más de 5MB.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $archivo = $request->file('archivo');
            $spreadsheet = SpreadsheetIOFactory::load($archivo->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $data = $worksheet->rangeToArray('A1:' . $highestColumn . $highestRow);

            // Verificar encabezados
            $encabezadosRequeridos = [
                'FET N°', 'Razon Social', 'Calle Dom Real', 'Localidad', 'CUIT',
                'Kilos Entregados', 'Superficie Medida',
                'Empleados Convenio', 'Total Salario Convenio', 'Promedio Salario Convenio',
                'Empleados Fuera Convenio', 'Total Salario Fuera Convenio', 'Promedio Salario Fuera Convenio',
                'Jornal Promedio', 'AyC sobre Jornal', 'Formula', 'Total AyC',
                'TS Determinada', 'Total AyC Unitario', 'Total Jornales',
                'Jornales x Hectarea', 'Jornales x Hectarea sin AyC',
                'Jornales x Hectarea Acumulados', 'Dif Jornales x Has',
                'Actividades'
            ];
            $encabezados = array_map('trim', $data[0]);
            $encabezadosFaltantes = array_diff($encabezadosRequeridos, $encabezados);

            if (!empty($encabezadosFaltantes)) {
                return back()->withErrors([
                    'archivo' => 'Faltan las siguientes columnas requeridas: ' . implode(', ', $encabezadosFaltantes)
                ]);
            }

            // Mapear índices de columnas
            $indices = array_flip($encabezados);

            // Procesar datos
            $errores = [];
            $productoresCreados = 0;
            $productoresExistentes = 0;

            for ($i = 1; $i < count($data); $i++) {
                $fila = $data[$i];

                // Verificar si la fila está vacía
                if (empty(array_filter($fila))) {
                    continue;
                }

                // Obtener y limpiar valores
                $cuit = trim($fila[$indices['CUIT']]);
                $numeroProductor = trim($fila[$indices['FET N°']]);
                $nombreCompleto = trim($fila[$indices['Razon Social']]);
                $direccion = trim($fila[$indices['Calle Dom Real']]);
                $localidad = trim($fila[$indices['Localidad']]);

                // Verificar datos requeridos
                if (empty($cuit) || empty($numeroProductor) || empty($nombreCompleto)) {
                    $errores[] = "Fila " . ($i + 1) . ": Faltan datos requeridos (CUIT, FET N° o Razón Social)";
                    continue;
                }

                // Validar CUIT
                if (!preg_match('/^\d{11}$/', $cuit)) {
                    $errores[] = "Fila " . ($i + 1) . ": El CUIT debe contener 11 dígitos. CUIT: {$cuit}";
                    continue;
                }

                // Verificar duplicados
                if (Productor::where('cuit_cuil', $cuit)->orWhere('numero_productor', $numeroProductor)->exists()) {
                    $productoresExistentes++;
                    continue;
                }

                try {
                    $productor = Productor::create([
                        'numero_productor' => $numeroProductor,
                        'nombre_completo' => $nombreCompleto,
                        'direccion' => $direccion,
                        'localidad' => $localidad,
                        'cuit_cuil' => $cuit,
                        'estado_documentacion' => 'En proceso',
                        'kilos_entregados' => intval($fila[$indices['Kilos Entregados']] ?? 0),
                        'superficie_medida' => floatval($fila[$indices['Superficie Medida']] ?? 0),
                        'cant_empleados_convenio' => intval($fila[$indices['Empleados Convenio']] ?? 0),
                        'total_salario_convenio' => floatval($fila[$indices['Total Salario Convenio']] ?? 0),
                        'promedio_salario_convenio' => floatval($fila[$indices['Promedio Salario Convenio']] ?? 0),
                        'cant_empleados_fuera_convenio' => intval($fila[$indices['Empleados Fuera Convenio']] ?? 0),
                        'total_salario_fuera_convenio' => floatval($fila[$indices['Total Salario Fuera Convenio']] ?? 0),
                        'promedio_salario_fuera_convenio' => floatval($fila[$indices['Promedio Salario Fuera Convenio']] ?? 0),
                        'jornal_promedio' => floatval($fila[$indices['Jornal Promedio']] ?? 0),
                        'ayc_sobre_jornal' => floatval($fila[$indices['AyC sobre Jornal']] ?? 0),
                        'formula' => floatval($fila[$indices['Formula']] ?? 0),
                        'total_ayc' => floatval($fila[$indices['Total AyC']] ?? 0),
                        'ts_determinada' => floatval($fila[$indices['TS Determinada']] ?? 0),
                        'total_ayc_unitario' => floatval($fila[$indices['Total AyC Unitario']] ?? 0),
                        'total_jornales' => floatval($fila[$indices['Total Jornales']] ?? 0),
                        'jornales_x_hectarea' => floatval($fila[$indices['Jornales x Hectarea']] ?? 0),
                        'jornales_x_hectarea_sin_ayc' => floatval($fila[$indices['Jornales x Hectarea sin AyC']] ?? 0),
                        'jornales_x_hectarea_acumulados' => floatval($fila[$indices['Jornales x Hectarea Acumulados']] ?? 0),
                        'dif_jornales_x_has' => floatval($fila[$indices['Dif Jornales x Has']] ?? 0),
                        'actividades' => trim($fila[$indices['Actividades']] ?? '')
                    ]);

                    $productoresCreados++;
                } catch (\Exception $e) {
                    Log::error("Error al crear productor: " . $e->getMessage());
                    $errores[] = "Fila " . ($i + 1) . ": Error al crear el productor - " . $e->getMessage();
                }
            }

            $mensaje = "Se importaron {$productoresCreados} productores exitosamente.";
            if ($productoresExistentes > 0) {
                $mensaje .= " Se omitieron {$productoresExistentes} productores por estar duplicados.";
            }

            if (!empty($errores)) {
                return back()->withErrors([
                    'archivo' => $errores
                ])->with('success', $mensaje);
            }

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            Log::error("Error al procesar archivo Excel: " . $e->getMessage());
            return back()->withErrors([
                'archivo' => 'Error al procesar el archivo: ' . $e->getMessage()
            ]);
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

        DB::beginTransaction();
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
                // Buscar o crear el productor
                $productor = Productor::where(function($query) use ($dato) {
                    $query->where('numero_productor', $dato['fet_numero'])
                          ->orWhere('cuit_cuil', $dato['cuit']);
                })->first();

                if (!$productor) {
                    $productor = Productor::create([
                        'numero_productor' => $dato['fet_numero'],
                        'nombre_completo' => $dato['razon_social'],
                        'cuit_cuil' => $dato['cuit'],
                        'estado_documentacion' => 'En proceso'
                    ]);
                }

                // Crear una copia de la plantilla para cada registro
                $templateProcessor = new TemplateProcessor($tempPlantillaPath);

                // Reemplazar campos en el documento
                foreach ($request->campos_reemplazo as $campo => $valorCampo) {
                    $valor = $dato[$valorCampo] ?? '';
                    // Log para depuración
                    Log::info("Reemplazando campo: {$campo} con valor: {$valor} (campo origen: {$valorCampo})");
                    $templateProcessor->setValue($campo, $valor);
                }

                // Crear nombre único del archivo usando timestamp y hash
                $timestamp = time();
                $hash = substr(md5(uniqid()), 0, 8);
                $nombreBase = str_replace(' ', '_', $dato['razon_social']);
                $nombreArchivo = $timestamp . '_' . $hash . '_' . $nombreBase . '.docx';
                $rutaDestino = 'public/documentos/' . $nombreArchivo;

                // Asegurar que el directorio existe
                $directorioDestino = storage_path('app/public/documentos');
                if (!file_exists($directorioDestino)) {
                    mkdir($directorioDestino, 0755, true);
                }

                // Guardar el documento procesado
                $rutaCompleta = storage_path('app/' . $rutaDestino);
                $templateProcessor->saveAs($rutaCompleta);

                // Obtener el peso del archivo
                $pesoArchivo = file_exists($rutaCompleta) ? filesize($rutaCompleta) : 0;



                // Crear documento automáticamente
                $fechaVencimiento = now();
                $productor->documentos()->create([
                    'tipo_documento_id' => 9,
                    'nombre' => $nombreArchivo,
                    'ubicacion' => $rutaDestino,
                    'peso' => $pesoArchivo,
                    'estado' => 'pendiente',
                    'es_requerido' => true,
                    'fecha_entrega' => $fechaVencimiento,
                    'fecha_vencimiento' => $fechaVencimiento
                ]);

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

            DB::commit();
            return response()->json([
                'success' => true,
                'documentos' => $documentosGenerados,
                'message' => 'Se generaron ' . count($documentosGenerados) . ' documentos correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function enviarEmailPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo_excel' => 'required|file|mimes:xlsx,xls',
            'plantilla_word' => 'required|file|mimes:docx,doc',
            'fila_index' => 'required|integer|min:0',
            'campos_reemplazo' => 'required|array',
            'email_destinatario' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Procesar archivo Excel
            $excelFile = $request->file('archivo_excel');
            $spreadsheet = SpreadsheetIOFactory::load($excelFile->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            // Obtener datos de la fila específica
            $filaIndex = $request->input('fila_index');
            $filaReal = $filaIndex + 2; // +2 porque empezamos desde la fila 2 y el índice es 0-based

            $datos = [];
            $cellIterator = $worksheet->getRowIterator($filaReal, $filaReal)->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $datos[] = $cell->getValue();
            }

            if (empty($datos) || count($datos) < 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron datos válidos en la fila especificada'
                ], 400);
            }

            // Crear directorio temporal si no existe
            $directorioTemporal = storage_path('app/temp/plantillas');
            if (!file_exists($directorioTemporal)) {
                mkdir($directorioTemporal, 0755, true);
            }

            // Mover plantilla Word a directorio temporal
            $wordFile = $request->file('plantilla_word');
            $nombrePlantilla = 'plantilla_' . time() . '_' . uniqid() . '.docx';
            $rutaPlantillaCompleta = $directorioTemporal . '/' . $nombrePlantilla;
            $wordFile->move($directorioTemporal, $nombrePlantilla);

            // Procesar plantilla Word
            $templateProcessor = new TemplateProcessor($rutaPlantillaCompleta);
            $camposReemplazo = $request->input('campos_reemplazo');

            // Reemplazar campos en la plantilla
            foreach ($camposReemplazo as $campo => $indice) {
                if (isset($datos[$indice])) {
                    $templateProcessor->setValue($campo, $datos[$indice]);
                }
            }

            // Guardar documento procesado
            $nombreArchivoWord = 'documento_' . time() . '_' . uniqid() . '.docx';
            $rutaArchivoWord = $directorioTemporal . '/' . $nombreArchivoWord;
            $templateProcessor->saveAs($rutaArchivoWord);

            // Convertir Word a HTML
            $phpWord = IOFactory::load($rutaArchivoWord);
            $htmlWriter = new HTMLWriter($phpWord);
            $contenidoHtml = $htmlWriter->getContent();

            // Limpiar HTML
            $contenidoLimpio = strip_tags($contenidoHtml, '<p><br><strong><b><em><i><u><table><tr><td><th><thead><tbody><h1><h2><h3><h4><h5><h6><ul><ol><li>');
            $contenidoLimpio = preg_replace('/\s+/', ' ', $contenidoLimpio);
            $contenidoLimpio = trim($contenidoLimpio);

            // Configurar Dompdf
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new Dompdf($options);

            // CSS personalizado
            $css = '
                body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 20px; }
                p { margin: 8px 0; }
                table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                h1, h2, h3, h4, h5, h6 { margin: 15px 0 10px 0; }
                ul, ol { margin: 10px 0; padding-left: 20px; }
                li { margin: 5px 0; }
            ';

            $htmlFinal = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>';
            $htmlFinal .= $css;
            $htmlFinal .= '</style></head><body>';
            $htmlFinal .= $contenidoLimpio;
            $htmlFinal .= '</body></html>';

            // Generar PDF
            $dompdf->loadHtml($htmlFinal);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Obtener contenido del PDF
            $pdfContent = $dompdf->output();

            // Generar nombre del archivo
            $razonSocial = isset($datos[1]) ? $datos[1] : 'documento';
            $razonSocialLimpia = preg_replace('/[^A-Za-z0-9_-]/', '_', $razonSocial);
            $nombreArchivoPdf = $razonSocialLimpia . '_' . date('Y-m-d_H-i-s') . '.pdf';

            // Enviar email
            $emailDestinatario = $request->input('email_destinatario');

            // Enviar email con PDF adjunto
            Mail::raw('Se adjunta el documento PDF generado.', function ($message) use ($emailDestinatario, $pdfContent, $nombreArchivoPdf) {
                $message->to($emailDestinatario)
                    ->subject('Documento PDF - ' . date('Y-m-d H:i:s'))
                    ->attachData($pdfContent, $nombreArchivoPdf, [
                        'mime' => 'application/pdf',
                    ]);
            });

            // Limpiar archivos temporales
            if (file_exists($rutaPlantillaCompleta)) {
                unlink($rutaPlantillaCompleta);
            }
            if (file_exists($rutaArchivoWord)) {
                unlink($rutaArchivoWord);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email enviado exitosamente a ' . $emailDestinatario
            ]);
        } catch (\Exception $e) {
            Log::error('Error enviando email con PDF: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar email: ' . $e->getMessage()
            ], 500);
        }
    }
}
