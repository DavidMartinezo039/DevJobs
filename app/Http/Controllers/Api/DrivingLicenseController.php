<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DrivingLicenseRequest;
use App\Models\DrivingLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Events\DrivingLicenseEditRequested;

/**
 * @OA\Tag(
 *     name="Driving Licenses",
 *     description="Endpoints for managing driving licenses"
 * )
 */
class DrivingLicenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/driving-licenses",
     *     summary="List driving licenses",
     *     tags={"Driving Licenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of driving licenses",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DrivingLicense")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $drivingLicenses = DrivingLicense::orderBy('category')->paginate(10);

        return response()->json($drivingLicenses);
    }

    /**
     * @OA\Get(
     *     path="/api/driving-licenses/{id}",
     *     summary="Get a driving license",
     *     tags={"Driving Licenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Driving license ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Driving license data",
     *         @OA\JsonContent(ref="#/components/schemas/DrivingLicense")
     *     )
     * )
     */
    public function show(DrivingLicense $drivingLicense)
    {
        return response()->json($drivingLicense);
    }

    /**
     * @OA\Post(
     *     path="/api/driving-licenses",
     *     summary="Create a driving license",
     *     tags={"Driving Licenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DrivingLicenseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Driving license created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/DrivingLicense")
     *         )
     *     )
     * )
     */
    public function store(DrivingLicenseRequest $request)
    {
        $validated = $request->validated();
        $validated['only_god'] = auth()->user()->hasRole('god');

        $drivingLicense = DrivingLicense::create($validated);

        return response()->json([
            'message' => __('Driving license created successfully'),
            'data' => $drivingLicense,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/driving-licenses/{id}",
     *     summary="Update a driving license",
     *     tags={"Driving Licenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Driving license ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DrivingLicenseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Driving license updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/DrivingLicense")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Edit request sent"
     *     )
     * )
     */
    public function update(DrivingLicenseRequest $request, DrivingLicense $drivingLicense)
    {
        if (!Gate::allows('update', $drivingLicense)) {
            DrivingLicenseEditRequested::dispatch(auth()->user(), $drivingLicense);

            return response()->json([
                'message' => __('Edit request sent. Waiting for god approval.')
            ], 403);
        }

        $validated = $request->validated();
        $validated['only_god'] = auth()->user()->hasRole('god');

        $drivingLicense->update($validated);

        return response()->json([
            'message' => __('Driving license updated successfully'),
            'data' => $drivingLicense,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/driving-licenses/{id}",
     *     summary="Delete a driving license",
     *     tags={"Driving Licenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Driving license ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Driving license deleted",
     *         @OA\JsonContent(@OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Delete request sent"
     *     )
     * )
     */
    public function destroy(DrivingLicense $drivingLicense)
    {
        if (!Gate::allows('delete', $drivingLicense)) {
            DrivingLicenseEditRequested::dispatch(auth()->user(), $drivingLicense);

            return response()->json([
                'message' => __('Delete request sent. Waiting for god approval.')
            ], 403);
        }

        $drivingLicense->delete();

        return response()->json([
            'message' => __('Driving license deleted successfully.'),
        ]);
    }
}
