<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vacancies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vacancies.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        return view('vacancies.show', compact('vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
        Gate::authorize('update', $vacancy);
        return view('vacancies.edit', compact('vacancy'));
    }
}
