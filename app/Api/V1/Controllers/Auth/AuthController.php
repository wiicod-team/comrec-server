<?php

namespace App\Api\V1\Controllers\Auth;

use App\Device;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Verification;
use Auth;
use Browser;
use Carbon\Carbon;
use function Functional\true;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;
use Mail;
use Setting;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{

    /**
     * @group Auth
     *
     * Get authenticated user details and auth credentials.
     *
     * @return JSON
     */
    public function getAuthenticatedUser(){
        if (Auth::check()) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->success(compact('user', 'token'));
        } else {
            return response()->error('unauthorized', 401);
        }
    }


    /**
     *
     * @group Auth
     * @bodyParam phone string required phone number of the user
     * @bodyParam email string required email of the user
     * @bodyParam password string password of the user
     *
     * Authenticate user.
     *
     * @param Instance Request instance
     *
     * @return JSON user details and auth credentials
     */
    public function signin(Request $request){

        $credentials = $request->only('login', 'password');
        $email=isset($credentials["login"])?$credentials["login"]:null;
        if($email==null)
            return response()->json(['error' => 'missing login'], 403);
        $validator = Validator::make(['email'=>$email], ['email'=>'email']);
        if($validator->fails()){
            $user= User::where("phone","=",$email)->first();
        }else{
            $user = User::where('email', '=', $email)->first();
        }


        if(!isset($user))
            return response()->error(trans('auth.failed'), 401);

        /* if (isset($user->email_verified) && $user->email_verified == 0) {
             return response()->error('Email Unverified');
         }*/


        $credentials['email'] = $user->email;
        unset($credentials['login']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->error(trans('auth.failed'), 401);
            }
        } catch (\JWTException $e) {
            return response()->error('Could not create token', 500);
        }


        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        /*$abilities = $this->getRolesAbilities();
        $userRole = [];

        foreach ($user->roles as $role) {
            $userRole [] = $role->name;
        }*/

        return response()->success(compact('user', 'token'));
