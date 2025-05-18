<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class VacancyController extends Controller
{
    public function index(): JsonResponse
    {
        $vacancies = Vacancy::all();

        return response()->json([
            'data' => $vacancies
        ]);
    }

    public function show(Vacancy $vacancy): JsonResponse
    {
        return response()->json(['data' => $vacancy]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Vacancy::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'last_day' => 'required|date',
            'salary_id' => 'required|exists:salaries,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $vacancy = Vacancy::create([
            'title' => $validated['title'],
            'salary_id' => $validated['salary'],
            'category_id' => $validated['category'],
            'user_id' => auth()->id(),
            'company' => $validated['company'],
            'last_day' => $validated['last_day'],
            'description' => $validated['description'],
        ]);

        return response()->json(['data' => $vacancy], 201);
    }

    public function update(Request $request, Vacancy $vacancy): JsonResponse
    {
        Gate::authorize('update', $vacancy);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'company' => 'sometimes|required|string|max:255',
            'last_day' => 'sometimes|required|date',
            'salary_id' => 'sometimes|required|exists:salaries,id',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $vacancy->update($validated);

        return response()->json(['data' => $vacancy]);
    }

    public function destroy(Vacancy $vacancy): JsonResponse
    {
        Gate::authorize('delete', $vacancy);

        $vacancy->delete();

        return response()->json(['message' => 'Vacancy deleted successfully']);
    }
}
