<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Writer\HTML as HTMLWriter;
use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;

class ImportacionController extends Controller
{
    public function index()
    {
        return Inertia::render('Importacion/Index');
    }

    public function procesarExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo_excel' => 'required|file|mimes:xlsx,xls|max:5120',
            'plantilla_word' => 'required|file|mimes:docx,doc|max:5120'
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo Excel.',
            'archivo_excel.file' => 'El archivo Excel debe ser un archivo válido.',
            'archivo_excel.mimes' => 'El archivo debe ser un archivo Excel (.xlsx, .xls).',
            'archivo_excel.max' => 'El archivo Excel no debe pesar más de 5MB.',
            'plantilla_word.required' => 'Debe seleccionar una plantilla Word.',
            'plantilla_word.file' => 'La plantilla Word debe ser un archivo válido.',
            'plantilla_word.mimes' => 'La plantilla debe ser un archivo Word (.docx, .doc).',
            'plantilla_word.max' => 'La plantilla Word no debe pesar más de 5MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $archivo = $request->file('archivo_excel');
            $spreadsheet = IOFactory::load($archivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Verificar encabezados
            $encabezadosRequeridos = ['FET N°', 'Razon Social', 'Calle Dom Real', 'Localidad', 'CUIT'];
            $encabezados = array_map('trim', $data[0]);
            $encabezadosFaltantes = array_diff($encabezadosRequeridos, $encabezados);

            if (!empty($encabezadosFaltantes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Faltan las siguientes columnas requeridas: ' . implode(', ', $encabezadosFaltantes)
                ], 400);
            }

            // Filtrar filas vacías y transformar a formato de objeto
            $datos = array_filter(array_slice($data, 1), function ($fila) {
                return !empty(array_filter($fila));
            });

            // Transformar los datos a un formato de objeto
            $datosFormateados = array_map(function ($fila) {
                return [
                    'fet_numero' => trim($fila[0]),
                    'razon_social' => trim($fila[1]),
                    'calle_dom_real' => trim($fila[2]),
                    'localidad' => trim($fila[3]),
                    'cuit' => trim($fila[4])
                ];
            }, array_values($datos));

