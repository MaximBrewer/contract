@extends('layouts.app')

@section('content')
<div class="container">
    <div id="app">
        <div class="panel-heading">Contragents</div>
        <div class="panel-body table-responsive">
            <router-view name="contragentsIndex"></router-view>
            <router-view></router-view>
        </div>
    </div>
</div>
@endsection