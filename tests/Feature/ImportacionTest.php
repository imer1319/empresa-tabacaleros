<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ImportacionTest extends TestCase
{
    #[Test]
    public function puede_importar_productores_desde_excel(): void
    {
        Storage::fake("local");

        $user = User::create([
            "name" => "Test User",
            "email" => "test_".time()."@example.com",
            "password" => Hash::make("password"),
        ]);

        $this->actingAs($user);

        $excelFile = UploadedFile::fake()->create("productores.xlsx", 100);
        $wordFile = UploadedFile::fake()->create("plantilla.docx", 100);

        $response = $this->post(route("importacion.procesar-excel"), [
            "archivo_excel" => $excelFile,
            "plantilla_word" => $wordFile,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "Archivo Excel procesado correctamente. Se encontraron 0 registros."
            ]);
    }
}
