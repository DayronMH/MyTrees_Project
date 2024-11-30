<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UsersModel;
use Illuminate\Validation\ValidationException;
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
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/'
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

            $hashedPassword = Hash::make($request->password);

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
}