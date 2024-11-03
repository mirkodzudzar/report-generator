<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class ChartService
{
    public function generateChart(array $statistics): string
    {
        $completedPercentage = ($statistics['completed'] / $statistics['total']) * 100;
        $incompletePercentage = ($statistics['incomplete'] / $statistics['total']) * 100;
        $completedLabel = __("Completed :completed%", ['completed' => $completedPercentage]);
        $incompleteLabel = __("Incomplete :incomplete%", ['incomplete' => $incompletePercentage]);

        $data = [
            'labels' => [$completedLabel, $incompleteLabel],
            'data' => [$completedPercentage, $incompletePercentage]
        ];

        $html = View::make('tasks.reports.chart', $data)->render();

        $filename = 'chart_' . uniqid() . '.png';
        $tempPath = Storage::path('temp/' . $filename);

        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        Browsershot::html($html)
            ->windowSize(600, 400)
            ->waitUntilNetworkIdle()
            ->save($tempPath);

        $base64 = base64_encode(file_get_contents($tempPath));

        Storage::delete($tempPath);

        return $base64;
    }
}
