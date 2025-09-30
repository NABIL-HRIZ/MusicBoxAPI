<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Role;

class RegisteredUserController extends Controller
{
   

    /**
 * @OA\Post(
 *     path="/register",
 *     tags={"Authentication"},
 *     summary="Créer un nouvel utilisateur",
 *     description="Inscription d'un nouvel utilisateur dans l'application MusicApp.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","date_of_birth","gender"},
 *             @OA\Property(property="name", type="string", example="NABIL HRIZ"),
 *             @OA\Property(property="email", type="string", example="nabil@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *             @OA\Property(property="date_of_birth", type="string", format="date", example="2001-01-14"),
 *             @OA\Property(property="phone", type="string", example="06091153426", nullable=true),
 *             @OA\Property(property="gender", type="string", enum={"homme","femme"}, example="homme")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur créé avec succès"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation"
 *     )
 * )
 */



    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:homme,femme'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $userRole = Role::where('name', 'user')->first();
if($userRole) {
    $user->addRole($userRole);
}

        event(new Registered($user));

        

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success'=>true,
            'user' => $user,
            'token' => $token,
            'role'=>$userRole

        ], 200);
    }
}
