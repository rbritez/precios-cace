<?php

namespace App\Console\Commands;

use App\Event;
use Illuminate\Console\Command;

use function GuzzleHttp\Promise\all;

class updateStatusEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:updateStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'actualiza el estado de los evento segun la fecha de inicio y fin de los eventos';

    public $status;

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
        $now = now()->format('Y-m-d H:i:s');
        $events = Event::whereIn('status_id',['1','2'])->get();
        $bar = $this->output->createProgressBar(count($events));
        
        foreach ($events as $event) {
            $fechaInicioEvent = $event->event_init;
            $fechaFinEvent = $event->event_end;

            if($fechaInicioEvent < $now && $fechaFinEvent < $now){
                //ya empezo             // ya termino
                $this->status = 3;
            }elseif($fechaInicioEvent < $now && $fechaFinEvent > $now){
                //ya empezo              // continua o se deja activo
                $this->status = 2;
            }else{
                //todavia no empezo
                $this->status = 1;
            }
            
            $event->update(['status_id' => $this->status]);
            $bar->advance();
            
        }
        
        $bar->finish();
    }   
}