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
        <div class="col-md-4">
          <div class="card text-center">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <a
                  href="javascript:void(0)"
                  class="btn btn-success"
                  @click="showPopupAddBidder(auction.id)"
                >{{ __('Add bidder') }}</a>
              </li>
              <li
                class="list-group-item"
                v-if="user.contragents[0] && auction.contragent && user.contragents[0].id == auction.contragent.id"
              >
                <router-link
                  v-tooltip="__('Edit auction')"
                  :to="{name: 'editAuction', 'params': {'id': auction.id}}"
                  class="btn btn-primary"
                >{{ __('Edit auction') }}</router-link>
              </li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-8">
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
    <modal name="add_bidder" height="auto" :adaptive="true" width="90%" :maxWidth="maxModalWidth">
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __('Auction bidder add') }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('add_bidder')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <v-select
            max-height="50px"
            :options="bidders"
            label="title"
            @search="fetchBidders"
            v-model="bidder"
          ></v-select>
          <br />
          <br />
          <br />
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          @click="$modal.hide('target');addBidder()"
        >{{ __('Add bidder') }}</button>
      </div>
    </modal>
  </section>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import vSelect from "vue-select";
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
        "/api/v1/contragents?search=csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.bidders = resp.data;
      });
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
      bidders: [],
      bidder: null,
      maxModalWidth: 600,
      auction: {}
    };
  },
  created() {
    this.listenForBroadcast();
  },
  methods: {
    addBidder() {
      var app = this;
      if (app.auction && app.bidder)
        axios
          .post(
            "/api/v1/auctions/add_bidder?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              auction: app.auction.id,
              bidder: app.bidder.id
            }
          )
          .then(function(resp) {
            app.auction = resp.data;
            app.$modal.hide("add_bidder");
          });
    },
    fetchBidders(search, loading) {
      var app = this;
      loading(true);
      axios
        .get(
          "/api/v1/contragents?search=" +
            search +
            "csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.bidders = resp.data;
          loading(false);
        });
    },
    showPopupAddBidder() {
      var app = this;
      app.$modal.show("add_bidder");
    },
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