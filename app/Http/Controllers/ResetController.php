<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use DB;

class ResetController extends Controller
{
    public function __construct(){
        $this->middleware('guest');
    }
    
    public function index(string $token) //formulaire de réinitialisation de mdp
    {
    	$password_reset = DB::table('password_resets')->where('token', $token)->first();

    	abort_if(!$password_reset, 403);

    	$data = [
    		'title' => $description = 'réinitialisation de mot de passe - '.config('app.name'),
    		'description'=>$description,
    		'password_reset'=>$password_reset,
    	];
    	return view('auth.reset', $data);
    }

    public function reset() //traitement de réinitialisation du mot de passe
    {
    	request()->validate([
    		'email'=>'required|email',
    		'token'=>'required',
    		'password'=>'required|between:9,20|confirmed',
    	]);

    	if(! DB::table('password_resets')
    		->where('email', request('email'))
    		->where('token', request('token'))->count()
    	){
    		$error = 'Vérifiez l\'addresse email.';
    		return back()->withError($error)->withInput();
    	}

    	$user = User::whereEmail(request('email'))->firstOrFail();

    	$user->password = bcrypt(request('password'));
    	$user->save();

    	DB::table('password_resets')->where('email', request('email'))->delete();

    	$success = 'Mot de passe mis à jour.';
    	return redirect()->route('login')->withSuccess($success);
    }
}
