<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class VacancyController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/vacancies",
     *     summary="Obtener todas las vacantes",
     *     tags={"Vacancies"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vacantes obtenida correctamente"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $vacancies = Vacancy::all();

        return response()->json([
            'data' => $vacancies
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/vacancies/{vacancy}",
     *     summary="Obtener una vacante por ID",
     *     tags={"Vacancies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la vacante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vacante encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vacante no encontrada"
     *     )
     * )
     */
    public function show(Vacancy $vacancy): JsonResponse
    {
        return response()->json(['data' => $vacancy]);
    }

    /**
     * @OA\Post(
     *     path="/api/vacancies",
     *     summary="Crear una nueva vacante",
     *     tags={"Vacancies"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "company", "last_day", "salary_id", "category_id"},
     *             @OA\Property(property="title", type="string", example="Desarrollador PHP"),
     *             @OA\Property(property="description", type="string", example="Se busca desarrollador con experiencia en Laravel."),
     *             @OA\Property(property="company", type="string", example="TechCorp"),
     *             @OA\Property(property="last_day", type="string", format="date", example="2025-06-30"),
     *             @OA\Property(property="salary_id", type="integer", example=1),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vacante creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado para crear vacantes"
     *     )
     * )
     */
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
            'salary_id' => $validated['salary_id'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'company' => $validated['company'],
            'last_day' => $validated['last_day'],
            'description' => $validated['description'],
        ]);

        return response()->json(['data' => $vacancy], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/vacancies/{vacancy}",
     *     summary="Actualizar una vacante existente",
     *     tags={"Vacancies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la vacante a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Frontend Developer"),
     *             @OA\Property(property="description", type="string", example="Experiencia con Vue.js o React."),
     *             @OA\Property(property="company", type="string", example="WebDev Inc."),
     *             @OA\Property(property="last_day", type="string", format="date", example="2025-07-15"),
     *             @OA\Property(property="salary_id", type="integer", example=2),
     *             @OA\Property(property="category_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vacante actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado para actualizar esta vacante"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/vacancies/{vacancy}",
     *     summary="Eliminar una vacante",
     *     tags={"Vacancies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="vacancy",
     *         in="path",
     *         required=true,
     *         description="ID de la vacante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vacante eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado para eliminar esta vacante"
     *     )
     * )
     */
    public function destroy(Vacancy $vacancy): JsonResponse
    {
        Gate::authorize('delete', $vacancy);

        $vacancy->delete();

        return response()->json(['message' => 'Vacancy deleted successfully']);
    }
}
