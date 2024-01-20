<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $user = User::where('id',Auth::user()->id)->where('estatus',1)->first();
        return view('profile.view',compact('user'));
    }

    public function edituser($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function updateUser(Request $request){
        $messages = [
            'profile_pic.image' =>'Please provide a Valid Extension Image(e.g: .jpg .png)',
            'profile_pic.mimes' =>'Please provide a Valid Extension Image(e.g: .jpg .png)',
            'first_name.required' =>'Please provide a First Name',
            'last_name.required' =>'Please provide a Last Name',
            'mobile_no.required' =>'Please provide a Mobile No.',
            'dob.required' =>'Please provide a Date of Birth.',
            'email.required' =>'Please provide a valid E-mail address.',
        ];

        $validator = Validator::make($request->all(), [
            'profile_pic' => 'image|mimes:jpeg,png,jpg',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'required|numeric|digits:10',
            'dob' => 'required',
            'email' => 'required|email',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }

        $user = User::find($request->user_id);

        if(!$user){
            return response()->json(['status' => '400']);
        }

        $old_image = $user->profile_pic;
        $image_name = $old_image;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile_no = $request->mobile_no;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->email = $request->email;

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $image_name = 'profilePic_' . rand(111111, 999999) . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = env('IMAGE_URL').'profile_pic/';
            $image->move($destinationPath, $image_name);
            if(isset($old_image)) {
                $old_image = env('IMAGE_URL').'profile_pic/'. $old_image;
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            $user->profile_pic = $image_name;
        }

        $user->save();

        return response()->json(['status' => '200','user'=>$user]);
    }

    public function editpassword($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function updatePassword(Request $request){
        $messages = [
            'old_password.required' =>'Please provide a Old Password',
            'new_password.required' =>'Please provide a New Password',
            'confirm_new_password.required' =>'Please provide a Re-enter New Password',
            'confirm_new_password.same' =>'New Password and Re-enter New Password must be same',
        ];

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }

        $user = User::find($request->password_user_id);

        if(!$user){
            return response()->json(['status' => '400']);
        }

        if ($user->decrypted_password != $request->old_password){
            return response()->json(['error' => "You have entered wrong Old Password",'status'=>'failed_old_password']);
        }

        $user->password = Hash::make($request->confirm_new_password);
        $user->decrypted_password = $request->confirm_new_password;
        $user->save();

        return response()->json(['status' => '200','user'=>$user]);
    }

}
