<template>
  <section class="auction-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div
              class="card-header"
              v-if="auction.contragent && auction.contragent.title"
            >{{ auction.contragent.title }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.federal_district"
              >{{ auction.store.federal_district.title }}</li>
              <li
                class="list-group-item"
                v-if="auction.region && auction.store.region"
              >{{ auction.store.region.title }}</li>
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.address"
              >{{ auction.store.address }}</li>
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.coords"
              >{{ auction.store.coords }}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-6">
          <div class="card">
            <div
              class="card-header"
              v-if="auction.product && auction.product.title"
            >{{ auction.product.title }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="auction.multiplicity"
              >{{ auction.multiplicity.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Auction volume') }}: {{ auction.volume }} {{ __('un') }}.</li>
              <li
                class="list-group-item"
              >{{ __('Auction start price') }}: {{ auction.start_price }}₽</li>
              <li class="list-group-item">{{ __('Auction step') }}: {{ auction.step }}₽</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('Auction start') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ auction.start_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('During time') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ time | formatDateTime}}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('Auction finish') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ auction.finish_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __('Auction comment') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item" v-if="auction.comment">{{ auction.comment }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __('Auction bidders') }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="bidder, index in auction.bidders"
              >{{ bidder.title }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect: vSelect,
    Datetime: Datetime,
    Loading: Loading
  },
  mounted() {
    let app = this;
    app.isLoading = true;
    let id = app.$route.params.id;
    app.auctionId = id;
    axios
      .get(
        "/api/v1/auction/" +
          id +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.auction = resp.data;
        app.isLoading = false;
      })
      .catch(function() {
        alert(app.__("Failed to load auction"));
        app.isLoading = false;
      });
  },
  data: function() {
    console.log(window.document.querySelector('meta[name="server-time"]'));
    return {
      time: window.document.querySelector('meta[name="server-time"]').content,
      isLoading: true,
      onCancel: false,
      fullPage: true,
      multiplicities: [],
      contragents: [],
      stores: [],
      products: [],
      auctionId: null,
      auction: {}
    };
  },
  created() {
    this.listenForBroadcast();
  },
  methods: {
    listenForBroadcast() {
      var that = this;
      Echo.channel("cross_contractru_database_every-minute").listen(
        "PerMinute",
        function(e) {
          that.time = e.time;
        }
      );
    }
  }
};
</script>