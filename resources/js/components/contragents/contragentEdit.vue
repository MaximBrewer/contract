<template>
  <section class="contragent-edit-wrapper">
    <div class="container">
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
                <span v-for="(error, index) in errors.title" :key="index">{{ error }}</span>
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
                <span
                  v-for="(error, index) in errors['federal_district.id']"
                  :key="index"
                >{{ error }}</span>
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
                <span v-for="(error, index) in errors['region.id']" :key="index">{{ error }}</span>
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
                <span v-for="(error, index) in errors['types.id']" :key="index">{{ error }}</span>
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
                <span v-for="(error, index) in errors.inn" :key="index">{{ error }}</span>
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
                <span v-for="(error, index) in errors.legal_address" :key="index">{{ error }}</span>
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
                <span v-for="(error, index) in errors.fio" :key="index">{{ error }}</span>
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
                <span v-for="(error, index) in errors.phone" :key="index">{{ error }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">{{ __('Contragent stores') }}</label>
              <div class="stores">
                <ul>
                  <li class="store" v-for="(store, index) in contragent.stores" :key="index">
                    <input type="hidden" v-model="contragent.stores[index].id" class="form-control" />
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <store-map :store="store" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label
                            class="control-label"
                          >{{ __('Store coords #', {store: index + 1}) }}</label>
                          <input
                            readonly
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
                            <span
                              v-for="(error, index) in errors.stores[index].coords"
                              :key="index"
                            >{{ error }}</span>
                          </div>
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
                            <span
                              v-for="(error, index) in errors.stores[index].address"
                              :key="index"
                            >{{ error }}</span>
                          </div>
                        </div>
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
                              v-for="(error, index) in errors.stores[index]['federal_district.id']"
                              :key="index"
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
                            <span
                              v-for="(error, index) in errors.stores[index]['region.id']"
                              :key="index"
                              Í
                            >{{ error }}</span>
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
import storeMap from "../storeMap";
export default {
  components: {
    storeMap: storeMap
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    let id = app.user.contragents[0].id;
    if (!!app.$route.params.id) id = app.$route.params.id;
    app.federal_districts = app.$root.getFederalDistricts(app);
    app.types = app.$root.getTypes(app);
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
        loader.hide();
        app.regions = app.$root.getRegions(app);
      })
      .catch(function() {
        alert(app.__("Failed to load contragent"));
        loader.hide();
      });
  },
  data: function() {
    return {
      onCancel: false,
      fullPage: true,
      federalDistricts: [],
      types: [],
      regions: [],
      contragentId: null,
      contragent: {},
      errors: {}
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
    saveForm() {
      event.preventDefault();
      var app = this;
      var newContragent = app.contragent;
      let loader = Vue.$loading.show();
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
            window.api_token,
          newContragent
        )
        .then(function(resp) {
          app.contragent = resp.data;
          loader.hide();
          //app.$router.replace("/");
          return true;
        })
        .catch(function(resp) {
          console.log(resp);
          alert(app.__("Failed to edit contragent"));
          loader.hide();
        });
    }
  }
};
</script>