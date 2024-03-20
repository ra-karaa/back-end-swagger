<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'refresh']]);
    }

    /**
     * Login
     * @OA\Patch (
     *     path="/api/auth/login",
     *     tags={"Login"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"email",
     *                     "password":"password"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="password", type="string", example="password"),
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'type' => 'bearer',
        ]);
    }

    public function register(Request $request){

        $check_user_name_exsist = User::where('email', $request->email)->first();

        if(!$check_user_name_exsist){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Username Already Use'
        ], 420);
    }

    public function refresh(Request $request){
        $check_user = User::where('remember_token', $request->token)->first();

        if($check_user){
            $token = JWTAuth::fromUser($check_user);
            $check_user->remember_token = $token;
            $check_user->save();

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'type' => 'bearer',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

}
