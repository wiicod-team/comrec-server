<?php

namespace App\Api\V1\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetNotification;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    public function sendResetToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required_without:phone|email|exists:users,email',
            'phone' => 'required_without:email|exists:users,phone',
        ]);

        $user= User::whereEmail($request->email)
            ->orWhere('phone',$request->phone)
            ->firstOrFail();

        //invalidate old tokens
        PasswordReset::whereUserId($user->id)->delete();

        if ($request->email){
            $t = str_random(6);
        }else{
            $t = PasswordReset::generatePIN();
        }
        $reset = PasswordReset::create([
            'user_id' => $user->id,
            'token' => $t
        ]);
        $token = $reset->token;

        Notification::send([$user] ,new PasswordResetNotification(['token'=>$token, 'is_phone'=>$request->is_phone]));
        Log::info('sending sms containing code for password recovery to ' . $user->phone);

        return response()->success(true);
    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'email' => 'required_without:phone|email|exists:users,email',
            'phone' => 'required_without:email|exists:users,phone',
            'token' => 'required',
        ]);

        $user= User::whereEmail($request->email)
            ->orWhere('phone',$request->phone)
            ->firstOrFail();

        $check = PasswordReset::where('user_id',$user->id)
        ->whereToken($request->token)
        ->first();

        if (!$check) {
            return response()->error('User does not exist', 422);
        }

        return response()->success(true);
    }

    public function reset(Request $request)
    {

        $this->validate($request, [
            'token'    => "required|exists:password_resets,token",
            'password' => 'required|min:8|confirmed',
        ]);

        $pr = PasswordReset::whereToken($request->token)->firstOrFail();
        $user = $pr->user;


        $user->password = $request->password;
        $user->save();

        //delete pending resets
        PasswordReset::whereUserId($user->id)->delete();

        return response()->success(true);
    }
}
