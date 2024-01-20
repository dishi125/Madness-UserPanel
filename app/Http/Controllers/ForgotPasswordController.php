<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $messages = [
            'email.required' =>'Please provide a valid E-mail address.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email,deleted_at,NULL',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json(['status'=>200]);
    }

    public function showResetPasswordForm($token) {
        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $messages = [
            'email.required' =>'Please provide a valid E-mail address.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email,deleted_at,NULL',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return response()->json(['error' => "Invalid token",'status'=>400]);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password), 'decrypted_password' => $request->password]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json(['status'=>200]);
    }
}
