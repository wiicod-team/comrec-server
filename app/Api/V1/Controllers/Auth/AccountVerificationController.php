<?php

namespace App\Api\V1\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetNotification;
use App\PasswordReset;
use App\User;
use App\Verification;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class AccountVerificationController extends Controller
{
    public function activateEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
        ]);

        $user= User::whereEmail($request->email)
            ->firstOrFail();

        $verify = Verification::where('verifiable_id',$user->id)
        ->where('verifiable_type', 'App\User')
        ->whereToken($request->token)
        ->first();

        if (!$verify) {
            return response()->error('User does not exist', 422);
        }else{
            $verify->status = 'done';
            $verify->save();
            $column = $verify->column;
            $user->$column = 1;
            $user->save();
        }

        return response()->success(true);
    }

    public function activatePhone(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|email|exists:users,email',
            'token' => 'required',
        ]);

        $user= User::wherePhone($request->phone)
            ->firstOrFail();

        $verify = Verification::where('verifiable_id',$user->id)
            ->where('verifiable_type', 'App\User')
            ->whereToken($request->token)
            ->first();

        if (!$verify) {
            return response()->error('User does not exist', 422);
        }else{
            $verify->status = 'done';
            $user->$verify->column = 1;
            $user->save();
        }

        return response()->success(true);
    }

}
