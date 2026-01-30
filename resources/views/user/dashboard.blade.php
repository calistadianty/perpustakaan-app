@extends('user.layout')

@section('content')
<h1>Dashboard User</h1>
@endsection
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">
        Logout
    </button>
</form>
