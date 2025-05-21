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
    public function index()
    {
        return Gender::orderBy('type')->get();
    }

    public function show(Gender $gender)
    {
        return $gender;
    }

    public function store(StoreGenderRequest $request)
    {
        $gender = Gender::create($request->validated());

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'created');

        return response()->json(['message' => 'Gender created', 'data' => $gender], 201);
    }

    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        Gate::authorize('update', $gender);

        $gender->update($request->validated());

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'updated');

        return response()->json(['message' => 'Gender updated', 'data' => $gender]);
    }

    public function destroy(Gender $gender)
    {
        Gate::authorize('delete', $gender);

        NotifyMarketingUsersOfGenderChange::dispatch($gender, 'deleted');
        $gender->delete();

        return response()->json(['message' => 'Gender deleted']);
    }

    public function toggleDefault(Gender $gender)
    {
        Gate::authorize('toggleDefault', $gender);

        $gender->is_default = !$gender->is_default;
        $gender->save();

        NotifyModeratorsOfDefaultGender::dispatch($gender);

        return response()->json(['message' => 'Default status toggled', 'data' => $gender]);
    }
}
