<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleController extends Controller
{
    public function index()
    {
        return view('users.roles');
    }

    public function store()
    {
        $attr = request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Role::create($attr);

        return response(['success' => 'Role successfully created.'], 200);
    }

    public function update(Role $role)
    {
        if ($role->id == 1) {
            return response([
                'message' => 'oof..',
                'errors' => ['unauthorized' => ['You\'re not allowed to modify this role.']]
            ], 403);
        }

        $attr = request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $role->update($attr);


        return response(['success' => 'Role successfully updated.'], 200);
    }

    public function destroy(Role $role)
    {
        if ($role->id == 1) {
            return response([
                'message' => 'oof..',
                'errors' => ['unauthorized' => ['You\'re not allowed to modify this role.']]
            ], 403);
        }

        $role->delete();

        return response(['success' => 'Role successfully deleted.'], 200);
    }

    public function list()
    {
        return response(Role::all(), 200);
    }

    public function records()
    {
        $query = Role::orderBy(request('column'), request('order'));

        return JsonResource::collection($query->paginate(5));
    }
}
