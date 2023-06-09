<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Str;

class PostsController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)//when i come to blog page i takes posts from my database , and here we pagenate ,every 6 posts in each page
    {

        $posts = Post::where([
            ['title', '!=', NULL],
            [function ($query) use ($request){
                if (($term = $request->term)){
                    $query->orWhere('title', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy('updated_at', 'DESC')
            ->paginate(6);

        return view('blog.index', compact('posts'));

        // return view('blog.index')
        //     ->with('posts', Post::orderBy('updated_at', 'DESC')->paginate(6));
            //Post::orderBy('updated_at', 'DESC')->get()
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()  //after press on create w new blog
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //after fill the blog and press submit, it checks the validatory of the request and creates a post
    {
        
        $request->validate([
          'title' => 'required',
          'data' => 'required',
          'image' => 'required|mimes:jpg,png,jpeg|max:5048'  
        ]);

        $newImageName = uniqid() . '-' . $request->title . '.' . $request->image->extension();

        $request->image->move(public_path('images'), $newImageName);

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('data'),
            'slug' => SlugService::createSlug(Post::class, 'slug', "title"),
            'image_path' => $newImageName,
            'category' => $request->input('category'),
            'user_id' => auth()->user()->id
        ]);

        return redirect('/blog')
            ->with('message', 'Your post has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)//for reading a blog , and also i will list the related blogs that are in same category
    {   

        $post = Post::where('slug', $slug)->first();
        $relatedPosts = Post::inRandomOrder()->where('category', $post->category)->where('slug', '!=', $post->slug)->get();
        return view('blog.show', compact('post', 'relatedPosts'));
            // ->with('post', compact('post, relatedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug) //if i want to edit a blog
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)// after edit the blog i press submit post
    {
        $request->validate([
            'title' => 'required',
            'data' => 'required',
        ]);

        Post::where('slug', $slug)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('data'),
                'category' => $request->input('category'),
                'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
                'user_id' => auth()->user()->id
            ]);

        return redirect('/blog')
            ->with('message', 'Your post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)// if i press delete 
    {
        $post = Post::where('slug', $slug);
        $post->delete();

        return redirect('/blog')
            ->with('message', 'Your post has been deleted!');
    }
}

