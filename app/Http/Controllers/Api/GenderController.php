<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Jobs\NotifyMarketingUsersOfGenderChange;
use App\Jobs\NotifyModeratorsOfDefaultGender;

class GenderController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/genders",
     *     summary="Obtener todos los géneros",
     *     description="Requiere autenticación mediante token. Accesible solo para roles 'moderator' o 'god'.",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de géneros obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado. Se requiere el rol 'moderator' o 'god'."
     *     )
     * )
     */
    public function index()
    {
        return Gender::orderBy('type')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/genders/{id}",
     *     summary="Obtener un género por ID",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Género no encontrado"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado: solo para roles 'moderator' o 'god'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function show(Gender $gender)
    {
        return $gender;
    }

    /**
     * @OA\Post(
     *     path="/api/genders",
     *     summary="Crear un nuevo género",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type"},
     *             @OA\Property(property="type", type="string", example="Binario"),
     *             @OA\Property(property="is_default", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Género creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado: solo para roles 'moderator' o 'god'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function store(StoreGenderRequest $request)
    {
        $gender = Gender::create($request->validated());

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'created');

        return response()->json(['message' => 'Gender created', 'data' => $gender], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/genders/{id}",
     *     summary="Actualizar un género",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type"},
     *             @OA\Property(property="type", type="string", example="Trinario"),
     *             @OA\Property(property="is_default", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado: solo para roles 'moderator' o 'god'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        Gate::authorize('update', $gender);

        $gender->update($request->validated());

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'updated');

        return response()->json(['message' => 'Gender updated', 'data' => $gender]);
    }


    /**
     * @OA\Delete(
     *     path="/api/genders/{id}",
     *     summary="Eliminar un género",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado: solo para roles 'moderator' o 'god'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function destroy(Gender $gender)
    {
        Gate::authorize('delete', $gender);

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'deleted');
        $gender->delete();

        return response()->json(['message' => 'Gender deleted']);
    }

    /**
     * @OA\Post(
     *     path="/api/genders/{id}/toggle-default",
     *     summary="Alternar el estado por defecto del género",
     *     tags={"Genders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado predeterminado actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No autorizado: solo para rol 'god'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function toggleDefault(Gender $gender)
    {
        Gate::authorize('toggleDefault', $gender);

        $gender->is_default = !$gender->is_default;
        $gender->save();

        NotifyModeratorsOfDefaultGender::dispatch($gender);

        return response()->json(['message' => 'Default status toggled', 'data' => $gender]);
    }
}
