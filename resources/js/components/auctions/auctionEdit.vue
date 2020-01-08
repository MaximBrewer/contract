<template>
  <section class="auction-edit-wrapper">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>
    <form v-on:submit="saveForm()">
      <div class="form-group">
        <label class="control-label">{{ __('Auction lot') }}</label>
        <v-select label="title" :options="products" v-model="auction.product"></v-select>
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
        <datetime type="datetime" class="theme-primary" input-class="form-control" v-model="auction.start_at"></datetime>
      </div>
      <div class="form-group">
        <label class="control-label">{{ __('Auction finish') }}</label>
        <datetime type="datetime" class="theme-primary" input-class="form-control" v-model="auction.finish_at"></datetime>
      </div>
      <div class="form-group">
        <label class="control-label">{{ __('Auction comment') }}</label>
        <textarea v-model="auction.comment" class="form-control"></textarea>
      </div>
      <div class="form-group">
        <button class="btn btn-primary btn-lg">{{ __('Edit') }}</button>
      </div>
    </form>
  </section>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Datetime } from 'vue-datetime';
import 'vue-datetime/dist/vue-datetime.css'
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
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    app.getContragents();
    app.auctionId = id;
    axios
      .get("/api/v1/auctions/" + id)
      .then(function(resp) {
        app.auction = resp.data;
        app.isLoading = false;
      })
      .catch(function() {
        alert( app.__('Failed to load auction') );
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
      auction: {
        name: "",
        address: "",
        website: "",
        email: ""
      }
    };
  },
  methods: {
    getMultiplicities() {
      let app = this;
      axios.get("/api/v1/multiplicities").then(function(resp) {
        app.multiplicities = resp.data;
      });
    },
    getProducts() {
      let app = this;
      axios
        .get("/api/v1/products")
        .then(function(resp) {
          app.products = resp.data;
        });
    },
    getStores() {
      let app = this;
      axios.get("/api/v1/stores").then(function(resp) {
        app.stores = resp.data;
      });
    },
    getContragents() {
      let app = this;
      axios.get("/api/v1/contragents").then(function(resp) {
        app.contragents = resp.data;
      });
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      var newAuction = app.auction;
      app.isLoading = true;
      axios
        .patch("/api/v1/auctions/" + app.auctionId, newAuction)
        .then(function(resp) {
          app.auction = resp.data;
          app.isLoading = false;
          return true;
          //app.$router.replace("/");
        })
        .catch(function(resp) {
          console.log(resp);
          alert( app.__('Failed to update auction') );
          app.isLoading = false;
        });
    }
  }
};
</script>