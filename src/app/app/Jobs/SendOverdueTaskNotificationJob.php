<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\OverdueTaskNotification;

class SendOverdueTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Task $task
    ) {}

    public function handle(): void
    {
        $this->task->loadMissing('project.user');

        $this->task->project->user->notify(
            new OverdueTaskNotification($this->task)
        );
    
        $this->task->update([
            'overdue_notification_sent_at' => now(),
        ]);
    }
}