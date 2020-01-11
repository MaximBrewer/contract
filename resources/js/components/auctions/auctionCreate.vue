<template>
  <section class="auction-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()">
        <div class="form-group">
          <label class="control-label">{{ __('Auction lot') }}</label>
          <v-select label="title" :options="products" v-model="auction.product"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction Start Price') }}</label>
          <input type="number" v-model="auction.start_price" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction Volume') }}</label>
          <input type="text" v-model="auction.volume" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction multiplicity') }}</label>
          <v-select label="title" :options="multiplicities" v-model="auction.multiplicity"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction contragent') }}</label>
          <v-select label="title" :options="contragents" v-model="auction.contragent"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction store') }}</label>
          <v-select label="address" :options="stores" v-model="auction.store"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction start') }}</label>
          <datetime
            type="datetime"
            class="theme-primary"
            input-class="form-control"
            v-model="auction.start_at"
          ></datetime>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction finish') }}</label>
          <datetime
            type="datetime"
            class="theme-primary"
            input-class="form-control"
            v-model="auction.finish_at"
          ></datetime>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Auction comment') }}</label>
          <textarea v-model="auction.comment" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <button class="btn btn-primary btn-lg">{{ __('Create auction') }}</button>
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
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    app.getContragents();
    app.isLoading = false;
  },
  data: function() {
    return {
      isLoading: true,
      fullPage: true,
      multiplicities: [],
      contragents: [],
      stores: [],
      products: [],
      auction: {
        contragent: null,
        store: null,
        start_at: null,
        finish_at: null,
        comment: "",
        product: null,
        multiplicity: null
      }
    };
  },
  methods: {
    getMultiplicities() {
      let app = this;
      axios
        .get(
          "/api/v1/multiplicities?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.multiplicities = resp.data;
        });
    },
    getProducts() {
      let app = this;
      axios
        .get(
          "/api/v1/products?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.products = resp.data;
        });
    },
    getStores() {
      let app = this;
      axios
        .get(
          "/api/v1/stores?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.stores = resp.data;
        });
    },
    getContragents() {
      let app = this;
      axios
        .get(
          "/api/v1/contragents?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.contragents = resp.data;
        });
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      app.isLoading = true;
      var newAuction = app.auction;
      axios
        .post(
          "/api/v1/auctions?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          newAuction
        )
        .then(function(resp) {
          app.auction = resp.data;
          app.$router.replace(
            "/auctions/edit/?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token +
              resp.data.id
          );
          app.isLoading = false;
          return true;
        })
        .catch(function(resp) {
          console.log(resp);
          alert(app.__("Failed to create auction"));
          app.isLoading = false;
        });
    }
  }
};
</script>