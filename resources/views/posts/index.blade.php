@extends('layouts.app')

@section('title', 'Home')

@section('content')
  @forelse($all_posts as $post)
    <div class="mt-2 border border-2 rounded py-3 px-4">
      <a href="{{ route('post.show', $post->id) }}">
        <h2 class="h4 fw-bold">{{ $post->title }}</h2>
      </a>
      <h3 class="h6 text-muted">{{ $post->user->name }}</h3>
      <p class="fw-light mb-0">{{ $post->body }}</p>

      {{-- ACTION BUTTONS --}}
      {{-- IF the AUTH user is the OWNER of the post, show the edit and delete buttons --}}
      @if(Auth::user()->id === $post->user_id)
        <div class="mt-2 text-end">
          <a href="{{ route('post.edit', $post->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>

          <form action="{{ route('post.destroy', $post->id) }}" method="post" class="d-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
          </form>
        </div>
      @endif
    </div>
  @empty
    <div style="margin-top: 100px">
      <h2 class="text-muted text-center">No Posts yet.</h2>
      <p class="text-center">
        <a href="{{ route('post.create') }}" class="text-decoration-none">Create new post</a>
      </p>
    </div>
  @endforelse
@endsection
