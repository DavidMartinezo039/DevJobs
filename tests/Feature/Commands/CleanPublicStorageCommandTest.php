<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::shouldReceive('exists')->byDefault()->andReturn(true);
    File::shouldReceive('directories')->byDefault()->andReturn([]);
    File::shouldReceive('files')->byDefault()->andReturn([]);
});

it('muestra advertencia si alguna carpeta no existe', function () {
    File::shouldReceive('exists')
        ->withArgs(fn($path) => str_contains($path, 'vacancies'))
        ->andReturn(false);

    $this->artisan('app:clean-public-storage')
        ->expectsOutputToContain('Carpeta no encontrada')
        ->assertSuccessful();
});

it('borra archivos y carpetas excepto "default"', function () {
    $folder = storage_path('app/public/cv');

    File::shouldReceive('exists')->with($folder)->andReturn(true);
    File::shouldReceive('directories')->with($folder)->andReturn([
        $folder . '/to-delete-dir',
        $folder . '/default'
    ]);
    File::shouldReceive('files')->with($folder)->andReturn([
        $folder . '/file1.txt',
        $folder . '/default'
    ]);

    File::shouldReceive('isDirectory')->with($folder . '/to-delete-dir')->andReturn(true);
    File::shouldReceive('isDirectory')->with($folder . '/default')->andReturn(true);
    File::shouldReceive('isDirectory')->with($folder . '/file1.txt')->andReturn(false);
    File::shouldReceive('isDirectory')->with($folder . '/default')->andReturn(false);

    File::shouldReceive('deleteDirectory')->with($folder . '/to-delete-dir')->once();
    File::shouldReceive('delete')->with($folder . '/file1.txt')->once();

    File::shouldReceive('deleteDirectory')->with($folder . '/default')->never();
    File::shouldReceive('delete')->with($folder . '/default')->never();

    $this->artisan('app:clean-public-storage')
        ->expectsOutputToContain("Limpiando: $folder")
        ->expectsOutput('Limpieza completada.')
        ->assertSuccessful();
});

it('funciona con todas las carpetas configuradas', function () {
    $folders = [
        storage_path('app/public/cv'),
        storage_path('app/public/images'),
        storage_path('app/public/vacancies'),
        storage_path('app/public/VacanciesPdfs'),
    ];

    foreach ($folders as $folder) {
        File::shouldReceive('exists')->with($folder)->andReturn(true);
        File::shouldReceive('directories')->with($folder)->andReturn([]);
        File::shouldReceive('files')->with($folder)->andReturn([]);
    }

    $this->artisan('app:clean-public-storage')
        ->expectsOutput('Limpieza completada.')
        ->assertSuccessful();
});
