<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function store(UserRequest $request)
    {
        User::create($request->merge([
            'email_verified_at' => NOW(),
            'password' => bcrypt($request->get('password'))
        ])->all());

        return response(["success" => "User successfully created."], 200);
    }

    public function update(UserRequest $request, User $user)
    {
        if ($user->role_id == 1) {
            return response([
                'message' => 'oof..',
                'errors' => ['unauthorized' => ['You\'re not allowed to modify this user.']]
            ], 403);
        }

        $user->update(
            $request->merge(['password' => bcrypt($request->get('password'))])
                ->except([$request->get('password') ? '' : 'password', 'password_confirmation'])
        );

        return response(['success' => 'User successfully updated.'], 200);
    }

    public function destroy(User $user)
    {
        if ($user->role_id == 1) {
            return response([
                'message' => 'oof..',
                'errors' => ['unauthorized' => ['You\'re not allowed to modify this user.']]
            ], 403);
        }

        $user->delete();

        return response(['success' => 'User successfully deleted.'], 200);
    }

    public function records()
    {
        $query = User::with('role')->orderBy(request('column'), request('order'));

        return JsonResource::collection($query->paginate(5));
    }
}
