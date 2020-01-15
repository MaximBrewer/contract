<template>
  <section class="target-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()" v-if="target">
        <div class="form-group">
          <label class="control-label">{{ __('Target lot') }}</label>
          <v-select
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
          <label class="control-label">{{ __('Target multiplicity') }}</label>
          <v-select
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
          <label class="control-label">{{ __('Target store') }}</label>
          <v-select
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
          <label class="control-label">{{ __('Target volume') }}</label>
          <input type="number" v-model="target.volume" class="form-control" v-bind:class="{ 'is-invalid': errors.volume }"/>
          <span role="alert" class="invalid-feedback" v-if="errors.volume">
            <strong v-for="error in errors.volume">{{ error }}</strong>
          </span>
        </div>
        <div class="form-group">
          <button class="btn btn-primary btn-lg">{{ __('Create target') }}</button>
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
          app.flash(app.__("Failed to create target"), "error", {
            timeout: 3000
          });
          app.isLoading = false;
        });
    }
  }
};
</script>