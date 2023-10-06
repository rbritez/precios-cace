<?php

namespace App\Console\Commands;

use App\Alteration;
use App\Event;
use App\megaOfertas;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyAlterations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mo:verify {--date= : filtro por fecha con formato Y-m-d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'se verifica si el producto scrapeado forma parte de mega ofertas para aletar sobre modificaciones de precios';

    public $megaOfertas ;

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
        
        $events = Event::whereIn('status_id',[1,2])->get();
        
        foreach ($events as $key => $event) {
            $inicio = $event->measurement->get(0)->revision_init;
            $fin = $event->measurement->get(0)->revision_end;
            $now = now()->format('Y-m-d H:i:s');
            if($inicio < $now && $fin > $now){
                $this->info('------------  Evento: '.$event->name .'  ------------');
                $queryAlterations = Alteration::whereEventId($event->id);
                if($date = $this->option('date')){
                    $start = Carbon::parse($date)->startOfDay();
                    $end = Carbon::parse($date)->endOfDay();
                }else{
                    $start = today()->startOfDay();
                    $end = today()->endOfDay();
                }
                $queryAlterations->whereBetween('created_at',[$start,$end]);
                $alterations = $queryAlterations->get();
                $barAlteration = $this->output->createProgressBar(count($alterations));
                foreach ($alterations as $key => $alteration) {
                    $this->verifyMO($alteration,$alteration->urlFormat);
                    $barAlteration->advance();
                }
                $barAlteration->finish();
            }
        }
    }

    public function verifyMO($alteration,$url){

        $exist = megaOfertas::whereLinkOferta($url)->first();

        if($exist){
            Notification::create(['alteration_id' => $alteration->id]);
        }else{
            Log::info('no se encontro la URL:'.$url .' en MegaOfertas');
        }
        
    }
}
