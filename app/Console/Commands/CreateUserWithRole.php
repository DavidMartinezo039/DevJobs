<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserWithRole extends Command
{
    protected $signature = 'make:user
    {name? : El nombre del usuario}
    {email? : El correo del usuario}
    {role? : El rol del usuario}';

    protected $description = 'Crea un usuario con un rol específico';

    public function handle(): void
    {
        $name = $this->argument('name') ?? $this->ask('Nombre del usuario (ej: Juan Pérez)');
        $email = $this->argument('email') ?? $this->ask('Correo electrónico (ej: juan@example.com)');
        $password = $this->secret('Contraseña (mínimo 8 caracteres)');

        $roles = Role::pluck('name')->values();

        if ($this->argument('role')) {
            $role = $this->argument('role');
        } else {
            $this->info('Roles disponibles:');
            foreach ($roles as $index => $roleName) {
                $this->line(" [$index] $roleName");
            }
            $selectedIndex = $this->ask('Selecciona el número del rol');
            $role = $roles[$selectedIndex] ?? null;
        }

        $data = compact('name', 'email', 'password', 'role');

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error("❌ $error");
            }
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);

        $this->info("✅ Usuario '{$user->name}' creado con el rol '{$role}'.");
    }
}
