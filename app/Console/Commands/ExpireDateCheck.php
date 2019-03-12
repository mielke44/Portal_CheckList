<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Check;
use App\Admin;
use App\Task;
use App\Events\CheckUpdateEvent;
use DateTime;

class ExpireDateCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks all the expired checks based on the now Carbon/DateTime instance';

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
        date_default_timezone_set('America/Sao_Paulo');
        $count=0;
        foreach(Check::where('status',0)->get() as $c){
            $receiver=array('admin'=>[]);
            if(strlen($c->resp)==6){
                foreach(Admin::where('group',$c->resp[5])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else if(strlen($c->resp)==7){
                foreach(Admin::where('group',$c->resp[5].$c->resp[6])->get() as $adm){
                    array_push($receiver['admin'],$adm->id);
                }
            }else{
                array_push($receiver['admin'],Admin::findOrFail($c->resp)['id']);
            }

            $t = Task::findOrFail($c->task_id);
            $now = new DateTime(date('m/d/Y h:i:s a', time()));
            $created_at = new DateTime($c->created_at);
            $check_limit = new DateTime($c->limit);

            if($now>=$check_limit) {
                event(new CheckUpdateEvent($c,$t->name, -1, $receiver));
                $count++;
            }else event(new CheckUpdateEvent($c,$t->name, 5, $receiver));
            
        }
        $this->info('All Checks monitored! '.$count." Checks expired from ".Check::where('status',0)->count()." in total!");
    }
}
