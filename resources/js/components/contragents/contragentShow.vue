<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header" v-if="contragent.title">{{ contragent.title }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="contragent.federal_district && contragent.federal_district.title"
              >{{ __('Contragent region') }}: {{ contragent.federal_district.title }}</li>
              <li
                class="list-group-item"
                v-if="contragent.region && contragent.region.title"
              >{{ __('Contragent federal district') }}: {{ contragent.region.title }}</li>
              <li class="list-group-item" v-if="contragent.types">{{ __('Contragent type') }}: 
                <span v-for="type, index in contragent.types">{{ type.title }}</span>
              </li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-6">
          <div class="card">
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="contragent.legal_address"
              >{{ contragent.legal_address }}</li>
              <li
                class="list-group-item"
                v-if="contragent.fio"
              >{{ contragent.fio }}</li>
              <li
                class="list-group-item"
                v-if="contragent.phone"
              >{{ contragent.phone }}</li>
              <li
                class="list-group-item"
                v-if="contragent.inn"
              >{{ __('Contragent TIN') }}: {{ contragent.inn }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>

      <div class="form-group">
        <label class="control-label">{{ __('Contragent TIN') }}</label>
        <div class="form-control">{{ contragent.inn }}</div>
      </div>
      <div class="form-group">
        <label class="control-label">{{ __('Contragent stores') }}</label>
        <div class="stores">
          <ul>
            <li class="store" v-for="store, index in contragent.stores">
              <div class="form-group">
                <label class="control-label">{{ __('Store coords #', {store: index + 1}) }}</label>
                <div class="form-control">{{ contragent.stores[index].coords }}</div>
                <label class="control-label">{{ __('Store address #', {store: index + 1}) }}</label>
                <div class="form-control">{{ contragent.stores[index].address }}</div>
                <label
                  class="control-label"
                >{{ __('Store federal district #', {store: index + 1}) }}</label>
                <div class="form-control">{{ contragent.stores[index].federal_district.title }}</div>
                <label class="control-label">{{ __('Store region #', {store: index + 1}) }}</label>
                <div class="form-control">{{ contragent.stores[index].region.title }}</div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <star-rating v-model="contragent.rating"></star-rating>
      <comment></comment>
    </div>
  </section>
</template>
<script>
import "vue-loading-overlay/dist/vue-loading.css";

import Loading from "vue-loading-overlay";
import vSelect from "vue-select";
import comment from "./comments.vue";
import StarRating from "vue-star-rating";

export default {
  components: {
    vSelect: vSelect,
    Loading: Loading,
    comment: comment,
    StarRating: StarRating
  },
  mounted() {
    let app = this;
    app.isLoading = true;
    let id = app.$route.params.id;
    app.contragentId = id;
    axios
      .get(
        "/api/v1/contragents/" +
          id +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.contragent = resp.data;
        app.isLoading = false;
      })
      .catch(function() {
        alert(app.__("Failed to load contragent"));
        app.isLoading = false;
      });
  },
  data: function() {
    return {
      isLoading: true,
      onCancel: false,
      fullPage: true,
      federalDistricts: [],
      types: [],
      regions: [],
      contragent: {
        title: "",
        federal_district: {
          title: ""
        },
        region: {
          title: ""
        }
      }
    };
  },
  methods: {
    saveForm() {
      event.preventDefault();
      var app = this;
      var newContragent = app.contragent;
      app.isLoading = true;
      if (newContragent.federal_district)
        newContragent.federal_district_id = newContragent.federal_district.id;
      if (newContragent.region)
        newContragent.region_id = newContragent.region.id;
      newContragent.typeIds = [];
      for (let t in newContragent.types)
        newContragent.typeIds.push(newContragent.types[t].id);
      axios
        .patch(
          "/api/v1/contragents/" +
            app.contragentId +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_tokens,
          newContragent
        )
        .then(function(resp) {
          app.contragent = resp.data;
          app.isLoading = false;
          //app.$router.replace("/");
          return true;
        })
        .catch(function(resp) {
          console.log(resp);
          alert(app.__("Failed to edit contragent"));
          app.isLoading = false;
        });
    }
  }
};
</script>