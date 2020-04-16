<template>
  <div class="container">
    <form v-on:submit="saveForm()" v-if="$root.target">
      <div class="row">
        <div class="col-md-6">
          <div class="jumbotron">
            <div class="h3">{{ __('You can add some description here!') }}</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <v-select
              :disabled="!!$route.params.id"
              :placeholder="__('Target lot')"
              label="title"
              :options="$root.products"
              v-model="$root.target.product"
              v-bind:class="{ 'is-invalid': $root.errors['product.id'] }"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
            <span role="alert" class="invalid-feedback" v-if="$root.errors['product.id']">
              <strong v-for="(error, index) in $root.errors['product.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <v-select
              :disabled="!!$route.params.id"
              :placeholder="__('Target multiplicity')"
              label="title"
              :options="$root.multiplicities"
              v-model="$root.target.multiplicity"
              v-bind:class="{ 'is-invalid': $root.errors['multiplicity.id'] }"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
            <span role="alert" class="invalid-feedback" v-if="$root.errors['multiplicity.id']">
              <strong
                v-for="(error, index) in $root.errors['multiplicity.id']"
                :key="index"
              >{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <v-select
              :disabled="!!$route.params.id"
              :placeholder="__('Target store')"
              label="address"
              :options="$root.stores"
              v-model="$root.target.store"
              v-bind:class="{ 'is-invalid': $root.errors['store.id'] }"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
            <span role="alert" class="invalid-feedback" v-if="$root.errors['store.id']">
              <strong v-for="(error, index) in $root.errors['store.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <input
              :placeholder="__('Target volume')"
              type="number"
              v-model="$root.target.volume"
              class="form-control"
              v-bind:class="{ 'is-invalid': $root.errors.volume }"
            />
            <span role="alert" class="invalid-feedback" v-if="$root.errors.volume">
              <strong v-for="(error, index) in $root.errors.volume" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <textarea
              :placeholder="__('Target description')"
              v-model="$root.target.description"
              class="form-control"
            ></textarea>
          </div>
          <div class="form-group text-right">
            <button class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
export default {
  created() {
    this.$root.errors = {};
  },
  methods: {
    saveForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();
      if (!!app.$route.params.id) {
        axios
          .patch("/web/v1/targets/" + app.$route.params.id, app.$root.target)
          .then(function(resp) {
            app.$router.replace("/personal/auctions/bid");
            loader.hide();
          })
          .catch(function(err) {
            app.$root.errors = err.response.data.errors;
            loader.hide();
          });
      } else {
        axios
          .post("/web/v1/targets", app.$root.target)
          .then(function(resp) {
            app.$router.replace("/personal/auctions/bid");
            loader.hide();
          })
          .catch(function(err) {
            app.$root.errors = err.response.data.errors;
            loader.hide();
          });
      }
    }
  }
};
</script>