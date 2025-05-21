<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Realiza un backup de la base de datos';

    public function handle()
    {
        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path("app/private/backups/{$filename}");

        $db = config('database.connections.mysql');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            $db['username'],
            $db['password'],
            $db['host'],
            $db['database'],
            $path
        );

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Backup creado: $filename");
        } else {
            $this->error("Error al crear el backup");
        }
    }
}
