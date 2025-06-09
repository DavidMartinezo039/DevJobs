<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateUserHistoryPdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserHistoryController extends Controller
{
    public function generate()
    {
        GenerateUserHistoryPdf::dispatch(Auth::id());

        return redirect()->back()->with('message', __('History in progress'));
    }

    public function download()
    {
        $files = collect(Storage::disk('public')->files('reports'))
            ->filter(fn($f) => Str::contains($f, 'user-history-') && Str::endsWith($f, '.pdf'))
            ->sortDesc();

        if ($files->isEmpty()) {
            return redirect()->back()->with('error', __('No history files found'));
        }

        $latest = $files->first();

        return response()->download(storage_path("app/public/{$latest}"));
    }
}
