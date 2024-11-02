<?php

namespace App\Services;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\AllTasksCompleted;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReportNotification;
use App\Notifications\CongratulationNotification;

class PdfReportService
{
    public function generate(User $user): void
    {
        $tasks = $this->getTasksFor($user);
        $statistics = $this->getStatistics($tasks);

        $pdf = Pdf::loadView('tasks.reports.pdf', [
            'user' => $user,
            'statistics' => $statistics,
        ]);

        $pdfFileName = "tasks-report-{$user->id}.pdf";
        $pdfPath = "temp/reports/{$pdfFileName}";

        Storage::put($pdfPath, $pdf->output());

        $user->notify(new ReportNotification($pdfPath, $pdfFileName));

        Storage::delete($pdfPath);

        if ($statistics['total'] > 0 && $statistics['total'] === $statistics['completed']) {
            event(new AllTasksCompleted($user));
        }
    }

    private function getTasksFor(User $user): Collection
    {
        $response = Http::get(config('app.task_api'));
        $tasks = $response->json();

        return collect($tasks)->filter(fn($task) => $task['userId'] === $user->id);
    }

    private function getStatistics(Collection $tasks): array
    {
        $total = $tasks->count();
        $completed = $tasks->where('completed', true)->count();
        $incomplete = $total - $completed;
        $percentage = $total > 0 ? ($completed / $total) * 100 : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'incomplete' => $incomplete,
            'percentage' => round($percentage, 2),
        ];
    }
}
