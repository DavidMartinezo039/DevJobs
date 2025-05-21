<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard.index', compact('user'));
    }

    public function backup()
    {
        Artisan::call('backup:database');
        return back()->with('success', 'Backup de base de datos generado.');
    }
}
