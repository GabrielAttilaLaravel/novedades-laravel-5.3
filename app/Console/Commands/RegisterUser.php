<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // preguntamos datos al usurio y lo almacenamos un variables
        $name = $this->ask('Por favor coloca tu nombre.');
        $email = $this->ask('Por favor coloca tu email.');
        $password = $this->secret('Por favor coloca tu clave.');

        User::create(compact('name', 'email', 'password'));

        $this->info("El usuario <$name> fue registrado con exito.");
    }
}
