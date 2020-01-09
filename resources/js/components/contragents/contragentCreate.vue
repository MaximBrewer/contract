<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()">
        <div class="form-group">
          <label class="control-label">{{ __('Contragent title') }}</label>
          <input type="text" v-model="contragent.title" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent federal district') }}</label>
          <v-select label="title" :options="federalDistricts" v-model="contragent.federal_district"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent region') }}</label>
          <v-select label="title" :options="regions" v-model="contragent.region"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent type') }}</label>
          <v-select label="title" :options="types" v-model="contragent.types" :multiple="true"></v-select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent TIN') }}</label>
          <input type="text" v-model="contragent.inn" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent Legal address') }}</label>
          <input type="text" v-model="contragent.legal_address" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent contact') }}</label>
          <input type="text" v-model="contragent.fio" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent phone') }}</label>
          <input type="text" v-model="contragent.phone" class="form-control" />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent stores') }}</label>
          <div class="stores">
            <ul>
              <li class="store" v-for="store, index in contragent.stores">
                <div class="form-group">
                  <input type="hidden" v-model="contragent.stores[index].id" class="form-control" />
                  <label class="control-label">{{ __('Store coords #', {store: index + 1}) }}</label>
                  <input type="text" v-model="contragent.stores[index].coords" class="form-control" />
                  <label class="control-label">{{ __('Store address #', {store: index + 1}) }}</label>
                  <input
                    type="text"
                    v-model="contragent.stores[index].address"
                    class="form-control"
                  />
                  <label
                    class="control-label"
                  >{{ __('Store federal district #', {store: index + 1}) }}</label>
                  <v-select
                    label="title"
                    :options="federalDistricts"
                    v-model="contragent.stores[index].federal_district"
                  ></v-select>
                  <label class="control-label">{{ __('Store region #', {store: index + 1}) }}</label>
                  <v-select
                    label="title"
                    :options="regions"
                    v-model="contragent.stores[index].region"
                  ></v-select>
                  <br />
                  <a
                    href="javascript:void(0)"
                    class="btn btn-danger btn-sm"
                    v-on:click="deleteStore(index)"
                  >{{ __('Delete store') }}</a>
                </div>
              </li>
            </ul>
            <a
              href="javascript:void(0)"
              class="btn btn-primary btn-sm"
              v-on:click="addStore"
            >{{ __('Add store') }}</a>
          </div>
        </div>
        <div class="form-group">
          <button class="btn btn-primary btn-lg">{{ __('Save') }}</button>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
import vSelect from "vue-select";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect: vSelect,
    Loading: Loading
  },

  mounted() {
    let app = this;
    app.isLoading = true;
    app.getFederalDistricts();
    app.getRegions();
    app.getTypes();
    app.contragent = {
      title: "",
      inn: "",
      typeIds: [],
      fio: "",
      phone: "",
      legal_address: "",
      federal_district: 0,
      region: 0,
      types: [],
      stores: []
    };
    app.isLoading = false;
  },
  data: function() {
    return {
      isLoading: true,
      onCancel: false,
      fullPage: true,
      federalDistricts: [],
      types: [],
      regions: [],
      contragentId: null,
      contragent: {
        name: "",
        address: "",
        website: "",
        email: ""
      }
    };
  },
  methods: {
    addStore() {
      let app = this;
      app.contragent.stores.push({
        id: 0,
        coords: "",
        addres: ""
      });
    },
    deleteStore(index) {
      let app = this;
      app.contragent.stores.splice(index, 1);
    },
    getFederalDistricts() {
      let app = this;
      axios.get("/api/v1/federalDistricts").then(function(resp) {
        app.federalDistricts = resp.data;
      });
    },
    getRegions() {
      let app = this;
      axios
        .get("/api/v1/regions?", this.contragent.federal_district)
        .then(function(resp) {
          app.regions = resp.data;
        });
    },
    getTypes() {
      let app = this;
      axios.get("/api/v1/types").then(function(resp) {
        app.types = resp.data;
      });
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      app.isLoading = true;
      var newContragent = app.contragent;
      newContragent.federal_district_id = newContragent.federal_district.id;
      newContragent.region_id = newContragent.region.id;
      newContragent.typeIds = [];
      for (let t in newContragent.types)
        newContragent.typeIds.push(newContragent.types[t].id);
      axios
        .post("/api/v1/contragents", newContragent)
        .then(function(resp) {
          app.contragent = resp.data;
          app.$router.replace("/contragents/edit/" + app.contragent.id);
          app.isLoading = false;
          return true;
        })
        .catch(function(resp) {
          console.log(resp);
          alert(app.__("Failed to create contragent"));
          app.isLoading = false;
        });
    }
  }
};
</script>