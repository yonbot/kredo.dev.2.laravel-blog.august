<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index() 
    {
        $all_posts = $this->post->latest()->get();

        return view('posts.index')->with('all_posts', $all_posts);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        # 1. Validate the request
        $request->validate([
            'title' => 'required|max:50',
            'body'  => 'required|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048'
            // mimes = Multipurpose Internet Mail ExtentionS
        ]);

        # 2. Save the request/form data to the database
        $this->post->user_id = Auth::user()->id;
        // Auth::user() ~~ contains the entire record/details of the LOGGED IN user.
        $this->post->title   = $request->title;
        $this->post->body    = $request->body;
        $this->post->image   = $this->saveImage($request);
        $this->post->save();

        # 3. Redirect back to the homepage
        return redirect()->route('index');
    }

    private function saveImage($request)
    {
        // Change the name of the image to the CURRENT TIME 
        // to avoid overwriting/deleting
        $image_name = time() . "." . $request->image->extension();
        // $image_name = 215205.jpg

        // SAVE the image inside the storage/app/public/images folder
        // self → PostController
        // :: → s   cope operator
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);

        return $image_name;
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('posts.show')->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        // 投稿者かどうかを判定する
        // URLをバイパスされないようにする
        if ($post->user->id != Auth::user()->id) {
            return redirect()->back();
        }

        return view('posts.edit')->with('post', $post);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:1|max:50',
            'body'  => 'required|min:1|max:1000',
            'image' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        $post = $this->post->findOrFail($id);
        $post->title = $request->title;
        $post->body = $request->body;

        # IF there is a new uploaded image...
        if ($request->image) {
            # DELETE the previous image from the local storage
            $this->deleteImage($post->image);

            # MOVE the new image to the local storage
            $post->image = $this->saveImage($request);
        }

        $post->save();
        return redirect()->route('post.show', $id);
    }

    private function deleteImage($image_name)
    {
        // /public/images/goofy.jpg
        $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;

        if (Storage::disk('local')->exists($image_path)) {
            Storage::disk('local')->delete($image_path);
        }
    }

    public function destroy($id)
    {
        /*
           1. Specify which of the post are you going to delete.
           2. Delete that specific post
           3. Redirect

           3 FILES TO EDIT:
           - Controller
           - Routes
           - Index Page
           
        */
        $post = $this->post->findOrFail($id);
        $this->deleteImage($post->image);
        $post->destroy($id);
        return redirect()->back();
    }
}
