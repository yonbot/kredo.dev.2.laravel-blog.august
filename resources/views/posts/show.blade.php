@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
  <div class="mt-2 border border2 rounded py-3 px-4 shadow">
    <h2 class="h4">{{ $post->title }}</h2>
    <h3 class="h6 text-muted">{{ $post->user->name }}</h3>
    <p>{{ $post->body }}</p>

    <img src="{{ asset('/storage/images/' . $post->image) }}" 
      alt="{{ $post->image }}" class="w-100 shadow">
  </div>

  <div class="mt-3 border border-2 rounded py-3 px-4 shadow">
    <form action="{{ route('comment.store', $post->id) }}" method="post">
      @csrf

      <div class="input-group">
        <input type="text" name="comment" id="comment" class="form-control"
          placeholder="Add a comment..."
          value="{{ old('comment') }}">
        <button type="submit" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
      {{-- ERROR --}}
      @error('comment')
        <p class="text-danger small">{{ $message }}</p>
      @enderror
    </form>

    {{-- IF the POST has COMMENTS, Show the comments. --}}
    @if($post->comments)
      @foreach($post->comments as $comment)
        <div class="row mt-3">
          <div class="col-10">
            <span class="h5 fw-bold">{{ $comment->user->name }}</span>
            &middot;
            <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
            <p class="h6 fw-bold">{{ $comment->body }}</p>
          </div>
          <div class="col-2 text-end">
            {{-- SHOW the delete button if the AUTH user is the OWNER of the COMMENT --}}
            @if(Auth::user()->id === $comment->user_id)
              <form action="{{ route('comment.destroy', $comment->id) }}" method="post" class="d-inline">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm" title="Delete Comment">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    @endif
  </div>
@endsection