//        return response()->success(compact('user', 'token','abilities', 'userRole'));
    }

    /**
     *
     * @group Auth
     * @bodyParam phone string required phone number of the user
     * @bodyParam email string required email of the user
     * @bodyParam password string password of the user
     * @bodyParam device_tokens array the user devices tokens
     * @bodyParam settings json key value array user settings
     *
     * Create new user.
     *
     * @param Instance Request instance
     *
     * @return JsonResponse user details and auth credentials
     */
    public function signup(Request $request)
    {

        $rule = [
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:5|confirmed',
            'settings' => 'array',
            'device_tokens' => 'array'
        ];
        if($request->phone){
            $rule['phone'] = 'min:9|max:255|unique:users,phone';
        }


        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return Response::json($validator->errors(), 422);

        }else{
            $verificationCode = Str::random(40);
            $user = new User();
            $user->phone = $request->phone;
            $user->email = trim(strtolower($request->email));
            $user->settings = isset($request->settings) ? $request->settings : [];
            $user->alternative_phone = isset($request->alternative_phone) ? $request->alternative_phone : null;
            $user->device_tokens = isset($request->device_tokens) ? $request->device_tokens : [];
            $user->password = $request->password;
            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->success(compact('user', 'token'));
        }


    }

    public function updateMe(Request $request){
        $rule = [
        ];

        if( $request->email !=null ){
            $rule['email'] = 'required|email|unique:users,email';
        }
        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return Response::json($validator->errors(), 422);
        }else{
            $user = Auth::user();

            if(isset($request->settings)){
                foreach ($request->settings as  $key=>$val){
                    Setting::set($key,$val, $user->id);
                }
                Setting::save($user->id);
            }

            $user->update($request->all());
            return response()->success(compact('user'));
        }
    }

    public function refresToken(){
        $token = JWTAuth::getToken();

        if (! $token) {
            throw new BadRequestHttpException('Token not provided');
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }

        //$user = Auth::user();
        return response()->success(compact('token'));
    }


    /**
     * @group Auth
     * @bodyParam email string required email of the user
     * @bodyParam google_oauth_id string id of google authentication
     * @bodyParam invite_by int id of the user who invite this user
     */
    public function oauth_login(Request $request){
        $rule = [
            'first_name'       => 'min:3|max:255',
            'last_name'       => 'min:3|max:255',
            'email'      => 'required|email',
            'google_oauth_id'   => 'required|min:3|max:255',
        ];
        $isRegister= false;
        if($request->phone){
            $rule['phone'] = 'min:9|max:255';
        }


        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return Response::json(['errors'=>$validator->errors()], 422);

        }else{
            $user = User::where("email","=",$request->email)->first();
            if(!$user){
                $isRegister=true;
                $validator = Validator::make($request->all(), ['phone'=>'required']);
                if($validator->fails()){
                    return Response::json(['is_register'=>true,'errors'=>$validator->errors()], 422);
                }

                $user = new User();
//                $user->first_name = trim($request->first_name);
//                $user->last_name = trim($request->last_name);
                $user->phone = $request->phone;
                $user->phone = $request->phone;
                $user->email = trim(strtolower($request->email));
                $user->settings = [];
                $user->email_activated = true;
                $user->password = '';
                $user->save();

            }

            if($user->google_oauth_id){
                if($user->google_oauth_id!=$request->google_oauth_id)
                    return response()->error(trans('auth.failed'), 401);
            }else{
                $user->google_oauth_id=$request->google_oauth_id;
                $user->save();
            }

            /*if($request->provider_name=='google'){
                if($user->google_oauth_id){
                    if($user->google_oauth_id!=$request->oauth_id)
                        return response()->error(trans('auth.failed'), 401);
                }else{
                    $user->google_oauth_id=$request->oauth_id;
                    $user->save();
                }
            }else if($request->provider_name=='facebook'){
                if($user->facebook_oauth_id){
                    if($user->facebook_oauth_id!=$request->oauth_id)
                        return response()->error(trans('auth.failed'), 401);
                }else{
                    $user->facebook_oauth_id=$request->oauth_id;
                    $user->save();
                }

            }else{
                return response()->error(trans('auth.failed'), 401);
            }*/
            $user = User::find($user->id);



            $token = JWTAuth::fromUser($user);

           /* $abilities = $this->getRolesAbilities();
            $userRole = [];

            foreach ($user->roles as $role) {
                $userRole [] = $role->name;
            }*/

            return response()->success(compact('user', 'token','isRegister'));
        }


    }


    

    /**
     * Send verification code to user phone number.
     *
     * @param Instance Request instance
     *
     * @return JsonResponse user details and auth credentials
     */
    public function sendPhoneVerificationCode(Request $request){

        $rule = [
            'phone_number'      => 'required|min:9|max:255'
        ];

        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return response()->error($validator->errors(), 422);

        }else{

            $user = User::where('phone_number', '=' ,$request->phone_number)
                ->firstOrFail();
            if (!$user->email) {
                $verify = Verification::generate_secure_code("phone_number");
                $verify->author_id = $user->id;
                $verify->save();
                $verify->send_code($user->phone_number);

                return response()->success(trans('auth.code_sent'));
            }else{
                return response()->error('The user has already been registered', 422);
            }
        }
    }


    /**
     * Send verification code to user phone number.
     *
     * @param Instance Request instance
     *
     * @return JsonResponse user details and auth credentials
     */
    public function verifyCode(Request $request){

        $rule = [
            'code'      => 'required|min:6|max:12'
        ];

        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return response()->error($validator->errors(), 422);

        }else{
            $verify = Verification::where('code', '=' ,$request->code)->where('status', '!=', 'done')->first();
            if($verify){
                $verify->status = "done";
                $verify->save();
                $user = User::where('id', '=' ,$verify->author_id)->firstOrFail();
                $this->save_device($user);
                $token = JWTAuth::fromUser($user);
                return response()->json(compact('user', 'token'));
            }
            else {
                return response()->error(trans('auth.code_invalid'), 422);
            }
        }
    }


    public function putMe(Request $request)
    {
        $user = Auth::user();
        if ($user == null)
            return response('User not found', 401);

        $rule = [
            'first_name'       => 'min:3|max:255',
            'last_name'       => 'min:3|max:255',
            'device_tokens'       => 'array',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'password'   => 'min:5|confirmed',
            'settings' => 'array'
        ];
        if($request->phone_number!=null){
            $rule['phone_number'] = 'min:9|max:255|unique:users,phone_number,'.$user->id;
        }
        if($request->password){
            $rule['current_password'] = 'required|min:5';
        }

        $validator = Validator::make($request->all(), $rule);

        if($validator->fails()){
            return response()->error($validator->errors(), 422);
        }

        if($request->first_name) $user->first_name = trim($request->first_name);
        if($request->last_name) $user->last_name = trim($request->last_name);
        if($request->phone_number) $user->phone_number = $request->phone_number;
        if($request->device_tokens) $user->device_tokens = $request->device_tokens;
        $user->email = trim(strtolower($request->email));



        if ($request->has('current_password')or $request->has('password')) {
            Validator::extend('hashmatch', function ($attribute, $value, $parameters) {
                return Hash::check($value, Auth::user()->password);
            });

            $rules = [
                'current_password' => 'required|hashmatch:data.current_password',
                'password' => 'required|min:5|confirmed',
                'password_confirmation' => 'required|min:5',
            ];

            $payload = $request->only('current_password', 'password', 'password_confirmation');

            $messages = [
                'hashmatch' => 'Invalid Password',
            ];

            $validator = app('validator')->make($payload, $rules, $messages);

            if ($validator->fails()) {
                return response()->error($validator->errors(), 422);
            } else {
                $user->password = Hash::make($request['password']);
            }
        }
        if(isset($request->settings)){
            foreach ($request->settings as  $key=>$val){
                Setting::set($key,$val, $user->id);
            }
            Setting::save($user->id);
        }

        $user->save();
        return response()->success(compact('user'));
    }

    /**
     * Get all roles and their corresponding permissions.
     *
     * @return array
     */
    private function getRolesAbilities()
    {
        $abilities = [];
        $roles = Role::all();

        foreach ($roles as $role) {
            if (!empty($role->name)) {
                $abilities[$role->name] = [];
                $rolePermission = $role->permissions()->get();

                foreach ($rolePermission as $permission) {
                    if (!empty($permission->name)) {
                        array_push($abilities[$role->name], $permission->name);
                    }
                }
            }
        }

        return $abilities;
    }
    

}
