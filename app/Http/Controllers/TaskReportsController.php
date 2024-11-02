<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TaskReportsController extends Controller
{
    public function create()
    {
        $users = User::query()->get();

        return view('tasks.reports.create', compact('users'));
    }
}
