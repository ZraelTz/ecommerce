<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Mail; 
use Log;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
       // $this->middleware('auth:api', ['except' => ['login', 'register','forgotPassword', 'resetPassword']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        Log::info("Verify Email Address API");
        $user = User::find($request->route('id'));
        Log::info(print_r($user, true));
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->markEmailAsVerified()) {
            // event(new Verified($user));
        }
        // $this->redirectPath()
        // return redirect()->route('login')->with('verified', true);
    }
    public function resetPassword(Request $request)
    {   try {
            Log::info("Reset Password API");
            $id = ($request->route('id')?$request->route('id'):$request->id);
            $hash = ($request->route('hash')?$request->route('hash'):$request->hash);
            $user = User::find($id);
            Log::info(print_r($user, true));
             if (empty($user)) {
                return response()->json(['error' => "User not exist."], 401);
                // throw new AuthorizationException;
            }
            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                return response()->json(['error' => "This Action is Unauthorized."],401);
                // throw new AuthorizationException;
            }
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|confirmed|min:6',
            ], [
                'password.required' => 'Text field cannot be empty, enter your password',
                'password.confirmed' => 'The passwords do not match',
                'password.min' => 'Password must contain six or more characters',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $user->password = bcrypt($request->password);
            if($user->save()) {
                Log::info("Reset Password API End");
                return response()->json(['message' => 'Password changed successfully' ], 201);
            } else {
                 Log::info("Reset Password API Error: Executing request");
                return response()->json(['error' => 'Executing request.'], 400);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        // $this->redirectPath()
        // return redirect()->route('login')->with('verified', true);
    }
    public function login(Request $request){
        Log::info("Login API");
        Log::info(print_r($request->all(), true));

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if(!User::where('email', $request->email)->exists()) {
            return response()->json(['errors' =>  [ 'email' => 'Enter a valid email address']], 422);
        }
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['errors' =>  [ 'password' => 'Password is incorrect']], 401);
        }
        Log::info("Login End With Token ". $token);
        return $this->createNewToken($token);
    }
    public function forgotPassword(Request $request){
        Log::info("forgotPassword API");
        Log::info(print_r($request->all(), true));
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::where('email', $request->email)->first();
        if(!empty($user)) {
            /*$url = URL::temporarySignedRoute(
            'forgotpassword',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 1440)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );*/
            $url = \config('frontend.url')."auth/reset-password?id=".$user->getKey()."&hash=".sha1($user->getEmailForVerification());
            Log::info("forgotPassword url ". $url);
            Mail::send('emails.forgotPassword', ['url' => $url, 'user' => $user], function($message) use($user){
                  $message->to($user->email);
                  $message->subject('Reset Your Password');
            });
            Log::info("forgotPassword End ");
            return response()->json([
                'message' => 'User successfully send forgot password email'
            ], 201);
        } else {
            return response()->json(['errors' =>  [ 'email' => 'Email address does not exist in our system']], 422);
        }
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        Log::info("Register API");
        Log::info(print_r($request->all(), true));
        $request->email = strtolower($request->email);
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:13|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'profile' => 'string|max:255',
        ], [
            'username.required' => 'Text field cannot be empty, enter your username',
            'email.required' => 'Text field cannot be empty, enter your email address',
            'email.email' => 'Enter a valid email address Ex. name@example.com',
            'email.unique' => 'Email address already exists',
            'phone.required' => 'Text field cannot be empty, enter your phone',
            'phone.unique' => 'Phone number already exists',
            'password.required' => 'Text field cannot be empty, enter your password',
            'password.confirmed' => 'The passwords do not match',
            'password.min' => 'Password must contain six or more characters',
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        Log::info(print_r($user, true));
         
        $user->markEmailAsVerified();
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Log::info("logout user API");
        auth('api')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        Log::info("Refresh Token API");
        return $this->createNewToken(auth('api')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        Log::info("Get User Profile API");
        return response()->json(auth('api')->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        Log::info("Generate New Token");
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
          //  'expires_in' => auth()->factory()->getTTL() * 60,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }
    public function checkEmailExists(Request $request) {
        Log::info("Check if Email Exists endpoint");
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if(User::where('email', $request->email)->exists()) {
            return response()->json(['message' =>  'Email address has been registered'], 422);
        } else {
            return response()->json(['message' => 'Email address has not been registered'], 201);
        }
   }
}
