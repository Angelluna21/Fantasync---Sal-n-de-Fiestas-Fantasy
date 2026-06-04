<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginLog;

class LoginLogController extends Controller
{
    public function index()
    {
        $logs = LoginLog::with('user')->orderBy('logged_in_at', 'desc')->paginate(20);
        return view('logs.index', compact('logs'));
    }
}
