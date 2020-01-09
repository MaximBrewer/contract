@extends('layouts.app')

@section('content')
<meta name="user-id" content="{{ Auth::user()->id }}">
<meta name="user-username" content="{{ Auth::user()->name }}">
<meta name="user-email" content="{{ Auth::user()->email }}">
<router-view></router-view>
@endsection