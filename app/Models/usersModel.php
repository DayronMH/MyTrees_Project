<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UsersModel extends Model
{

    public $timestamps = false;
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'address', 'country'];
    public static function createFriendUser(string $name, string $email, string $password, string $phone, string $address, string $country): bool
    {
        try {
            $user = self::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'friend',
                'phone' => $phone,
                'address' => $address,
                'country' => $country
            ]);

            if ($user) {
                self::logCreatedUser($user);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            self::logCreationError($e);
            return false;
        }
    }

    private static function logCreatedUser($user)
    {
        Log::info('New user created:', $user->toArray());
    }

    private static function logCreationError(Exception $e)
    {
        Log::error('Error creating user:', ['message' => $e->getMessage()]);
    }
    public static function getUserByEmail($email)
    {
        return self::select('email', 'password', 'role')
            ->where('email', $email)
            ->first();
    }
}