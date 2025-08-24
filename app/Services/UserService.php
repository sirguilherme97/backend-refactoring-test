<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection<User>
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id)
    {
        return User::find($id);
    }

    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function createUser(array $data)
    {
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User|null
     */
    public function updateUser(int $id, array $data): ?User
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
