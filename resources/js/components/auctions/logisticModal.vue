<template>
  <section>
    <div class="modal-header">
      <h5 class="modal-title">
        <strong>{{ __('Add vehicle') }}</strong>
      </h5>
      <button
        type="button"
        class="close"
        @click="$modal.hide('add_logistic')"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Title') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  errors.title }"
              type="text"
              v-model="form.title"
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
            <label class="control-label">{{ __('Vehicle number') }}</label>
            <input
              v-bind:class="{ 'is-invalid': errors.gosznak }"
              type="text"
              v-model="form.gosznak"
              class="form-control"
              ref="gosznak"
            />
            <div role="alert" class="invalid-feedback" v-if="errors.gosznak">
              <span v-for="(error, index) in errors.gosznak" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Purpose') }}</label>
            <v-select
              label="title"
              :searchable="false"
              :options="$root.purposes"
              v-model="form.purpose"
              v-bind:class="{ 'is-invalid': errors.purpose_id }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors.purpose_id">
              <span v-for="(error, index) in errors.purpose_id" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Available from') }}</label>
            <datetime
              type="date"
              zone="Europe/Moscow"
              value-zone="Europe/Moscow"
              class="theme-primary"
              :input-class="errors.available_from ? 'form-control is-invalid': 'form-control'"
              v-model="form.available_from"
            ></datetime>
            <div role="alert" class="invalid-feedback" v-if="errors.available_from">
              <span v-for="(error, index) in errors.available_from" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Capacity') }}</label>
            <v-select
              label="title"
              :searchable="false"
              :options="$root.capacities"
              v-model="form.capacity"
              v-bind:class="{ 'is-invalid': errors.capacity_id }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors.capacity_id">
              <span v-for="(error, index) in errors.capacity_id" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Description') }}</label>
            <textarea
              style="min-height:100px;"
              v-model="form.description"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.description }"
            ></textarea>
            <span role="alert" class="invalid-feedback" v-if="errors['auction.description']">
              <strong v-for="(error, index) in errors.description" :key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <div role="alert" class="invalid-feedback" v-if="errors.coords">
              <span v-for="(error, index) in errors.coords" :key="index">{{ error }}</span>
            </div>
            <yandex-map
              @map-was-initialized="mapLoaded"
              zoom="8"
              :settings="settings"
              :controls="controls"
              :coords="form.coords && form.coords.split(',').length ? form.coords.split(',') : defaultCoords"
            >
              <ymap-marker
                v-if="form.coords && form.coords.split(',').length"
                :coords="form.coords.split(',')"
                :marker-id="form.id * 1"
                :hint-content="form.address"
              />
            </yandex-map>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Federal district') }}</label>
            <v-select
              :disabled="true"
              label="title"
              :options="$root.federalDistricts"
              v-model="form.federal_district"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Region') }}</label>
            <v-select :disabled="true" label="title" :options="$root.regions" v-model="form.region">
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">{{ __('Location') }}</label>
            <input
              v-bind:class="{ 'is-invalid': errors.address }"
              type="text"
              v-model="form.address"
              class="form-control"
              ref="address"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button
        type="button"
        class="btn btn-success"
        data-dismiss="modal"
        @click="addVehicle()"
      >{{ __('Add') }}</button>
    </div>
  </section>
</template>
<script>
import { yandexMap, ymapMarker } from "vue-yandex-maps";
export default {
  components: { yandexMap, ymapMarker },
  props: ["form"],
  data: function() {
    return {
      controls: ["searchControl"],
      settings: {
        apiKey: "a5c4997f-eb1b-4fee-bea6-fb5c83005b5a",
        lang: "ru_RU",
        coordorder: "latlong",
        version: "2.1"
      },
      defaultCoords: [55.75222, 37.61556],
      errors: {},
    };
  },
  mounted: function() {
    
  },
  methods: {
    addVehicle() {
      let app = this;
      let loader = Vue.$loading.show();
      if (
        !app.form.capacity ||
        !app.form.purpose ||
        !app.form.federal_district ||
        !app.form.region
      ) {
        app.$fire({
          title: app.__("Error!"),
          text: app.__("All required fields must be filled"),
          type: "error",
          timer: 5000
        });
        loader.hide();
        return false;
      }
      app.form.capacity_id = app.form.capacity.id;
      app.form.purpose_id = app.form.purpose.id;
      app.form.federal_district_id = app.form.federal_district.id;
      app.form.region_id = app.form.region.id;
      if (app.form.id)
        axios
          .patch("/web/v1/logistics/" + app.form.id, app.form)
          .then(function(res) {
            app.$parent.$parent.logistics = res.data.data;
            app.$parent.$parent.filterList();
            app.$fire({
              title: app.__("Success!"),
              text: app.__("The vehicle was updated successfully!"),
              type: "success",
              timer: 5000
            });
            app.$modal.hide("add_logistic");
            loader.hide();
          })
          .catch(function(err) {
            app.errors = {};
            if (!!err.response.data.errors)
              app.errors = err.response.data.errors;
            loader.hide();
            app.$fire({
              title: app.__("Error!"),
              text: app.__("Failed to load vehicle"),
              type: "error",
              timer: 5000
            });
          });
      else
        axios
          .post("/web/v1/logistics", app.form)
          .then(function(res) {
            app.$parent.$parent.logistics = res.data.data;
            app.$parent.$parent.filterList();
            app.$fire({
              title: app.__("Success!"),
              text: app.__("The vehicle was added successfully!"),
              type: "success",
              timer: 5000
            });
            app.$modal.hide("add_logistic");
            loader.hide();
          })
          .catch(function(err) {
            app.errors = {};
            if (!!err.response.data.errors)
              app.errors = err.response.data.errors;
            loader.hide();
            app.$fire({
              title: app.__("Error!"),
              text: app.__("Failed to load vehicle"),
              type: "error",
              timer: 5000
            });
          });
    },
    mapLoaded(obj) {
      var app = this,
        geolocation = ymaps.geolocation;
      geolocation
        .get({
          provider: "yandex",
          mapStateAutoApply: true
        })
        .then(function(result) {
          if (!form.coords.split(",").length)
            obj.geoObjects.add(result.geoObjects);
        });
      obj.events.add("click", function(e) {
        var coords = e.get("coords");
        var address = e.get("address");
        ymaps.geocode(coords).then(function(res) {
          app.form.address = res.geoObjects.get(0).getAddressLine();
          let address = app.form.address.split(",");
          if (address.length) {
            axios
              .post("/web/v1/address", { address: address })
              .then(function(resp) {
                // if (resp.data[0]) {
                console.log(resp.data);
                app.form.region = resp.data[0];
                app.form.federal_district = resp.data[1];
                // }
              });
          }
        });
        obj.geoObjects.removeAll().add(new ymaps.Placemark(coords));
        app.form.coords = coords.join(",");
      });
    }
  }
};
</script>