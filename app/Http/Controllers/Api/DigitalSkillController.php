<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DigitalSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class DigitalSkillController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/digital-skills",
     *     summary="Obtener todas las habilidades digitales",
     *     tags={"DigitalSkills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de habilidades digitales obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function index()
    {
        $digitalSkills = DigitalSkill::orderBy('name')->get();
        return response()->json($digitalSkills);
    }

    /**
     * @OA\Post(
     *     path="/api/digital-skills",
     *     summary="Crear una nueva habilidad digital",
     *     tags={"DigitalSkills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Machine Learning")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Habilidad digital creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:digital_skills,name',
        ]);

        $skill = DigitalSkill::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Digital skill created successfully',
            'data' => $skill,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/digital-skills/{id}",
     *     summary="Obtener una habilidad digital por ID",
     *     tags={"DigitalSkills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad digital",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Habilidad digital encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Habilidad digital no encontrada"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function show(DigitalSkill $digitalSkill)
    {
        return response()->json($digitalSkill);
    }

    /**
     * @OA\Put(
     *     path="/api/digital-skills/{id}",
     *     summary="Actualizar una habilidad digital",
     *     tags={"DigitalSkills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad digital",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Ciberseguridad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Habilidad digital actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function update(Request $request, DigitalSkill $digitalSkill)
    {
        Gate::authorize('update', $digitalSkill);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('digital_skills')->ignore($digitalSkill->id),
            ],
        ]);

        $digitalSkill->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Digital skill updated successfully',
            'data' => $digitalSkill,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/digital-skills/{id}",
     *     summary="Eliminar una habilidad digital",
     *     tags={"DigitalSkills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la habilidad digital",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Habilidad digital eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function destroy(DigitalSkill $digitalSkill)
    {
        Gate::authorize('delete', $digitalSkill);

        $digitalSkill->delete();

        return response()->json([
            'message' => 'Digital skill deleted successfully',
        ]);
    }
}
