<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PDO;
use Exception;

class usersModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'country'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Creates a new user
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string $phone
     * @param string $address
     * @param string $country
     * @return bool
     */
    public function createUser(string $name, string $email, string $password, string $role, string $phone, string $address, string $country): bool
    {
        try {
            return (bool) self::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'phone' => $phone,
                'address' => $address,
                'country' => $country
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Creates a new friend user in the database.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $phone
     * @param string $address
     * @param string $country
     * @return bool
     */
    public function createFriendUser(string $name, string $email, string $password, string $phone, string $address, string $country): bool
    {
        try {
            return (bool) self::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => $phone,
                'address' => $address,
                'country' => $country
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Handles user login by email
     *
     * @param string $email
     * @return array|false
     */
    public function handleLogin(string $email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Counts the number of friends in the database
     *
     * @return int
     */
    public function countFriends(): int
    {
        return self::where('role', 'friend')->count();
    }

    /**
     * Retrieves all users
     *
     * @return array
     */
    public function getUsers(): array
    {
        return self::all()->toArray();
    }

    /**
     * Retrieves all friends
     *
     * @return array
     */
    public function getFriends()
    {
        return self::where('role', 'friend')->get()->toArray();
    }

    /**
     * Retrieves all admins
     *
     * @return array
     */
    public function getAdmins()
    {
        return self::where('role', 'admin')->get()->toArray();
    }

    /**
     * Retrieves a user by their ID
     *
     * @param int $id
     * @return array|null
     */
    public function getUserById($id): ?array
    {
        $user = self::find($id);
        return $user ? $user->toArray() : null;
    }

    /**
     * Creates the first admin user if none exists
     *
     * @param string $role
     * @return bool
     */
    public function createFirstAdmin($role): bool 
    {
        // Check if admin exists
        $adminExists = self::where('role', 'admin')->exists();

        if (!$adminExists && $role === 'admin') {
            return (bool) self::create([
                'name' => "Admin",
                'email' => "Admin@gmail.com",
                'password' => Hash::make("123"),
                'role' => $role,
                'phone' => "123",
                'address' => "123",
                'country' => "123"
            ]);
        }

        return false;
    }
}