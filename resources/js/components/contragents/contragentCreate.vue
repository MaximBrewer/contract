<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <form v-on:submit="saveForm()">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent title') }}</label>
              <input
                v-bind:class="{ 'is-invalid':  errors.title }"
                type="text"
                v-model="contragent.title"
                class="form-control"
                ref="title"
              />
              <div role="alert" class="invalid-feedback" v-if="errors.title">
                <span v-for="error in errors.title">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent federal district') }}</label>
              <v-select
                v-bind:class="{ 'is-invalid': errors['federal_district.id'] }"
                label="title"
                :options="federalDistricts"
                v-model="contragent.federal_district"
              ></v-select>
              <div role="alert" class="invalid-feedback" v-if="errors['federal_district.id']">
                <span v-for="error in errors['federal_district.id']">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent region') }}</label>
              <v-select
                v-bind:class="{ 'is-invalid': errors['region.id'] }"
                label="title"
                :options="regions"
                v-model="contragent.region"
                ref="region"
              ></v-select>
              <div role="alert" class="invalid-feedback" v-if="errors['region.id']">
                <span v-for="error in errors['region.id']">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent type') }}</label>
              <v-select
                v-bind:class="{ 'is-invalid': errors['types.id'] }"
                label="title"
                :options="types"
                v-model="contragent.types"
                :multiple="true"
                ref="types"
              ></v-select>
              <div role="alert" class="invalid-feedback" v-if="errors['types.id']">
                <span v-for="error in errors['types.id']">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent TIN') }}</label>
              <input
                v-bind:class="{ 'is-invalid': errors.inn }"
                type="text"
                v-model="contragent.inn"
                class="form-control"
                ref="inn"
              />
              <div role="alert" class="invalid-feedback" v-if="errors.inn">
                <span v-for="error in errors.inn">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent Legal address') }}</label>
              <input
                v-bind:class="{ 'is-invalid': errors.legal_address }"
                type="text"
                v-model="contragent.legal_address"
                class="form-control"
                ref="legal_address"
              />
              <div role="alert" class="invalid-feedback" v-if="errors.legal_address">
                <span v-for="error in errors.legal_address">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent contact') }}</label>
              <input
                v-bind:class="{ 'is-invalid': errors.fio }"
                type="text"
                v-model="contragent.fio"
                class="form-control"
                ref="fio"
              />
              <div role="alert" class="invalid-feedback" v-if="errors.fio">
                <span v-for="error in errors.fio">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent phone') }}</label>
              <input
                v-bind:class="{ 'is-invalid': errors.phone }"
                type="text"
                v-model="contragent.phone"
                class="form-control"
                ref="phone"
              />
              <div role="alert" class="invalid-feedback" v-if="errors.phone">
                <span v-for="error in errors.phone">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent stores') }}</label>
              <div class="stores">
                <ul>
                  <li class="store" v-for="store, index in contragent.stores">
                    <input type="hidden" v-model="contragent.stores[index].id" class="form-control" />
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label
                            class="control-label"
                          >{{ __('Store coords #', {store: index + 1}) }}</label>
                          <input
                            type="text"
                            v-bind:class="{ 'is-invalid': errors.stores && errors.stores[index] && errors.stores[index].coords }"
                            v-model="contragent.stores[index].coords"
                            class="form-control"
                            :ref="'stores_'+index+'_coords'"
                          />
                          <div
                            role="alert"
                            class="invalid-feedback"
                            v-if="errors.stores && errors.stores[index] && errors.stores[index].coords"
                          >
                            <span v-for="error in errors.stores[index].coords">{{ error }}</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label
                            class="control-label"
                          >{{ __('Store address #', {store: index + 1}) }}</label>
                          <input
                            v-bind:class="{ 'is-invalid': errors.stores && errors.stores[index] && errors.stores[index].address }"
                            type="text"
                            v-model="contragent.stores[index].address"
                            class="form-control"
                            :ref="'stores_'+index+'_address'"
                          />
                          <div
                            role="alert"
                            class="invalid-feedback"
                            v-if="errors.stores && errors.stores[index] && errors.stores[index].address"
                          >
                            <span v-for="error in errors.stores[index].address">{{ error }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label
                            class="control-label"
                          >{{ __('Store federal district #', {store: index + 1}) }}</label>
                          <v-select
                            v-bind:class="{ 'is-invalid': errors.stores && errors.stores[index] && errors.stores[index]['federal_district.id'] }"
                            label="title"
                            :options="federalDistricts"
                            v-model="contragent.stores[index].federal_district"
                            :ref="'stores_'+index+'_federal_district'"
                          ></v-select>
                          <div
                            role="alert"
                            class="invalid-feedback"
                            v-if="errors.stores && errors.stores[index] && errors.stores[index]['federal_district.id']"
                          >
                            <span
                              v-for="error in errors.stores[index]['federal_district.id']"
                            >{{ error }}</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label
                            class="control-label"
                          >{{ __('Store region #', {store: index + 1}) }}</label>
                          <v-select
                            v-bind:class="{ 'is-invalid': errors.stores && errors.stores[index] && errors.stores[index]['region.id'] }"
                            label="title"
                            :options="federalDistricts"
                            v-model="contragent.stores[index].region"
                            :ref="'stores_'+index+'_region'"
                          ></v-select>
                          <div
                            role="alert"
                            class="invalid-feedback"
                            v-if="errors.stores && errors.stores[index] && errors.stores[index]['region.id']"
                          >
                            <span v-for="error in errors.stores[index]['region.id']">{{ error }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 text-right">
                        <a
                          href="javascript:void(0)"
                          class="btn btn-danger btn-sm"
                          v-on:click="deleteStore(index)"
                        >{{ __('Delete store') }}</a>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="text-right">
                  <a
                    href="javascript:void(0)"
                    class="btn btn-primary btn-sm"
                    v-on:click="addStore"
                  >{{ __('Add store') }}</a>
                </div>
              </div>
            </div>
            <div class="form-group text-right">
              <button class="btn btn-primary">{{ __('Save') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
import vSelect from "vue-select";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
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
      contragent: {},
      errors:{}
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
      app.isLoading = true;
      var newContragent = app.contragent;
      newContragent.federal_district_id = newContragent.federal_district.id;
      newContragent.region_id = newContragent.region.id;
      newContragent.typeIds = [];
      for (let t in newContragent.types)
        newContragent.typeIds.push(newContragent.types[t].id);
      axios
        .post(
          "/api/v1/contragents?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          newContragent
        )
        .then(function(resp) {
          app.contragent = resp.data;
          app.$router.replace(
            "/personal/contragents/show/" +
              app.contragent.id +
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