<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header" v-if="contragent.title">
              <strong>{{ contragent.title }}</strong>
            </div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="contragent.federal_district && contragent.federal_district.title"
              >
                <strong>{{ __('Contragent region') }}:</strong>
                {{ contragent.federal_district.title }}
              </li>
              <li class="list-group-item" v-if="contragent.region && contragent.region.title">
                <strong>{{ __('Contragent federal district') }}:</strong>
                {{ contragent.region.title }}
              </li>
              <li class="list-group-item" v-if="contragent.types">
                <strong>{{ __('Contragent type') }}:</strong>
                <span v-for="(type, index) in contragent.types" :key="index">{{ type.title }}</span>
              </li>
              <li class="list-group-item" v-if="contragent.legal_address">
                <strong>{{ __('Contragent Legal address') }}:</strong>
                {{ contragent.legal_address }}
              </li>
              <li class="list-group-item" v-if="contragent.fio">
                <strong>{{ __('Contragent contact') }}:</strong>
                {{ contragent.fio }}
              </li>
              <li class="list-group-item" v-if="contragent.phone">
                <strong>{{ __('Contragent phone') }}:</strong>
                {{ contragent.phone }}
              </li>
              <li class="list-group-item" v-if="contragent.inn">
                <strong>{{ __('Contragent TIN') }}:</strong>
                {{ contragent.inn }}
              </li>
              </li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="h4">{{ __('Contragent stores') }}</div>
      <div class="row">
        <div class="col-md-6" v-for="(store, index) in contragent.stores" :key="index">
          <div class="card">
            <div class="card-header">{{ contragent.stores[index].address }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
              >{{ __('Store federal district #', {store: index + 1}) }}: {{ contragent.stores[index].federal_district.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Store region #', {store: index + 1}) }}: {{ contragent.stores[index].region.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Store coords #', {store: index + 1}) }}: {{ contragent.stores[index].coords }}</li>
            </ul>
          </div>
        </div>
      </div>
      <comment></comment>
    </div>
  </section>
</template>
<script>
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";
import vSelect from "vue-select";
import comment from "./comments.vue";

export default {
  components: {
    vSelect: vSelect,
    Loading: Loading,
    comment: comment
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