<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; // Add this line
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Add this line
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this line

class PostController extends Controller
{
    use AuthorizesRequests; // Add this line

    public function __construct(){
        $this->middleware('auth')->except(['show', 'index']); 
        Carbon::setLocale('es'); // Add this line
    }

    public function index(User $user){
        $posts = Post::where('user_id', $user->id)->latest()->paginate(20); 

        return view('dashboard', [
            'user' => $user, 
            'posts' => $posts
        ]); 
    }

    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);
        
        // Otra forma de crear un post
        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen, 
        //     'user_id' => Auth::user()->id
        // ]);

        
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen, 
            'user_id' => Auth::user()->id
        ]); 
        return redirect()->route('posts.index', Auth::user()->username); 
    }

    public function show(User $user, Post $post){
        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]); 
    }

    public function destroy(Post $post){
        $this->authorize('delete', $post); 

        $post->delete(); 
        $imagenPath = public_path('uploads' . $post->imagen);
        
        if(File::exists($imagenPath)){
            unlink($imagenPath); 
        }

        return redirect()->route('posts.index', Auth::user()->username);  
    }

}
