<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Inertia\Inertia;

class ImportacionController extends Controller
{
    public function index()
    {
        return Inertia::render('Importacion/Index');
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
            $spreadsheet = IOFactory::load($archivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Verificar encabezados
            $encabezadosRequeridos = ['FET N°', 'Razon Social', 'Calle Dom Real', 'Localidad', 'CUIT'];
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
            $totalFilas = count($data);
            // Verificar que no haya filas vacías al final del archivo
            while ($totalFilas > 0 && empty(array_filter($data[$totalFilas - 1]))) {
                $totalFilas--;
            }
            $productoresCreados = 0;

            for ($i = 1; $i < $totalFilas; $i++) {
                $fila = $data[$i];

                // Verificar si la fila está vacía o si el CUIT está vacío
                if (empty(array_filter($fila)) || empty(trim($fila[$indices['CUIT']]))) {
                    Log::info("Fila " . ($i + 1) . ": Fila vacía o CUIT vacío");
                    continue;
                }

                $cuit = trim($fila[$indices['CUIT']]);

                // Validar CUIT
                if (!preg_match('/^\d{11}$/', $cuit)) {
                    $mensaje = "Fila " . ($i + 1) . ": El CUIT debe contener 11 dígitos. Cuit de productor: " . trim($fila[$indices['CUIT']]);
                    Log::error($mensaje);
                    $errores[] = $mensaje;
                    continue;
                }

                // Verificar si el productor ya existe por CUIT o número de productor
                $numeroProductor = trim($fila[$indices['FET N°']]);

                // Si ya existe un productor con ese CUIT, simplemente pasamos al siguiente registro
                if (Productor::where('cuit_cuil', $cuit)->exists()) {
                    continue;
                }

                // Si ya existe un productor con ese número, simplemente pasamos al siguiente registro
                if (Productor::where('numero_productor', $numeroProductor)->exists()) {
                    continue;
                }

                try {
                    Productor::create([
                        'numero_productor' => trim($fila[$indices['FET N°']]),
                        'nombre_completo' => trim($fila[$indices['Razon Social']]),
                        'direccion' => trim($fila[$indices['Calle Dom Real']]),
                        'localidad' => trim($fila[$indices['Localidad']]),
                        'cuit_cuil' => $cuit,
                        'estado_documentacion' => 'En proceso'
                    ]);
                    $productoresCreados++;
                } catch (\Exception $e) {
                    $errores[] = "Fila " . ($i + 1) . ": Error al crear el productor - " . $e->getMessage();
                }
            }

            if (!empty($errores)) {
                return back()->withErrors([
                    'archivo' => $errores
                ]);
            }

            return back()->with('success', "Se importaron {$productoresCreados} productores exitosamente.");
        } catch (\Exception $e) {
            return back()->withErrors([
                'archivo' => 'Error al procesar el archivo: ' . $e->getMessage()
            ]);
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
