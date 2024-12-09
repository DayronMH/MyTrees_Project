<?php 

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UsersModel;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;

class UsersController {
    public function addNewFriendUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:100',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).{8,}$/'
                ],
                'phone' => 'required|string|min:8|max:15',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:100'
            ], [
                'email.unique' => 'El correo electrónico ya está registrado',
                'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número',
                'phone.min' => 'El teléfono debe tener al menos 8 caracteres',
                'name.min' => 'El nombre debe tener al menos 2 caracteres'
            ]);

            $validator->validate();

            $hashedPassword = $request->password;

            $userCreated = UsersModel::createFriendUser(
                $request->name,
                $request->email,
                $hashedPassword,
                $request->phone,
                $request->address,
                $request->country
            );

            if (!$userCreated) {
                return response()->json([
                    'error' => 'No se pudo crear el usuario'
                ], 500);
            }

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'name' => $request->name,
                    'email' => $request->email
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error inesperado',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function createUser(Request $request)
    {
        // Log para verificar la solicitud de entrada
        Log::info('Creación de usuario solicitada', ['request_data' => $request->all()]);

        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'role' => 'required|in:admin,operator',
        ]);

        // Log para registrar si la validación pasó
        Log::info('Datos validados correctamente para crear el usuario.', ['email' => $request->email]);

        $hashedPassword = Hash::make($request->password);


        // Intentar crear el usuario
        try {
            // Llamar al modelo para crear el usuario
            $userCreated = UsersModel::createUser(
                $request->name,
                $request->email,
                $hashedPassword,
                $request->phone,
                $request->address,
                $request->country,
                $request->role
            );

            // Log para verificar si el usuario fue creado correctamente
            if ($userCreated) {
                Log::info('Usuario creado con éxito', ['user_email' => $request->email,
            'password' => $request->password]);
                return response()->json(['message' => 'Usuario creado con éxito.'], 201);
            } else {
                Log::error('Error al crear el usuario', ['user_email' => $request->email]);
                return response()->json(['message' => 'Error al crear el usuario.'], 500);
            }
        } catch (\Exception $e) {
            // Log para capturar cualquier excepción que ocurra
            Log::error('Excepción al crear el usuario', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return response()->json(['message' => 'Error interno.'], 500);
        }
    }
    public function friendsCount()
    {
        $friendsCount = UsersModel::countFriends();    
        return response()->json([
            'friends' => $friendsCount
        ]);
    }

    public function authUser(Request $request)
{
    try {
        $user = UsersModel::getUserByEmail($request->email);

        if (!$user) {
            return response()->json([
                $error = 'error' => 'Credenciales inválidas',
            ], 401);
        }

        // Verificar si la contraseña proporcionada coincide con el hash almacenado
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Contraseña incorrecta',
            ], 401);
        }
        $token = bin2hex(random_bytes(32));

        // Log de login exitoso
        Log::info('Login exitoso', [
            'email' => $user->email,
            'role' => $user->role,
            'name'=>$user->name,
            'timestamp' => now(),
        ]);

        $redirect = $this->getRedirectByRole($user->role);

        return response()->json([
            'success' => true,
            'token' => $token,
            'redirect' => $redirect,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
            ],
            'server_timestamp' => now(),
        ]);
    } catch (Exception $e) {
        Log::error('Error en proceso de login', [
            'email' => $request->email,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => now(),
        ]);

        return response()->json([
            'error' => 'Error en el servidor',
            'debug_info' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}

private function getRedirectByRole($role)
{
    $redirects = [
        'admin' => '/adminPanel',
        'friend' => '/friendPanel',
        'operator' => '/operatorPanel',
    ];

    return $redirects[$role] ?? $redirects['default'];
}

public function getFriends()
{
    try {
        $friends = UsersModel::getFriends();

        // Si no hay amigos, devolvemos un mensaje adecuado
        if ($friends->isEmpty()) {
            return response()->json(['message' => 'No se encontraron amigos.'], 404);
        }

        // Si hay amigos, los retornamos
        return response()->json($friends, 200);
    } catch (Exception $e) {
        // En caso de error, devolvemos un mensaje de error
        return response()->json(['message' => 'Error al obtener amigos.', 'error' => $e->getMessage()], 500);
    }
}

}