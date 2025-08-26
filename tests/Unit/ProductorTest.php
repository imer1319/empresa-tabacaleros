<?php

namespace Tests\Unit;

use App\Models\Productor;
use App\Models\Documento;
use App\Models\Historial;
use App\Models\Cita;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProductorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function puede_crear_un_productor()
    {
        $productorData = [
            'numero_productor' => 'FET001',
            'nombre_completo' => 'Juan Pérez',
            'cuit_cuil' => '20123456789',
            'telefono' => '3814567890',
            'email' => 'juan@example.com',
            'direccion' => 'Calle Principal 123',
            'localidad' => 'San Miguel',
            'departamento' => 'Tafí Viejo',
            'estado_documentacion' => 'En proceso' // Valor válido del enum: 'En proceso', 'Aprobado', 'Faltante'
        ];

        $productor = Productor::create($productorData);

        $this->assertInstanceOf(Productor::class, $productor);
        $this->assertDatabaseHas('productors', $productorData);
    }

    /** @test */
    public function tiene_relacion_con_documentos()
    {
        $productor = Productor::factory()->create();
        $documento = Documento::factory()->create(['productor_id' => $productor->id]);

        $this->assertInstanceOf(Documento::class, $productor->documentos->first());
        $this->assertEquals($documento->id, $productor->documentos->first()->id);
    }

    /** @test */
    public function tiene_relacion_con_historial()
    {
        $productor = Productor::factory()->create();
        // El primer historial se crea automáticamente por el trait RegistraHistorial
        $historial = Historial::factory()->create(['productor_id' => $productor->id]);

        $this->assertInstanceOf(Historial::class, $productor->historial->first());
        // Verificamos que el historial creado manualmente sea el segundo registro
        $this->assertEquals($historial->id, $productor->historial->skip(1)->first()->id);
    }

    /** @test */
    public function tiene_relacion_con_citas()
    {
        $productor = Productor::factory()->create();
        $cita = Cita::factory()->create(['productor_id' => $productor->id]);

        $this->assertInstanceOf(Cita::class, $productor->citas->first());
        $this->assertEquals($cita->id, $productor->citas->first()->id);
    }

    /** @test */
    public function los_campos_son_fillable()
    {
        $productor = new Productor;
        $fillable = [
            'numero_productor',
            'nombre_completo',
            'cuit_cuil',
            'telefono',
            'email',
            'direccion',
            'localidad',
            'departamento',
            'estado_documentacion'
        ];

        $this->assertEquals($fillable, $productor->getFillable());
    }

    /** @test */
    public function estado_documentacion_es_string()
    {
        $productor = Productor::factory()->create([
            'estado_documentacion' => 'En proceso'
        ]);

        $this->assertIsString($productor->estado_documentacion);
        $this->assertEquals('En proceso', $productor->estado_documentacion);
    }

    /** @test */
    public function validar_campos_requeridos()
    {
        $request = new \App\Http\Requests\ProductorRequest();
        $request->setContainer(app());
        $request->setRedirector(app()->make(\Illuminate\Routing\Redirector::class));

        $productorData = [];
        $request->merge($productorData);

        try {
            $request->validateResolved();
            $this->fail('Se esperaba una excepción de validación por campos requeridos');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('numero_productor', $e->errors());
            $this->assertArrayHasKey('nombre_completo', $e->errors());
            $this->assertArrayHasKey('cuit_cuil', $e->errors());
            $this->assertArrayHasKey('telefono', $e->errors());
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertArrayHasKey('direccion', $e->errors());
            $this->assertArrayHasKey('localidad', $e->errors());
            $this->assertArrayHasKey('departamento', $e->errors());
            $this->assertArrayHasKey('estado_documentacion', $e->errors());
        }
    }

    /** @test */
    public function validar_formato_cuit_cuil()
    {
        $request = new \App\Http\Requests\ProductorRequest();
        $request->setContainer(app());
        $request->setRedirector(app()->make(\Illuminate\Routing\Redirector::class));

        $productorData = [
            'numero_productor' => 'FET001',
            'nombre_completo' => 'Juan Pérez',
            'cuit_cuil' => '123', // Formato inválido
            'telefono' => '3814567890',
            'email' => 'juan@example.com',
            'direccion' => 'Calle Principal 123',
            'localidad' => 'San Miguel',
            'departamento' => 'Tafí Viejo',
            'estado_documentacion' => 'En proceso'
        ];

        $request->merge($productorData);

        try {
            $request->validateResolved();
            $this->fail('Se esperaba una excepción de validación por CUIT/CUIL inválido');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('cuit_cuil', $e->errors());
        }
    }

    /** @test */
    public function validar_formato_email()
    {
        $request = new \App\Http\Requests\ProductorRequest();
        $request->setContainer(app());
        $request->setRedirector(app()->make(\Illuminate\Routing\Redirector::class));

        $productorData = [
            'numero_productor' => 'FET001',
            'nombre_completo' => 'Juan Pérez',
            'cuit_cuil' => '20123456789',
            'telefono' => '3814567890',
            'email' => 'correo-invalido',
            'direccion' => 'Calle Principal 123',
            'localidad' => 'San Miguel',
            'departamento' => 'Tafí Viejo',
            'estado_documentacion' => 'En proceso'
        ];

        $request->merge($productorData);

        try {
            $request->validateResolved();
            $this->fail('Se esperaba una excepción de validación por email inválido');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }
    }

    /** @test */
    public function validar_estado_documentacion_enum()
    {
        $request = new \App\Http\Requests\ProductorRequest();
        $request->setContainer(app());
        $request->setRedirector(app()->make(\Illuminate\Routing\Redirector::class));

        $productorData = [
            'numero_productor' => 'FET001',
            'nombre_completo' => 'Juan Pérez',
            'cuit_cuil' => '20123456789',
            'telefono' => '3814567890',
            'email' => 'juan@example.com',
            'direccion' => 'Calle Principal 123',
            'localidad' => 'San Miguel',
            'departamento' => 'Tafí Viejo',
            'estado_documentacion' => 'Estado Inválido'
        ];

        $request->merge($productorData);

        try {
            $request->validateResolved();
            $this->fail('Se esperaba una excepción de validación por estado_documentacion inválido');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('estado_documentacion', $e->errors());
        }
    }

    /** @test */
    public function eliminar_productor_elimina_relaciones()
    {
        // Crear productor con relaciones
        $productor = Productor::factory()->create();
        $documento = Documento::factory()->create(['productor_id' => $productor->id]);
        $historial = Historial::factory()->create(['productor_id' => $productor->id]);
        $cita = Cita::factory()->create(['productor_id' => $productor->id]);

        // Verificar que las relaciones existen
        $this->assertDatabaseHas('documentos', ['id' => $documento->id]);
        $this->assertDatabaseHas('historial_cambios', ['id' => $historial->id]);
        $this->assertDatabaseHas('citas', ['id' => $cita->id]);

        // Eliminar productor
        $productor->delete();

        // Verificar que el productor y sus relaciones fueron eliminados
        $this->assertDatabaseMissing('productors', ['id' => $productor->id]);
        $this->assertDatabaseMissing('documentos', ['id' => $documento->id]);
        $this->assertDatabaseMissing('historial_cambios', ['id' => $historial->id]);
        $this->assertDatabaseMissing('citas', ['id' => $cita->id]);
    }

    /** @test */
    public function actualizar_campos_productor()
    {
        // Crear productor
        $productor = Productor::factory()->create([
            'nombre_completo' => 'Juan Pérez',
            'telefono' => '3814567890',
            'email' => 'juan@example.com'
        ]);

        // Datos para actualizar
        $datosActualizados = [
            'nombre_completo' => 'Juan Pablo Pérez',
            'telefono' => '3814567891',
            'email' => 'juanpablo@example.com'
        ];

        // Actualizar productor
        $productor->update($datosActualizados);

        // Recargar el modelo desde la base de datos
        $productor->refresh();

        // Verificar que los campos se actualizaron correctamente
        $this->assertEquals($datosActualizados['nombre_completo'], $productor->nombre_completo);
        $this->assertEquals($datosActualizados['telefono'], $productor->telefono);
        $this->assertEquals($datosActualizados['email'], $productor->email);

        // Verificar que se registró el cambio en el historial
        $this->assertDatabaseHas('historial_cambios', [
            'productor_id' => $productor->id,
            'tipo_operacion' => 'actualizacion'
        ]);
    }
}