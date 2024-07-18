<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Article, Category
};

use Str, Auth;

use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    protected $perPage = 12;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderByDesc('id')->paginate($this->perPage);

        $data = [
            'title'=>'Les articles du blog - '.config('app.name'),
            'description'=>'Retrouvez tous les articles de '.config('app.name'),
            'articles'=>$articles,
        ];

        return view('article.index', $data);

        // $count = Article::count();
        // dd($count);
        //$articles = Article::orderByDesc('id')->skip(10)->limit(15)->get();
        // $article = Article::where('title', 'Aliquam quia voluptates voluptatem distinctio saepe sint.')->first();
        // dd($article->id);
        // $articles = Article::orderBy('title')->get();

        // foreach($articles as $article){
        //     dump($article->id, $article->title);
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();

        $data = [
            'title' => $description = 'Ajouter un nouvel article',
            'description'=>$description,
            'categories'=>$categories,
        ];
        return view('article.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['category_id'] = request('category', null);

        Auth::user()->articles()->create($validatedData);

        $success = 'Article ajouté';

        return back()->withSuccess($success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $data = [
            'title'=>$article->title.' - '.config('app.name'),
            'description'=>$article->title.'. '.Str::words($article->content, 10),
            'article'=>$article,
            'comments'=>$article->comments()->orderByDesc('created_at')->get(),
        ];
        return view('article.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        abort_if(auth()->id() != $article->user_id, 403);

        $data = [
            'title'=> $description = 'Mise à jour de '.$article->title,
            'description'=>$description,
            'article'=>$article,
            'categories'=>Category::get(),
        ];

        return view('article.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        abort_if(auth()->id() != $article->user_id, 403);

        $validatedData = $request->validated();
        $validatedData['category_id'] = request('category', null);

        $article = Auth::user()->articles()->updateOrCreate(['id'=>$article->id], $validatedData);

        $success = 'Article modifié';

        return redirect()->route('articles.edit', ['article'=>$article->slug])->withSuccess($success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        abort_if(auth()->id() != $article->user_id, 403);

        $article->delete();

        $success = 'Article supprimé.';

        return back()->withSuccess($success);
    }
}
