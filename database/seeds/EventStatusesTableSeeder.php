<?php

use App\EventStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sts = [
            ['name_event' => 'En espera'],    //1
            ['name_event' => 'En curso'],     //2
            ['name_event' => 'Finalizada'],   //3
            ['name_event' => 'Cancelada'],    //4
        ];

        foreach ($sts as $st) {
            EventStatus::create($st);
        }

    }
}