            return response()->json([
                'success' => true,
                'message' => 'Archivo procesado correctamente',
                'datos' => $datosFormateados
            ]);
        } catch (\Exception $e) {
            Log::error('Error en procesarExcel: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importarProductores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datos' => 'required|array'
        ], [
            'datos.required' => 'Los datos son requeridos.',
            'datos.array' => 'Los datos deben ser un arreglo.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $datos = $request->input('datos');
            $errores = [];
            $productoresCreados = 0;

            foreach ($datos as $index => $fila) {
                // Verificar si la fila está vacía o si el CUIT está vacío
                if (empty($fila) || empty(trim($fila[4]))) { // El índice 4 corresponde al CUIT
                    Log::info("Fila " . ($index + 1) . ": Fila vacía o CUIT vacío");
                    continue;
                }

                $cuit = trim($fila[4]);
                $numeroProductor = trim($fila[0]); // El índice 0 corresponde al FET N°

                // Validar CUIT
                if (!preg_match('/^\d{11}$/', $cuit)) {
                    $mensaje = "Fila " . ($index + 1) . ": El CUIT debe contener 11 dígitos. Cuit de productor: " . $cuit;
                    Log::error($mensaje);
                    $errores[] = $mensaje;
                    continue;
                }

                // Verificar si el productor ya existe por CUIT o número de productor
                if (Productor::where('cuit_cuil', $cuit)->exists()) {
                    continue;
                }

                if (Productor::where('numero_productor', $numeroProductor)->exists()) {
                    continue;
                }

                try {
                    Productor::create([
                        'numero_productor' => $numeroProductor,
                        'nombre_completo' => trim($fila[1]), // Razon Social
                        'direccion' => trim($fila[2]), // Calle Dom Real
                        'localidad' => trim($fila[3]), // Localidad
                        'cuit_cuil' => $cuit,
                        'estado_documentacion' => 'En proceso'
                    ]);
                    $productoresCreados++;
                } catch (\Exception $e) {
                    Log::error('Error al crear productor: ' . $e->getMessage());
                    $errores[] = "Fila " . ($index + 1) . ": Error al crear el productor - " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => empty($errores),
                'message' => empty($errores)
                    ? "Se importaron {$productoresCreados} productores exitosamente."
                    : "Se encontraron errores durante la importación.",
                'errores' => $errores,
                'productores_creados' => $productoresCreados
            ]);
        } catch (\Exception $e) {
            Log::error('Error en importarProductores: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarDocumentos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plantilla_word' => 'required|file|mimes:docx,doc|max:5120',
            'datos' => 'required|array',
            'campos_reemplazo' => 'required|array'
        ], [
            'plantilla_word.required' => 'Debe seleccionar una plantilla Word.',
            'plantilla_word.file' => 'La plantilla Word debe ser un archivo válido.',
            'plantilla_word.mimes' => 'La plantilla debe ser un archivo Word (.docx, .doc).',
            'plantilla_word.max' => 'La plantilla Word no debe pesar más de 5MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obtener los datos enviados desde el frontend
            $datos = $request->input('datos');
            if (empty($datos) || !is_array($datos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron datos para procesar'
                ], 400);
            }

            // Verificar que cada dato tenga la estructura esperada
            foreach ($datos as $dato) {
                if (
                    !isset($dato['fet_numero']) || !isset($dato['razon_social']) ||
                    !isset($dato['calle_dom_real']) || !isset($dato['localidad']) ||
                    !isset($dato['cuit'])
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uno o más registros no tienen el formato esperado'
                    ], 400);
                }

                // Verificar que los datos no estén vacíos
                if (empty(array_filter($dato))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uno o más registros están vacíos'
                    ], 400);
                }
            }

            // Obtener campos de reemplazo
            $camposReemplazo = $request->input('campos_reemplazo');
            $documentosGenerados = [];

            // Log para debug
            Log::info('Contenido de la solicitud:', [
                'all' => $request->all(),
                'files' => $request->allFiles(),
                'has_file' => $request->hasFile('plantilla_word'),
                'file' => $request->file('plantilla_word'),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method()
            ]);

            // Verificar si el archivo está presente en la solicitud
            if (!$request->hasFile('plantilla_word')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el archivo de plantilla Word en la solicitud'
                ], 400);
            }

            // Validar archivo Word
            $validator = Validator::make($request->all(), [
                'plantilla_word' => 'required|file|mimes:docx,doc|max:5120'
            ], [
                'plantilla_word.required' => 'Debe seleccionar una plantilla Word.',
                'plantilla_word.file' => 'La plantilla Word debe ser un archivo válido.',
                'plantilla_word.mimes' => 'La plantilla debe ser un archivo Word (.docx, .doc).',
                'plantilla_word.max' => 'La plantilla Word no debe pesar más de 5MB.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('plantilla_word')
                ], 400);
            }

            // Obtener y validar el archivo Word
            $wordFile = $request->file('plantilla_word');
            if (!$wordFile || !$wordFile->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo de plantilla Word no es válido o está corrupto'
                ], 400);
            }

            // Crear directorio temporal si no existe
            $directorioTemp = storage_path('app/temp');
            if (!file_exists($directorioTemp)) {
                mkdir($directorioTemp, 0755, true);
            }

            // Copiar el archivo Word a una ubicación temporal
            $tempWordPath = $directorioTemp . DIRECTORY_SEPARATOR . 'plantilla_' . uniqid() . '.docx';
            if (!copy($wordFile->getPathname(), $tempWordPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al copiar la plantilla Word'
                ], 500);
            }

            // Procesar cada registro
            foreach ($datos as $dato) {
                // Crear nombre único para el documento
                $nombreDocumento = 'doc_' . $dato['fet_numero'] . '_' . uniqid();

                // Procesar plantilla Word usando la copia temporal
                $templateProcessor = new TemplateProcessor($tempWordPath);

                // Reemplazar campos en la plantilla
                foreach ($camposReemplazo as $campo => $valorCampo) {
                    $valor = $dato[$valorCampo] ?? '';
                    $templateProcessor->setValue($campo, $valor);
                }

                // Crear directorio temporal si no existe
                $directorioTemp = storage_path('app/temp');
                if (!file_exists($directorioTemp)) {
                    mkdir($directorioTemp, 0755, true);
                }

                // Guardar documento temporal
                $tempDocx = $directorioTemp . DIRECTORY_SEPARATOR . $nombreDocumento . '.docx';
                $templateProcessor->saveAs($tempDocx);

                // Convertir DOCX a HTML
                $phpWord = WordIOFactory::load($tempDocx);
                $htmlWriter = new HTMLWriter($phpWord);

                $tempHtml = $directorioTemp . DIRECTORY_SEPARATOR . $nombreDocumento . '.html';
                $htmlWriter->save($tempHtml);

                // Leer contenido HTML
                $html = file_get_contents($tempHtml);

                // Configurar Dompdf
                $options = new Options();
                $options->set('defaultFont', 'Arial');
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // Obtener contenido del PDF
                $pdfContent = $dompdf->output();

                // Guardar PDF
                $pdfPath = $directorioTemp . DIRECTORY_SEPARATOR . $nombreDocumento . '.pdf';
                file_put_contents($pdfPath, $pdfContent);

                // Agregar documento a la lista
                $documentosGenerados[] = [
                    'nombre' => basename($pdfPath),
                    'ruta' => $pdfPath,
                    'fet_numero' => $dato['fet_numero'],
                    'razon_social' => $dato['razon_social']
                ];

                // Limpiar archivos temporales
                unlink($tempDocx);
                unlink($tempHtml);
            }

            // Limpiar archivo temporal de la plantilla
            if (file_exists($tempWordPath)) {
                unlink($tempWordPath);
            }

            // Devolver respuesta
            return response()->json([
                'success' => true,
                'message' => 'Documentos generados exitosamente',
                'documentos' => $documentosGenerados
            ]);
        } catch (\Exception $e) {
            Log::error('Error en generarDocumentos: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Limpiar archivo temporal de la plantilla en caso de error
            if (isset($tempWordPath) && file_exists($tempWordPath)) {
                unlink($tempWordPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al generar documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generarPdfIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datos' => 'required|array',
            'campos_reemplazo' => 'required|array',
            'nombre_documento' => 'required|string',
            'plantilla_word' => 'required|file|mimes:docx,doc|max:5120'
        ], [
            'datos.required' => 'Los datos son requeridos.',
            'datos.array' => 'Los datos deben ser un arreglo.',
            'campos_reemplazo.required' => 'Los campos de reemplazo son requeridos.',
            'campos_reemplazo.array' => 'Los campos de reemplazo deben ser un arreglo.',
            'nombre_documento.required' => 'El nombre del documento es requerido.',
            'nombre_documento.string' => 'El nombre del documento debe ser una cadena de texto.',
            'plantilla_word.required' => 'Debe seleccionar una plantilla Word.',
            'plantilla_word.file' => 'La plantilla Word debe ser un archivo válido.',
            'plantilla_word.mimes' => 'La plantilla debe ser un archivo Word (.docx, .doc).',
            'plantilla_word.max' => 'La plantilla Word no debe pesar más de 5MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obtener los datos enviados desde el frontend
            $datos = $request->input('datos');
            if (empty($datos) || !is_array($datos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron datos para procesar'
                ], 400);
            }

            // Verificar que cada dato tenga la estructura esperada
            foreach ($datos as $dato) {
                if (
                    !isset($dato['fet_numero']) || !isset($dato['razon_social']) ||
                    !isset($dato['calle_dom_real']) || !isset($dato['localidad']) ||
                    !isset($dato['cuit'])
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uno o más registros no tienen el formato esperado'
                    ], 400);
                }

                // Verificar que los datos no estén vacíos
                if (empty(array_filter($dato))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uno o más registros están vacíos'
                    ], 400);
                }
            }

            // Obtener campos de reemplazo
            $camposReemplazo = $request->input('campos_reemplazo');
            $documentosGenerados = [];

            // Procesar cada registro
            foreach ($datos as $dato) {
                // Crear directorio temporal si no existe
                $directorioTemporal = storage_path('app/temp/plantillas');
                if (!file_exists($directorioTemporal)) {
                    mkdir($directorioTemporal, 0755, true);
                }

                // Procesar plantilla Word
                $archivoWord = $request->file('plantilla_word');
                $rutaOriginal = $archivoWord->getPathname();
                $nombreArchivo = 'plantilla_' . time() . '_' . uniqid() . '_' . $archivoWord->getClientOriginalName();
                $rutaPlantillaCompleta = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

                // Crear una copia del archivo
                copy($rutaOriginal, $rutaPlantillaCompleta);

                if (!file_exists($rutaPlantillaCompleta)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la plantilla'
                    ], 404);
                }

                // Crear una copia de la plantilla para procesar
                $templateProcessor = new TemplateProcessor($rutaPlantillaCompleta);

                // Reemplazar campos en el documento
                foreach ($camposReemplazo as $campo => $valorCampo) {
                    $valor = $dato[$valorCampo] ?? '';
                    $templateProcessor->setValue($campo, $valor);
                }

                // Generar nombre único para el archivo procesado
                $nombreArchivoProcesado = 'procesado_' . time() . '_' . uniqid() . '_' . $dato['fet_numero'] . '.docx';
                $rutaArchivoProcesado = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivoProcesado;

                // Guardar el documento procesado
                $templateProcessor->saveAs($rutaArchivoProcesado);

                if (!file_exists($rutaArchivoProcesado)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar el documento'
                    ], 500);
                }

                // Convertir DOCX a HTML usando PhpWord
                $phpWord = IOFactory::load($rutaArchivoProcesado);
                $htmlWriter = new HTMLWriter($phpWord);

                // Generar nombre único para el archivo HTML
                $nombreArchivoHtml = 'html_' . time() . '_' . uniqid() . '_' . $dato['fet_numero'] . '.html';
                $rutaArchivoHtml = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivoHtml;

                // Guardar como HTML
                $htmlWriter->save($rutaArchivoHtml);

                if (!file_exists($rutaArchivoHtml)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al convertir a HTML'
                    ], 500);
                }

                // Leer el contenido HTML
                $html = file_get_contents($rutaArchivoHtml);

                // Configurar Dompdf
                $options = new Options();
                $options->set('defaultFont', 'Arial');
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);

                // Limpiar el HTML y extraer solo el contenido del body
                $dom = new \DOMDocument();
                @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $body = $dom->getElementsByTagName('body')->item(0);

                if ($body) {
                    $contenidoLimpio = '';
                    foreach ($body->childNodes as $child) {
                        $contenidoLimpio .= $dom->saveHTML($child);
                    }
                } else {
                    $contenidoLimpio = $html;
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
                if (file_exists($rutaArchivoProcesado)) {
                    unlink($rutaArchivoProcesado);
                }
                if (file_exists($rutaArchivoHtml)) {
                    unlink($rutaArchivoHtml);
                }

                // Generar nombre del archivo PDF usando el fet_numero
                $nombreArchivoPdf = 'pdf_' . $dato['fet_numero'] . '_' . date('Y-m-d_H-i-s') . '.pdf';

                // Obtener el contenido del PDF
                $pdfContent = $dompdf->output();

                // Guardar el PDF en el directorio temporal
                $rutaArchivoPdf = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivoPdf;
                file_put_contents($rutaArchivoPdf, $pdfContent);

                // Devolver el PDF como respuesta
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . basename($rutaArchivoPdf) . '"')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }
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
