<template>
  <section class="target-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
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
                <strong v-for="error in errors['product.id']">{{ error }}</strong>
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
                <strong v-for="error in errors['multiplicity.id']">{{ error }}</strong>
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
                <strong v-for="error in errors['store.id']">{{ error }}</strong>
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
                <strong v-for="error in errors.volume">{{ error }}</strong>
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
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect: vSelect,
    Loading: Loading
  },
  mounted() {
    let app = this;
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    app.isLoading = false;
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
      app.isLoading = true;
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
          app.isLoading = false;
          return true;
        })
        .catch(function(errors) {
          app.errors = errors.response.data.errors;
          app.isLoading = false;
        });
    }
  }
};
</script>