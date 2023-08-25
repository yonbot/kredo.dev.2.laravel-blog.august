@extends('layouts.app')

@section('title', 'Profile')

@section('content')
  <div class="row mt-2 mb-5">
    <div class="col-4">
      @if($user->avatar)
        <img src="{{ asset('/storage/avatars/' . $user->avatar) }}" 
          alt="{{ $user->avatar }}" 
          class="border-0 rounded-circle img-thumbnail w-100">
      @else
        <i class="fa-solid fa-image fa-10x d-block text-center"></i>
      @endif
    </div>
    <div class="col-8">
      <h2 class="display-6">{{ $user->name }}</h2>
      <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit Profile</a>
    </div>
  </div>

  {{-- IF the user has POSTS, show all the posts of this USER
    use eloquent relationship --}}
  @if($user->posts)
    <ul class="list-group mb-5">
      @foreach ($user->posts as $post)
        <li class="list-group-item py-4">
          <a href="{{ route('post.show', $post->id) }}">
            <h4>{{ $post->title }}</h4>
          </a>
          <p class="fw-light mb-0">{{ $post->body }}</p>
        </li>
      @endforeach
    </ul>
  @endif

  {{-- 
  <div class="mt-2 border border2 rounded py-3 px-4 shadow">
    <h2 class="h4">{{ $user->name }}</h2>
    <h3 class="h6 text-muted">{{ $user->email }}</h3>

    <img src="{{ asset('/storage/images/' . $user->avatar) }}" 
      alt="{{ $user->avatar }}" 
      width="200" height="200"
      class="border-0 rounded-circle img-thumbnail">

    <div class="mt-2 text-end">
      <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>
    </div>
  </div> 
  --}}
@endsection
