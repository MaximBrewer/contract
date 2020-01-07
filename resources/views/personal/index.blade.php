@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-md-3 col-lg-2">
        <ul>
            <li>
                <router-link :to="{name: 'auctionIndex'}">Auctions</router-link>
            </li>
            <li>
                <router-link :to="{name: 'contragentIndex'}">Contragents</router-link>
            </li>
        </ul>
    </div>
    <div class="col-xs-12 col-md-9 col-lg-10">
        <router-view></router-view>
    </div>
</div>
@endsection