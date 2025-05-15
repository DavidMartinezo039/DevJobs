<?php

namespace App\Jobs;

use App\Mail\MarketingNewsletter;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMarketingEmails implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $users = User::where('wants_marketing', true)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new MarketingNewsletter($user));
        }
    }
}
