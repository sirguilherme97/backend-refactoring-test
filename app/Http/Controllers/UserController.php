<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAllUsers(), 200);
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return $user
            ? response()->json($user, 200)
            : response()->json(['message' => 'User not found'], 404);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {

        $user = $this->userService->updateUser($id, $request->validated());
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);

    }

    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);
        return $deleted
            ? response()->json(['message' => 'User deleted'], 200)
            : response()->json(['message' => 'User not found'], 404);
    }
}

