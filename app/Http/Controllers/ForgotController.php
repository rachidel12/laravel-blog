<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\PasswordResetNotification;

use App\Models\User;

use Str, DB;

class ForgotController extends Controller
{
    public function __construct(){
        $this->middleware('guest');
    }
    
    public function index() //formulaire d'oublie de mot de passe
    {
    	$data = [
    		'title'=> $description = 'Oublie de mot de passe - '.config('app.name'),
    		'description'=>$description,
    	];

    	return view('auth.forgot', $data);
    }

    public function store() //vérification des data et envoi de lien par mail
    {
    	request()->validate([
    		'email'=>'required|email|exists:users',
    	]);

    	$token = Str::uuid();

    	DB::table('password_resets')->insert([
    		'email'=>request('email'),
    		'token'=>$token,
    		'created_at'=>now(),
    	]);

    	//envoi de notification avec un lien sécurisé
    	//
    	$user = User::whereEmail(request('email'))->firstOrFail();

    	$user->notify(new PasswordResetNotification($token));
    	
    	$success = 'Vérifier votre boîte mail et suivez les instructions.';
    	return back()->withSuccess($success);
    }
}
