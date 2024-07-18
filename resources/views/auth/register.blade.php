@extends('layouts.main')

@section('content')

<div class="row">

    <div class="col-lg-3">
      @include('includes.sidebar')
    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <!-- /.card -->

      <div class="card card-outline-secondary my-4">
        <div class="card-header">
          Inscription
        </div>
        <div class="card-body">
          
          <form action="{{ route('post.register') }}" method="post">

            @csrf

            <div class="form-group">
              <label for="name">Nom</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}">
              @error('name')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}">
              @error('email')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label for="password">Mot de passe</label>
              <input type="password" name="password" class="form-control">
              @error('password')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>

            {{-- <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div> --}}
            <button type="submit" class="btn btn-primary">Inscription</button>
          </form>

          <p><a href="{{ route('login') }}">J'ai déjà un compte</a></p>

        </div>
      </div>
      <!-- /.card -->

    </div>
    <!-- /.col-lg-9 -->

  </div>

@stop