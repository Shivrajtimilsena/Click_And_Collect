<?php

namespace App\Console\Commands;

use App\Mail\TraderApprovedMail;
use App\Models\TraderApplication;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessPendingTraderApprovals extends Command
{
    protected $signature = 'trader:process-pending-approvals';

    protected $description = 'Fix PENDING_SETUP trader passwords and send approval emails';

    public function handle(): int
    {
        $users = User::where('password', 'PENDING_SETUP')
            ->where('role', 'TRADER')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No pending trader approvals found.');

            return self::SUCCESS;
        }

        $count = 0;
        foreach ($users as $user) {
            $plainPassword = Str::random(12);

            $user->update(['password' => $plainPassword]);

            TraderApplication::where('email', $user->email)
                ->where('status', '!=', 'APPROVED')
                ->update(['status' => 'APPROVED', 'reviewed_at' => now()]);

            try {
                Mail::to($user->email)->send(new TraderApprovedMail($user, $plainPassword));
                $this->info("Email sent to {$user->email}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Failed to send approval email to {$user->email}: ".$e->getMessage());
                $this->error("Failed to send email to {$user->email}");
            }
        }

        $this->info("Processed {$count} pending trader approvals.");

        return self::SUCCESS;
    }
}
