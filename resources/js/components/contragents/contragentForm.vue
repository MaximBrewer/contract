<template>
  <div class="container">
    <form v-on:submit="saveForm()">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent title') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  $root.errors.title }"
              type="text"
              v-model="$root.contragent.title"
              class="form-control"
              ref="title"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.title">
              <span v-for="(error, index) in $root.errors.title" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent federal district') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': $root.errors['federal_district.id'] }"
              label="title"
              :options="$root.federalDistricts"
              v-model="$root.contragent.federal_district"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="$root.errors['federal_district.id']">
              <span
                v-for="(error, index) in $root.errors['federal_district.id']"
                :key="index"
              >{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent region') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': $root.errors['region.id'] }"
              label="title"
              :options="$root.regions"
              v-model="$root.contragent.region"
              ref="region"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="$root.errors['region.id']">
              <span v-for="(error, index) in $root.errors['region.id']" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent type') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': $root.errors['types.id'] }"
              label="title"
              :options="$root.types"
              v-model="$root.contragent.types"
              :multiple="true"
              ref="types"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="$root.errors['types.id']">
              <span v-for="(error, index) in $root.errors['types.id']" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent TIN') }}</label>
            <input
              v-bind:class="{ 'is-invalid': $root.errors.inn }"
              type="text"
              :disabled="!!$root.contragent.id"
              v-model="$root.contragent.inn"
              class="form-control"
              ref="inn"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.inn">
              <span v-for="(error, index) in $root.errors.inn" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent Legal address') }}</label>
            <input
              v-bind:class="{ 'is-invalid': $root.errors.legal_address }"
              type="text"
              v-model="$root.contragent.legal_address"
              class="form-control"
              ref="legal_address"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.legal_address">
              <span v-for="(error, index) in $root.errors.legal_address" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent contact') }}</label>
            <input
              v-bind:class="{ 'is-invalid': $root.errors.fio }"
              type="text"
              v-model="$root.contragent.fio"
              class="form-control"
              ref="fio"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.fio">
              <span v-for="(error, index) in $root.errors.fio" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent phone') }}</label>
            <input
              v-bind:class="{ 'is-invalid': $root.errors.phone }"
              type="text"
              v-model="$root.contragent.phone"
              class="form-control"
              ref="phone"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.phone">
              <span v-for="(error, index) in $root.errors.phone" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent stores') }}</label>
            <div class="stores">
              <ul>
                <li class="store" v-for="(store, index) in $root.contragent.stores" :key="index">
                  <input type="hidden" v-model="store.id" class="form-control" />
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <store-map :store="store" />
                      </div>
                    </div>
                    <div class="col-md-6" v-tooltip="__('Choose place on map!')">
                      <div class="form-group">
                        <label class="control-label">{{ __('Store coords #', {store: index + 1}) }}</label>
                        <input
                          readonly
                          type="text"
                          v-bind:class="{ 'is-invalid': $root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index].coords }"
                          v-model="store.coords"
                          class="form-control"
                          :ref="'stores_'+index+'_coords'"
                        />
                        <div
                          role="alert"
                          class="invalid-feedback"
                          v-if="$root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index].coords"
                        >
                          <span
                            v-for="(error, index) in $root.errors.stores[index].coords"
                            :key="index"
                          >{{ error }}</span>
                        </div>
                        <label class="control-label">{{ __('Store address #', {store: index + 1}) }}</label>
                        <input
                          readonly
                          v-bind:class="{ 'is-invalid': $root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index].address }"
                          type="text"
                          v-model="store.address"
                          class="form-control"
                          :ref="'stores_'+index+'_address'"
                        />
                        <div
                          role="alert"
                          class="invalid-feedback"
                          v-if="$root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index].address"
                        >
                          <span
                            v-for="(error, index) in $root.errors.stores[index].address"
                            :key="index"
                          >{{ error }}</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label
                          class="control-label"
                        >{{ __('Store federal district #', {store: index + 1}) }}</label>
                        <v-select
                          :disabled="true"
                          v-bind:class="{ 'is-invalid': $root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index]['federal_district.id'] }"
                          label="title"
                          :options="$root.federalDistricts"
                          v-model="store.federal_district"
                          :ref="'stores_'+index+'_federal_district'"
                        >
                          <div slot="no-options">{{ __('No Options Here!') }}</div>
                        </v-select>
                        <div
                          role="alert"
                          class="invalid-feedback"
                          v-if="$root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index]['federal_district.id']"
                        >
                          <span
                            v-for="(error, index) in $root.errors.stores[index]['federal_district.id']"
                            :key="index"
                          >{{ error }}</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">{{ __('Store region #', {store: index + 1}) }}</label>
                        <v-select
                          :disabled="true"
                          v-bind:class="{ 'is-invalid': $root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index]['region.id'] }"
                          label="title"
                          :options="$root.regions"
                          v-model="store.region"
                          :ref="'stores_'+index+'_region'"
                        >
                          <div slot="no-options">{{ __('No Options Here!') }}</div>
                        </v-select>
                        <div
                          role="alert"
                          class="invalid-feedback"
                          v-if="$root.errors.stores && $root.errors.stores[index] && $root.errors.stores[index]['region.id']"
                        >
                          <span
                            v-for="(error, index) in $root.errors.stores[index]['region.id']"
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
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <label class="control-label"></label>
          <div class="form-group form-check">
            <input
              type="checkbox"
              v-model="$root.contragent.requisites"
              readonly
              class="form-check-input"
              id="contragent_requisites"
            />
            <label class="form-check-label" for="contragent_requisites">
              <h4>{{ __('Fill bank details') }}</h4>
            </label>
          </div>
        </div>
      </div>
      <div class="row" v-if="$root.contragent.requisites">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('KPP') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  $root.errors.kpp }"
              type="text"
              v-model="$root.contragent.kpp"
              class="form-control"
              ref="title"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.kpp">
              <span v-for="(error, index) in $root.errors.kpp" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Checking account') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  $root.errors.rs }"
              type="text"
              v-model="$root.contragent.rs"
              class="form-control"
              ref="title"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.rs">
              <span v-for="(error, index) in $root.errors.rs" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('BIC') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  $root.errors.bik }"
              type="text"
              v-model="$root.contragent.bik"
              class="form-control"
              ref="title"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.bik">
              <span v-for="(error, index) in $root.errors.bik" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('VAT') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': $root.errors['types.id'] }"
              :options="[{code: 'w0', label: 'без ндс'}, {code: 'w10', label: 'включая ндс 10'}, {code: 'w18', label: 'включая ндс 18'}, {code: 'w20', label: 'включая ндс 20'}]"
              :reduce="cod => cod.code"
              :cod="$root.contragent.nds"
              v-model="$root.contragent.nds"
              :searchable="false"
              :clearable="false"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="$root.errors.nds">
              <span v-for="(error, index) in $root.errors.nds" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">{{ __('Bank name') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  $root.errors.bank }"
              type="text"
              v-model="$root.contragent.bank"
              class="form-control"
              ref="title"
            />
            <div role="alert" class="invalid-feedback" v-if="$root.errors.bank">
              <span v-for="(error, index) in $root.errors.bank" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group text-right">
            <button class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import storeMap from "../storeMap";
