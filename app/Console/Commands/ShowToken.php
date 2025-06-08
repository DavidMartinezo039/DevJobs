<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ShowToken extends Command
{
    protected $signature = 'user:show-token
                            {--email= : Email del usuario}
                            {--password= : Contraseña del usuario}
                            {--id= : ID del usuario ya autenticado}';

    protected $description = 'Muestra el token personal del usuario. Se puede usar con email/contraseña o con ID de usuario autenticado.';

    public function handle()
    {
        if ($this->option('id')) {
            $user = User::find($this->option('id'));
            if (! $user) {
                $this->error("Usuario con ID {$this->option('id')} no encontrado.");
                return 1;
            }
        } else {
            $email = $this->option('email');
            $password = $this->option('password');

            if (! $email || ! $password) {
                $this->error("Debe proporcionar email y contraseña si no se usa --id.");
                return 1;
            }

            $user = User::where('email', $email)->first();

            if (! $user) {
                $this->error("Usuario con email $email no encontrado.");
                return 1;
            }

            if (! Hash::check($password, $user->password)) {
                $this->error("Contraseña incorrecta.");
                return 1;
            }
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $this->line($token);

        return 0;
    }
}
