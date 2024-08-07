<!-- resources/views/auth/edit_delete_login.blade.php -->

@extends('layouts.default')

@section('content')
<div class="container">
    <h2>Edit/Delete Authentication</h2>
    <form action="{{ route('admin.edit_delete_auth.authenticate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
