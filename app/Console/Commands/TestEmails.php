<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\HolidayPending;

class TestEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::select('email')->find(2);
        $userDestinatario = User::select('name', 'email')->find(4);

        $dataToSend = array(
            'day' => '2025-02-12',
            'name' => $userDestinatario->name,
            'email' => $userDestinatario->email,
        );
        Mail::to($user->email)->send(new HolidayPending($dataToSend));
    }
}
