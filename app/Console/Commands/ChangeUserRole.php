<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class ChangeUserRole extends Command
{
    protected $signature = 'change:user-role {email? : El correo del usuario}';

    protected $description = 'Cambiar el rol de un usuario por su correo electrónico';

    public function handle(): void
    {
        $email = $this->argument('email');

        while (true) {
            if (!$email) {
                $email = $this->ask('Introduce el correo electrónico del usuario');
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("No se encontró usuario con el correo '$email'.");

                // Buscar emails similares con levenshtein
                $allEmails = User::pluck('email')->toArray();

                $similarEmails = collect($allEmails)
                    ->mapWithKeys(function ($emailDB) use ($email) {
                        $distance = levenshtein($email, $emailDB);
                        return [$emailDB => $distance];
                    })
                    ->filter(fn($distance) => $distance <= 3)
                    ->sortBy(fn($distance) => $distance)
                    ->keys()
                    ->take(5);

                if ($similarEmails->isNotEmpty()) {
                    $this->info("¿Quizás quisiste decir uno de estos emails?");
                    foreach ($similarEmails as $similarEmail) {
                        $this->line(" - $similarEmail");
                    }
                } else {
                    $this->info('No se encontraron emails similares.');
                }

                $email = null;
                continue;
            }

            break;
        }

        $currentRole = $user->roles->first()->name;

        $roles = Role::pluck('name')->filter(fn($roleName) => $roleName !== $currentRole)->values();

        $this->info("El usuario tiene el rol actual: '{$currentRole}'.");
        $this->info("Roles disponibles para asignar:");

        foreach ($roles as $index => $roleName) {
            $this->line(" [$index] $roleName");
        }

        $selectedIndex = $this->ask('Selecciona el número del nuevo rol');

        if (!isset($roles[$selectedIndex])) {
            $this->error('Selección inválida, operación cancelada.');
            return;
        }

        $newRole = $roles[$selectedIndex];

        // Quitar roles antiguos y asignar el nuevo
        $user->syncRoles([$newRole]);

        $this->info("El rol del usuario '{$user->name}' ha sido cambiado a '{$newRole}'.");
    }
}
