<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Models\User;

use Storage, Image, Str, DB;

class UserController extends Controller
{
	public function __construct(){
		$this->middleware('auth')->except('profile');
	}

    public function profile(User $user)
    {
    	$articles = $user->articles()->withCount('comments')->orderByDesc('comments_count')->paginate(4);
    	// dd($articles);
    	$data = [
    		'title' => 'Profil de '.$user->name,
    		'description' => $user->name.' est inscrit depuis le '.$user->created_at->isoFormat('LL').' et a posté '.$user->articles()->count().' article(s)',
    		'user'=>$user,
    		'articles'=>$articles,
    	];

    	return view('user.profile', $data);
    }


    public function edit() //formulaire de mise à jour des infos du user connecté
    {
    	$user = auth()->user();
    	$data = [
    		'title'=> $description = 'Editer mon profil',
    		'description'=> $description,
    		'user'=>$user,
    	];
    	return view('user.edit', $data);
    }

    public function password() //formulaire d'update de password
    {
    	$data = [
    		'title' => $description = 'Modifier mon mot de passe',
    		'description'=>$description,
    		'user'=>auth()->user(),
    	];
    	return view('user.password', $data);
    }

    public function updatePassword() //mise à jour du mot de passe
    {
    	request()->validate([
    		'current'=>'required|password',
    		'password'=>'required|between:9,20|confirmed',
    	]);

    	$user = auth()->user();

    	$user->password = bcrypt(request('password'));

    	$user->save();

    	$success = 'Mot de passe mis à jour.';
    	return back()->withSuccess($success);
    }

    public function store() //sauvegarde des infos user
    {
    	$user = auth()->user();

    	DB::beginTransaction();

    	try
    	{
	    	$user = $user->updateOrCreate(['id'=>$user->id], 
	    		request()->validate([
		    		'name'=>['required', 'min:3', 'max:20', Rule::unique('users')->ignore($user)],
		    		'email'=>['required', 'email', Rule::unique('users')->ignore($user)],
		    		'avatar'=>['sometimes', 'nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=200,min_height=200'],
		    	]));

	    	if(request()->hasFile('avatar') && request()->file('avatar')->isValid()){

	    		if(Storage::exists('avatars/'.$user->id)){
	    			Storage::deleteDirectory('avatars/'.$user->id);
	    		}

	    		$ext = request()->file('avatar')->extension();
	    		$filename = Str::slug($user->name).'-'.$user->id.'.'.$ext;

	    		$path = request()->file('avatar')->storeAs('avatars/'.$user->id, $filename);
	    		
	    		$thumbnailImage = Image::make(request()->file('avatar'))->fit(200, 200, function($constraint){
	    			$constraint->upsize();
	    		})->encode($ext, 50);

	    		$thumbnailPath = 'avatars/'.$user->id.'/thumbnail/'.$filename;

	    		Storage::put($thumbnailPath, $thumbnailImage);

	    		$user->avatar()->updateOrCreate(['user_id'=>$user->id],
	    			[
	    				'filename'=>$filename,
	    				'url'=>Storage::url($path),
	    				'path'=>$path,
	    				'thumb_url'=>Storage::url($thumbnailPath),
	    				'thumb_path'=>$thumbnailPath,
	    			]
	    		);
	    	}
    	}
    	catch(ValidationException $e){
    		DB::rollBack();
    		dd($e->getErrors());
    	}

    	DB::commit();

    	$success = 'Informations mises à jour.';
    	return back()->withSuccess($success);
    }

    public function destroy(User $user) //suppression du compte utilisateur
    {
    	abort_if($user->id !== auth()->id(), 403);
    	
    	Storage::deleteDirectory('avatars/'.$user->id);

    	$user->delete();

    	$success = 'Utilisateur supprimé';
    	return redirect('/')->withSuccess($success);
    } 
}
