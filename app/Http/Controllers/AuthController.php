<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Models\PremiumUserTransaction;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }

        $user = User::where('email',$request->email)->where('decrypted_password',$request->password)->where('is_premium',1)->where('estatus',1)->first();
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials) && $user) {
            return response()->json(['status'=>200]);
        }

        return response()->json(['status'=>400]);
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }

    public function register(){
        $Settings = Settings::where('estatus',1)->first();
        return view('auth.register',compact('Settings'));
    }

    public function pay(Request $request)
    {
        $amount = Settings::where('estatus',1)->pluck('premium_user_membership_fee')->first(); //Amount to be paid

        $userData = [
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'mobile_no' => $request->mobile_no,
          'email' => $request->email,
          'password' => $request->password,
          'gender' => $request->gender,
          'dob' => $request->dob,
          'amount' => $amount,
        ];

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $image_name = 'profilePic_' . rand(111111, 999999) . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = env('IMAGE_URL').'profile_pic';
            $image->move($destinationPath, $image_name);
            $userData['profile_pic'] = $image_name;
        }

        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $request->mobile_no."_".rand(1,1000),
            'user' => rand(10,1000),
            'mobile_number' => $request->mobile_no,
            'email' => $request->email, // your user email address
            'amount' => $amount, // amount will be paid in INR.
            'callback_url' => route('register.status',$userData) // callback URL
        ]);
        return $payment->receive();  // initiate a new payment
    }

    public function paymentCallback(Request $request)
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        $order_id = $transaction->getOrderId(); // return a order id
        $transaction->getTransactionId(); // return a transaction id

        // update the db data as per result from api call
        if ($transaction->isSuccessful()) {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_no = $request->mobile_no;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->role = 3;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->decrypted_password = $request->password;
            $user->profile_pic = $request->profile_pic;
            $user->is_premium = 1;
            $user->created_at = new \DateTime(null, new \DateTimeZone('Asia/Kolkata'));
            $user->save();

            PremiumUserTransaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'transaction_id' => $transaction->getTransactionId(),
                'payment_mode' => $response['PAYMENTMODE'],
                'transaction_date' => new \DateTime($response['TXNDATE'], new \DateTimeZone('Asia/Kolkata')),
            ]);

            return redirect(route('login'))->with('message', "Your payment is successfull.");
        } else if ($transaction->isFailed()) {
            if(isset($request->profile_pic)) {
                $profile_pic = env('IMAGE_URL').'profile_pic/'.$request->profile_pic;
                if (file_exists($profile_pic)) {
                    unlink($profile_pic);
                }
            }

            return redirect(route('register'))->with('message', $transaction->getResponseMessage());
        }

        $transaction->getResponseMessage(); //Get Response Message If Available
    }

}
