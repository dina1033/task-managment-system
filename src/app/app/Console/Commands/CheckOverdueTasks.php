<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Jobs\SendOverdueTaskNotificationJob;
use App\Models\Task;
use App\Enums\TaskStatus;


#[Signature('app:check-overdue-tasks')]
#[Description('Command description')]
class CheckOverdueTasks extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Task::query()
            ->where('status', '!=', TaskStatus::DONE)
            ->whereDate('due_date', '<=', now())
            ->each(function (Task $task) {

                SendOverdueTaskNotificationJob::dispatch($task);
    
            });
    
        return self::SUCCESS;
    }
}
