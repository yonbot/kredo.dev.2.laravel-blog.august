<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/avatars/';

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;

        # IF there is a new uploaded image...
        if ($request->avatar) {
            # DELETE the previous image from the local storage
            if ($user->avatar) {
                $this->deleteAvatar($user->avatar);
            }

            # MOVE the new image to the local storage
            $user->avatar = $this->saveAvatar($request);
        }

        $user->save();
        return redirect()->route('profile.show');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function saveAvatar($request)
    {
        // Change the name of the image to the CURRENT TIME 
        // to avoid overwriting/deleting
        $avatar_name = time() . "." . $request->avatar->extension();
        // $avatar_name = 215205.jpg

        // SAVE the image inside the storage/app/public/avatars folder
        // self → PostController
        // :: → scope operator
        $request->avatar->storeAs(self::LOCAL_STORAGE_FOLDER, $avatar_name);

        return $avatar_name;
    }

    private function deleteAvatar($avatar_name)
    {
        // /public/avatars/goofy.jpg
        $avatar_path = self::LOCAL_STORAGE_FOLDER . $avatar_name;

        if (Storage::disk('local')->exists($avatar_path)) {
            Storage::disk('local')->delete($avatar_path);
        }
    }
}
