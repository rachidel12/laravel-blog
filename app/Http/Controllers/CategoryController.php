<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category) //les article de la categorie
    {
    	$articles = $category->articles()->withCount('comments')->latest()->paginate(5);
    	
    	$data = [
    		'title'=>$category->name,
    		'description'=>'Les articles de la categorie '.$category->name,
    		'category'=>$category,
    		'articles'=>$articles,
    	];

    	return view('category.show', $data);
    }
}
