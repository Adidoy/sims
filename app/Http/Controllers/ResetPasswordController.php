<?php

namespace App\Http\Controllers;

use DB;
use App;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\Notifications;

class ResetPasswordController extends Controller {

    public function getForgotPassword(Request $request) {
		return view('auth.passwords.email');
	}

    public function resetPasswordViaEmail(Request $request) {
		$username = $this->sanitizeString($request->get('username'));
        $email = $this->sanitizeString($request->get('email'));
 		if( App\User::UserExists($email,$username) == 'true' ) {
            $message = 'An message was sent to your email containing instructions to reset your password.';
			$user = App\User::findByUserName($username)->get();
			$token = bin2hex(random_bytes($length));
            \Notification::send($user, new App\Notifications\EmailResetPassword($token));
		}
		else {
			$message = 'The entered username and email address does not match our records. Please verify them and try again.';
		}
		return view('auth.passwords.emailsent')
			->with('message',$message);
	}
	
	public function afterEmail() {
		return view('auth.passwords.reset')
			->with('token','token');
	}

	public function resetPassword(Request $request, $token) {
		
		$email = $this->sanitizeString($request->get('email'));
		$password = $this->sanitizeString($request->get('password'));
		if($password != null && $password != "" && $password != " ") {
			DB::beginTransaction();
			$user = App\User::findByEmail($email)->first();
			$user->password = Hash::make($password);
			$user->save();
			DB::commit();
			\Alert::success("Pasword reset successful!")->flash();
			return redirect('login');
		}
		else {
			\Alert::failed("Invalid input!")->flash();
		}
	}
}