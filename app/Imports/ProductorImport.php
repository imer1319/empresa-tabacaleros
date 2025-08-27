<?php

namespace App\Imports;

use App\Models\Productor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;

class ProductorImport implements ToModel, WithHeadingRow, WithStartRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable;

    public function startRow(): int
    {
        return 7; // Los datos comienzan en la fila 7
    }

    public function headingRow(): int
    {
        return 6; // Los encabezados están en la fila 6
    }

    /**
     * @param array $row
     */
    public function prepareForValidation($row)
    {
        // Registrar los encabezados para debugging
        \Illuminate\Support\Facades\Log::info('Encabezados del Excel:', $row);

        // Limpiar y convertir campos a string
        $row['cuit_productor'] = trim($row['cuit_productor'] ?? '');
        $row['nro_de_grupo_economico'] = trim($row['nro_de_grupo_economico'] ?? '');

        return $row;
    }

    public function model(array $row)
    {
        // Verificar si la fila está vacía
        if (empty(array_filter($row))) {
            return null;
        }
        // Preparar datos
        $datos = [
            'numero_productor' => trim($row['nro_de_grupo_economico'] ?? ''),
            'nombre_completo' => trim($row['productor_cabeza_de_grupo'] ?? ''),
            'cuit_cuil' => trim($row['cuit_productor'] ?? ''),
            'kilos_entregados' => intval($row['kilos_entregados'] ?? 0),
            'superficie_medida' => floatval($row['superficie_medida'] ?? 0),
            'cant_empleados_convenio' => intval($row['cant_empleados_convenio'] ?? 0),
            'total_salario_convenio' => floatval($row['total_salario_convenio'] ?? 0),
            'promedio_salario_convenio' => floatval($row['promedio_salario_convenio'] ?? 0),
            'cant_empleados_fuera_convenio' => intval($row['cant_empleados_fuera_convenio'] ?? 0),
            'total_salario_fuera_convenio' => floatval($row['total_salario_f_convenio'] ?? 0),
            'promedio_salario_fuera_convenio' => floatval($row['promedio_salario_f_convenio'] ?? 0),
            'jornal_promedio' => floatval($row['jornal_promedio'] ?? 0),
            'ayc_sobre_jornal' => floatval($row['a_y_c_s_jornal'] ?? 0),
            'formula' => floatval($row['formula'] ?? 0),
            'total_ayc' => floatval($row['total_a_y_c'] ?? 0),
            'ts_determinada' => floatval($row['ts_determinada'] ?? 0),
            'total_ayc_unitario' => floatval($row['total_a_y_c_unitario'] ?? 0),
            'total_jornales' => floatval($row['total_de_jornales'] ?? 0),
            'jornales_x_hectarea' => floatval($row['jornales_x_hectarea'] ?? 0),
            'jornales_x_hectarea_sin_ayc' => floatval($row['jornales_x_hectarea_s_ayc'] ?? 0),
            'jornales_x_hectarea_acumulados' => floatval($row['jornales_x_hectarea_acumulados'] ?? 0),
            'dif_jornales_x_has' => floatval($row['dif_de_j_has'] ?? 0),
            'actividades' => trim($row['actividades'] ?? '')
        ];

        // Buscar productor existente por número de productor
        $productor = Productor::where('numero_productor', $datos['numero_productor'])->first();

        if ($productor) {
            // Si existe, actualizar sus datos con los nuevos valores
            $productor->update($datos);
            return $productor;
        }

        // Si no existe, crear nuevo productor
        return Productor::create($datos);
    }

    public function rules(): array
    {
        return [
            'cuit_productor' => ['nullable', 'regex:/^\d{11}$/', 'string'],
            'nro_de_grupo_economico' => ['nullable', 'string']
        ];
    }

    public function onError(\Throwable $e)
    {
        throw new \Exception("Error al procesar la fila: " . $e->getMessage());
    }

    public function onFailure(Failure ...$failures)
    {
        $errores = [];
        foreach ($failures as $failure) {
            $fila = $failure->row();
            $atributo = $failure->attribute();
            $error = $failure->errors()[0];
            $valores = $failure->values();

            $mensaje = "Error en la fila {$fila}: ";
            if ($atributo === 'cuit_productor') {
                $mensaje .= "El CUIT '{$valores[$atributo]}' debe tener exactamente 11 dígitos";
            } elseif ($atributo === 'nro_de_grupo_economico') {
                $mensaje .= "El número de grupo económico es requerido";
            } else {
                $mensaje .= $error;
            }

            $errores[] = $mensaje;
        }

        throw new \Maatwebsite\Excel\Validators\ValidationException(
            \Illuminate\Validation\ValidationException::withMessages($errores),
            $failures
        );
    }
}
