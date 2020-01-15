<template>
  <section class="target-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()" v-if="target">
        <div class="form-group">
          <label class="control-label">{{ __('Target lot') }}</label>
          <v-select label="title" :options="products" v-model="target.product"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Target multiplicity') }}</label>
          <v-select label="title" :options="multiplicities" v-model="target.multiplicity"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Target store') }}</label>
          <v-select label="address" :options="stores" v-model="target.store"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Target volume') }}</label>
          <input type="text" v-model="target.volume" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Target remain') }}</label>
          <input type="text" v-model="target.remain" class="form-control" />
        </div>
        <div class="form-group">
          <button class="btn btn-primary btn-lg">{{ __('Update target') }}</button>
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
    app.isLoading = true;
    let id = app.$route.params.id;
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    axios
      .get(
        "/api/v1/targets/" +
          id +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.target = resp.data;
        app.isLoading = false;
      })
      .catch(function() {
        alert(app.__("Failed to load target"));
        app.isLoading = false;
      });
  },
  data: function() {
    return {
      isLoading: true,
      fullPage: true,
      multiplicities: [],
      stores: [],
      products: []
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
      var newTarget = app.target;
      axios
        .post(
          "/api/v1/targets?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          newTarget
        )
        .then(function(resp) {
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
        .catch(function(resp) {
          console.log(resp);
          alert(app.__("Failed to create target"));
          app.isLoading = false;
        });
    }
  }
};
</script>