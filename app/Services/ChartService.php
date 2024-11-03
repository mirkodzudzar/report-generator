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

        $fileName = 'chart_' . uniqid() . '.png';
        $filePath = Storage::path('temp/reports/' . $fileName);

        Browsershot::html($html)
            ->windowSize(600, 400)
            ->waitUntilNetworkIdle()
            ->save($filePath);

        $base64 = base64_encode(file_get_contents($filePath));

        Storage::delete($filePath);

        return $base64;
    }
}
