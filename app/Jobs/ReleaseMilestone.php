<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ReleaseMilestone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('proposals')
			->select('messages.created_at','transactions.id','chat_rooms.id as chat_id')
			->join('projects','projects.id','=','proposals.project_id')
			->join('chat_rooms','projects.user_id','=','chat_rooms.owner_id')
			->join('messages','chat_rooms.owner_id','=','messages.sender_id')
			->join('transactions','transactions.project_id','=','projects.id')
			->where('finished','in progress')
			->where('messages.created_at','<',now()->subDays(7))
			->where('last',1)
			->groupBy('chat_rooms.id')
			->latest('messages.created_at')
			->update(['transactions.type'=>'released']);
    }
}