export default {
  components: {
    storeMap: storeMap
  },
  created() {
    this.$root.errors = {};
  },
  methods: {
    addStore() {
      this.$root.contragent.stores.push({
        id: 0,
        coords: "",
        addres: ""
      });
    },
    deleteStore(index) {
      this.$root.contragent.stores.splice(index, 1);
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      app.$root.errors = {};
      let loader = Vue.$loading.show();
      var newContragent = app.$root.contragent;
      newContragent.federal_district_id = newContragent.federal_district.id;
      newContragent.region_id = newContragent.region.id;
      newContragent.typeIds = [];
      for (let t in newContragent.types)
        newContragent.typeIds.push(newContragent.types[t].id);

      if (app.$root.contragent.id) {
        axios
          .patch(
            "/web/v1/contragents/" + app.$root.contragent.id,
            newContragent
          )
          .then(function(res) {
            app.$root.contragent = res.data;
            app.$root.getMyStores();
            app.$fire({
              title: app.__("Company is successfully updated!"),
              type: "success",
              timer: 5000
            });
            loader.hide();
            return true;
          })
          .catch(function(err) {
            app.$root.errors = err.response.data.errors;
            loader.hide();
          });
      } else
        axios
          .post("/web/v1/contragents", newContragent)
          .then(function(res) {
            app.$root.contragent = res.data;
            app.$router.replace(
              "/personal/contragents/show/" + app.$root.contragent.id
            );
            loader.hide();
            return true;
          })
          .catch(function(err) {
            app.$root.errors = err.response.data.errors;
            loader.hide();
          });
    }
  }
};
</script>