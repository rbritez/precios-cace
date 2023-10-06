<?php

namespace App\Console\Commands;

use App\Event;
use App\Shop;
use Illuminate\Console\Command;

class addShopsToEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addShopsToEvent';

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
     * @return int
     */
    public function handle()
    {
     $shops = Shop::where('platform','!=',null)->where('platform','!=','')->get();
     $event = Event::find(1);
     //foreach($shops->pluck('id') as $shop_id){
        $event->shops()->sync($shops->pluck('id'));
     //}
    }
}
