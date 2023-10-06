<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class testDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'prueba de conexion a db externa ssh';

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
     * @return int
     */
    public function handle()
    {
        $users = DB::connection('mysql_cace')
        ->table('users')
        ->get();
        dd($users);
    }
}
