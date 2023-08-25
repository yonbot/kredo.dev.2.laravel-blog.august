@extends('layouts.app')

@section('title', 'User Edit')

@section('content')
  <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row mb-3">
      <div class="col">
        <label for="avatar" class="form-label text-muted">Avatar</label><br>
        <img src="{{ asset('/storage/avatars/' . $user->avatar) }}" 
          alt="{{ $user->avatar }}" 
          width="200" height="200"
          class="border-0 rounded-circle img-thumbnail">
        <input type="file" name="avatar" id="avatar" class="form-control mt-1"
          aria-describedby="image-info">
        <div class="form-text" id="image-info">
          Acceptable formats: jpeg, jpg, png, gif only <br>
          Maximum file size: 1048kB
        </div>
        {{-- ERROR --}}
        @error('avatar')
          <p class="text-danger small">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label text-secondary">Name</label>
      <input type="text" name="name" id="name" 
        class="form-control" placeholder="Enter Name here"
        value="{{ old('name', $user->name) }}">
      {{-- Error --}}
      @error('name')
        <p class="text-danger small">{{ $message }}</p>
      @enderror
    </div>

    <div class="mb-3">
      <label for="email" class="form-label text-secondary">Email</label>
      <input type="email" name="email" id="email" 
        class="form-control" placeholder="Enter Email here"
        value="{{ old('email', $user->email) }}">
      {{-- Error --}}
      @error('email')
        <p class="text-danger small">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="btn btn-warning px-5">Save</button>
  </form>
@endsection
