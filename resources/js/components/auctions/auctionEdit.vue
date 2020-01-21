<template>
  <section class="auction-edit-wrapper">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
    <div class="container" v-if="auction">
      <form v-on:submit="saveForm()">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Auction lot') }}</label>
              <v-select
                label="title"
                :options="products"
                :disabled="auction.started"
                v-model="auction.product"
                v-bind:class="{ 'is-invalid': errors['product.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['product.id']">
                <strong v-for="(error, index) in errors['product.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction multiplicity') }}</label>
              <v-select
                label="title"
                :disabled="auction.started"
                :options="multiplicities"
                v-model="auction.multiplicity"
                v-bind:class="{ 'is-invalid': errors['multiplicity.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['multiplicity.id']">
                <strong v-for="(error, index) in errors['multiplicity.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction store') }}</label>
              <v-select
                label="address"
                :disabled="auction.started"
                :options="stores"
                v-model="auction.store"
                v-bind:class="{ 'is-invalid': errors['store.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['store.id']">
                <strong v-for="(error, index) in errors['store.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction Volume') }}</label>
              <input
                type="number"
                v-model="auction.volume"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors.volume }"
              />
              <span role="alert" class="invalid-feedback" v-if="errors.volume">
                <strong v-for="(error, index) in errors.volume" :key="index">{{ error }}</strong>
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Auction Start Price') }}</label>
              <input
                type="number"
                :disabled="auction.started"
                v-model="auction.start_price"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors.start_price }"
              />
              <span role="alert" class="invalid-feedback" v-if="errors.start_price">
                <strong v-for="(error, index) in errors.start_price" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction Step') }}</label>
              <input
                type="decimal"
                :disabled="auction.started"
                v-model="auction.step"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors.step }"
              />
              <span role="alert" class="invalid-feedback" v-if="errors.step">
                <strong v-for="(error, index) in errors.step" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction start') }}</label>
              <datetime
                type="datetime"
                class="theme-primary"
                :disabled="auction.started"
                input-class="form-control"
                v-model="auction.start_at"
                v-bind:class="{ 'is-invalid': errors.start_at }"
              ></datetime>
              <span role="alert" class="invalid-feedback" v-if="errors.start_at">
                <strong v-for="(error, index) in errors.start_at" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label class="control-label">{{ __('Auction finish') }}</label>
              <datetime
                type="datetime"
                class="theme-primary"
                input-class="form-control"
                v-model="auction.finish_at"
                v-bind:class="{ 'is-invalid': errors.finish_at }"
              ></datetime>
              <span role="alert" class="invalid-feedback" v-if="errors.finish_at">
                <strong v-for="(error, index) in errors.finish_at" :key="index">{{ error }}</strong>
              </span>
            </div>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="form-group">
            <label class="control-label">{{ __('Auction comment') }}</label>
            <textarea
              v-model="auction.comment"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.comment }"
            ></textarea>
            <span role="alert" class="invalid-feedback" v-if="errors['auction.comment']">
              <strong v-for="(error, index) in errors.comment" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group text-right">
            <button class="btn btn-primary">{{ __('Edit auction') }}</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
import Loading from "vue-loading-overlay";
import { Datetime } from "vue-datetime";
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
    axios
      .get(
        "/api/v1/auction/" +
          app.$route.params.id +
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
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    app.getContragents();
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
      auction: null,
      errors: []
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
      axios
        .post(
          "/api/v1/auction/edit/" +
            app.auction.id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          app.auction
        )
        .then(function(resp) {
          app.$router.replace(
            "/personal/auctions/show/" +
              app.auction.id
          );
        })
        .catch(function(errors) {
          app.errors = errors.response.data.errors;
          app.isLoading = false;
        });
    }
  }
};
</script>