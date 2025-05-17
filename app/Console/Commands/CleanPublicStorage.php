<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanPublicStorage extends Command
{
    protected $signature = 'app:clean-public-storage';
    protected $description = 'Limpia archivos de public/cv, public/images, public/vacancies y public/VacanciesPdfs, excepto la carpeta default.';

    public function handle(): void
    {
        $folders = [
            storage_path('app/public/cv'),
            storage_path('app/public/images'),
            storage_path('app/public/vacancies'),
            storage_path('app/public/VacanciesPdfs'),
        ];

        foreach ($folders as $folder) {
            if (!File::exists($folder)) {
                $this->warn("Carpeta no encontrada: $folder");
                continue;
            }

            $this->info("Limpiando: $folder");

            $items = File::directories($folder) + File::files($folder);

            foreach ($items as $item) {
                $itemName = basename($item);

                if ($itemName === 'default') {
                    continue;
                }

                File::isDirectory($item)
                    ? File::deleteDirectory($item)
                    : File::delete($item);

            }
        }

        $this->info('Limpieza completada.');
    }
}
