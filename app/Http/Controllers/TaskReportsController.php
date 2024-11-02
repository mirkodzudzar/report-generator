<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\GenerateReportJob;
use App\Services\PdfReportService;

class TaskReportsController extends Controller
{
    public function create()
    {
        $users = User::query()->get();

        return view('tasks.reports.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::find($validated['user_id']);

        dispatch(new GenerateReportJob($user));

        return back()->with('success', __('File generated successfully and sent via email.'));
    }
}
