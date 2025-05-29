<?php

use App\Models\User;
use App\Models\CV;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use App\Jobs\GenerateCVPdf;
use Illuminate\Support\Facades\Queue;

test('descarga el PDF del CV si existe en el almacenamiento', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $cv = CV::factory()->create([
        'user_id' => $user->id,
        'file_path' => 'test_cv.pdf',
        'title' => 'MiCV',
    ]);

    // Crear el archivo falso en storage/app/public/cv/test_cv.pdf
    Storage::disk('public')->put('cv/test_cv.pdf', 'contenido del PDF');

    actingAs($user)
        ->get(route('cv.download', $cv))
        ->assertDownload('CV_MiCV.pdf');
});

test('despacha el job para generar PDF si no existe el archivo', function () {
    Storage::fake('public');
    Queue::fake();

    $user = User::factory()->create();
    $cv = CV::factory()->create([
        'user_id' => $user->id,
        'file_path' => 'cv_inexistente.pdf',
        'title' => 'CVGenerar',
    ]);

    // No ponemos nada en storage: el archivo no existe

    actingAs($user)
        ->get(route('cv.download', $cv))
        ->assertRedirect()
        ->assertSessionHas('success', 'El CV se está generando en PDF. Por favor, inténtalo más tarde.');

    Queue::assertPushed(GenerateCVPdf::class, function ($job) use ($cv) {
        return $job->cv->is($cv);
    });
});
