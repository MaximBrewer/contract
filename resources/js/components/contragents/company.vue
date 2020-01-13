<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()">
        <div class="form-group">
          <label class="control-label">{{ __('Contragent title') }}</label>
          <input
            v-bind:class="{ 'is-invalid':  contragent.errors && contragent.errors.title }"
            type="text"
            v-model="contragent.title"
            class="form-control"
            ref="title"
          />
          <div role="alert" class="invalid-feedback" v-if="contragent.errors">
            <span v-for="error in contragent.errors.title">{{ error }}</span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent federal district') }}</label>
          <v-select
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            label="title"
            :options="federalDistricts"
            v-model="contragent.federal_district"
          ></v-select>
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent region') }}</label>
          <v-select
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            label="title"
            :options="regions"
            v-model="contragent.region"
            ref="region"
          ></v-select>
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent type') }}</label>
          <v-select
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            label="title"
            :options="types"
            v-model="contragent.types"
            :multiple="true"
            ref="types"
          ></v-select>
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent TIN') }}</label>
          <input
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            type="text"
            v-model="contragent.inn"
            class="form-control"
            ref="inn"
          />
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent Legal address') }}</label>
          <input
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            type="text"
            v-model="contragent.legal_address"
            class="form-control"
            ref="legal_address"
          />
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent contact') }}</label>
          <input
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            type="text"
            v-model="contragent.fio"
            class="form-control"
            ref="fio"
          />
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent phone') }}</label>
          <input
            v-bind:class="{ 'is-invalid': contragent.errors && contragent.errors.title }"
            type="text"
            v-model="contragent.phone"
            class="form-control"
            ref="phone"
          />
          <span role="alert" class="invalid-feedback"></span>
        </div>
        <div class="form-group">
          <label class="control-label">{{ __('Contragent stores') }}</label>
          <div class="stores">
            <ul>
              <li class="store" v-for="store, index in contragent.stores">
                <div class="form-group">
                  <input type="hidden" v-model="contragent.stores[index].id" class="form-control" />
                  <label class="control-label">{{ __('Store coords #', {store: index + 1}) }}</label>
                  <input
                    type="text"
                    v-model="contragent.stores[index].coords"
                    class="form-control"
                    :ref="'stores_'+index+'_coords'"
                  />
                  <span role="alert" class="invalid-feedback"></span>
                  <label class="control-label">{{ __('Store address #', {store: index + 1}) }}</label>
                  <input
                    type="text"
                    v-model="contragent.stores[index].address"
                    class="form-control"
                    :ref="'stores_'+index+'_address'"
                  />
                  <span role="alert" class="invalid-feedback"></span>
                  <label
                    class="control-label"
                  >{{ __('Store federal district #', {store: index + 1}) }}</label>
                  <v-select
                    label="title"
                    :options="federalDistricts"
                    v-model="contragent.stores[index].federal_district"
                    :ref="'stores_'+index+'_federal_district'"
                  ></v-select>
                  <span role="alert" class="invalid-feedback"></span>
                  <label class="control-label">{{ __('Store region #', {store: index + 1}) }}</label>
                  <v-select
                    label="title"
                    :options="regions"
                    v-model="contragent.stores[index].region"
                    :ref="'stores_'+index+'_region'"
                  ></v-select>
                  <span role="alert" class="invalid-feedback"></span>
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
    let id = app.user.contragents[0].id;

    if (!!app.$route.params.id) id = app.$route.params.id;
    app.getFederalDistricts();
    app.getRegions();
    app.getTypes();
    app.contragentId = id;
    axios
      .get(
        "/api/v1/company/?csrf_token=" +
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
      contragentId: null,
      contragent: {
        errors: {
          title: []
        }
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
      axios
        .get(
          "/api/v1/federalDistricts?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.federalDistricts = resp.data;
        });
    },
    getRegions() {
      let app = this;
      axios
        .get(
          "/api/v1/regions?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          this.contragent.federal_district
        )
        .then(function(resp) {
          app.regions = resp.data;
        });
    },
    getTypes() {
      let app = this;
      axios
        .get(
          "/api/v1/types?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.types = resp.data;
        });
    },
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
          "/api/v1/company/?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          newContragent
        )
        .then(function(resp) {
          if (resp.data.errors) {
            for (let g in resp.data.errors) {
              app.$refs[g].addClass("is-invalid");
            }
          } else {
            app.contragent = resp.data;
          }
          app.isLoading = false;
          //app.$router.replace("/");
          app.flash(app.__("Company edited successfully"), "error");
          return true;
        });
    }
  }
};
</script>