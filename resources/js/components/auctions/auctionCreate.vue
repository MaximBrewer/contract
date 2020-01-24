<template>
  <section class="auction-edit-wrapper">
    <div class="container">
      <form v-on:submit="saveForm()">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Auction lot') }}</label>
              <v-select
                label="title"
                :options="products"
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
                 v-mask="'999.99'"
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
                zone="Europe/Moscow"
                value-zone="Europe/Moscow"
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
                zone="Europe/Moscow"
                value-zone="Europe/Moscow"
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
            <button class="btn btn-primary">{{ __('Create auction') }}</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
export default {
  mounted() {
    let loader = Vue.$loading.show();
    let app = this;
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    loader.hide()
  },
  data: function() {
    return {
      isLoading: true,
      fullPage: true,
      multiplicities: [],
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
      },
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
    saveForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();
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
          app.$router.replace("/personal/auctions/show/" + resp.data.id);
        })
        .catch(function(errors) {
          app.errors = errors.response.data.errors;
          console.log(app.errors["multiplicity.id"]);
          loader.hide()
        });
    }
  }
};
</script>