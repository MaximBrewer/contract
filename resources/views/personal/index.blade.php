@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-6">
        <router-link :to="{name: 'auctionIndex'}" class="btn btn-primary">Auctions</router-link>
    </div>
    <div class="col-6">
        <router-link :to="{name: 'contragentIndex'}" class="btn btn-primary">Contragents</router-link>
    </div>
</div>
<hr>
<router-view></router-view>
@endsection