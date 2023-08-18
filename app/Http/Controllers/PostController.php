<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index() {
        return view('posts.index');
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
        // :: → scope operator
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);

        return $image_name;
    }
}
