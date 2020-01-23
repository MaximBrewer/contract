<template>
  <section class="target-edit-wrapper">
    <div class="container">
      
        <div class="h2 text-center">{{ __('Creating target') }}</div><br>
      <form v-on:submit="saveForm()" v-if="target">
        <div class="row">
          <div class="col-md-6">
            <div class="jumbotron">
            <div class="h3">{{ __('You can add some description here!') }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <v-select
                :placeholder="__('Target lot')"
                label="title"
                :options="products"
                v-model="target.product"
                v-bind:class="{ 'is-invalid': errors['product.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['product.id']">
                <strong v-for="(error, index) in errors['product.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <v-select
                :placeholder="__('Target multiplicity')"
                label="title"
                :options="multiplicities"
                v-model="target.multiplicity"
                v-bind:class="{ 'is-invalid': errors['multiplicity.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['multiplicity.id']">
                <strong v-for="(error, index) in errors['multiplicity.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <v-select
                :placeholder="__('Target store')"
                label="address"
                :options="stores"
                v-model="target.store"
                v-bind:class="{ 'is-invalid': errors['store.id'] }"
              ></v-select>
              <span role="alert" class="invalid-feedback" v-if="errors['store.id']">
                <strong v-for="(error, index) in errors['store.id']" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group">
              <input
                :placeholder="__('Target volume')"
                type="number"
                v-model="target.volume"
                class="form-control"
                v-bind:class="{ 'is-invalid': errors.volume }"
              />
              <span role="alert" class="invalid-feedback" v-if="errors.volume">
                <strong v-for="(error, index) in errors.volume" :key="index">{{ error }}</strong>
              </span>
            </div>
            <div class="form-group text-right">
              <button class="btn btn-primary">{{ __('Create target') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
export default {
  mounted() {
    let app = this;
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    loader.hide();
  },
  data: function() {
    return {
      isLoading: true,
      fullPage: true,
      multiplicities: [],
      stores: [],
      products: [],
      errors: [],
      target: {
        store: null,
        volume: null,
        remain: null,
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
    saveForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .post(
          "/api/v1/targets?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          app.target
        )
        .then(function(resp) {
          app.target = resp.data;
          app.$router.replace(
            "/personal/auctions/bid" +
              "?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token
          );
          loader.hide();
          return true;
        })
        .catch(function(errors) {
          app.errors = errors.response.data.errors;
          loader.hide();
        });
    }
  }
};
</script>