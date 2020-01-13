<template>
  <section class="auction-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()">
        <div class="form-group">
          <label class="control-label">{{ __('Auction lot') }}</label>
          <div class="form-control">{{ auction.product.title }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction multiplicity') }}</label>
          <div class="form-control">{{ auction.multiplicity.title }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction contragent') }}</label>
          <div class="form-control">{{ auction.contragent.title }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction store address') }}</label>
          <div class="form-control">{{ auction.store.address }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction store coords') }}</label>
          <div class="form-control">{{ auction.store.coords }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction store federal district') }}</label>
          <div class="form-control">{{ auction.store.federal_district.title }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction store region') }}</label>
          <div class="form-control">{{ auction.store.region.title }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction start') }}</label>
          <div class="form-control">{{ auction.start_at }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction finish') }}</label>
          <div class="form-control">{{ auction.finish_at }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction comment') }}</label>
          <div class="form-control">{{ auction.comment }}</div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction bidders') }}</label>
          <ul id="auction-bidders">
            <li class="form-control" v-for="bidder, index in auction.bidders">{{ bidder.title }}</li>
          </ul>
        </div>
      </form>
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
    return {
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
  methods: {
  }
};
</script>