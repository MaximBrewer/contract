<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendChatMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:message {survey} {message}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send chat message.';

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
        // Вызов события, пока просто выбирая первого пользователя
        $user = \App\User::first();

        event(new \App\Events\MessagePushed($user));
    }
}
