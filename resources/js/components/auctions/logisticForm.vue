<template>
  <div class="container">
    <form v-on:submit="saveForm()">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Title') }}</label>
            <input
              v-bind:class="{ 'is-invalid':  errors.title }"
              type="text"
              v-model="logistic.title"
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
              v-model="logistic.gosznak"
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
              v-model="logistic.purposes"
              :multiple="true"
              v-bind:class="{ 'is-invalid': errors['purpose.id'] }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors['purpose.id']">
              <span v-for="(error, index) in errors['purpose.id']" :key="index">{{ error }}</span>
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
              v-model="logistic.available_from"
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
              v-model="logistic.capacity"
              v-bind:class="{ 'is-invalid': errors['capacity.id'] }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors['capacity.id']">
              <span v-for="(error, index) in errors['capacity.id']" :key="index">{{ error }}</span>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Description') }}</label>
            <textarea
              style="min-height:100px;"
              v-model="logistic.description"
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
              :coords="logistic.coords && logistic.coords.split(',').length ? logistic.coords.split(',') : defaultCoords"
              v-bind:class="{ 'is-invalid': errors.location }"
            >
              <ymap-marker
                v-if="logistic.coords && logistic.coords.split(',').length"
                :coords="logistic.coords.split(',')"
                :marker-id="logistic.id * 1"
                :hint-content="logistic.address"
              />
            </yandex-map>
            <span role="alert" class="invalid-feedback" v-if="errors.location">
              <strong v-for="(error, index) in errors.location" :key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Federal district') }}</label>
            <v-select
              :disabled="true"
              label="title"
              :options="$root.federalDistricts"
              v-model="logistic.federal_district"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="form-group">
            <label class="control-label">{{ __('Region') }}</label>
            <v-select
              :disabled="true"
              label="title"
              :options="$root.regions"
              v-model="logistic.region"
            >
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
              v-model="logistic.address"
              class="form-control"
              ref="address"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="form-group text-right">
            <button class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import { yandexMap, ymapMarker } from "vue-yandex-maps";
export default {
  components: { yandexMap, ymapMarker },
  props: ["logistic"],
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
      errors: {}
    };
  },
  mounted: function() {},
  methods: {
    saveForm() {
      event.preventDefault();
      let app = this;
      app.errors = {};
      let tr = true;

      if (!app.logistic.purposes.length) {
        app.errors["purpose.id"] = [app.__("Field is required!")];
        tr = false;
      }
      if (!app.logistic.capacity) {
        app.errors["capacity.id"] = [app.__("Field is required!")];
        tr = false;
      }
      if (!app.logistic.federal_district) {
        app.errors.location = [app.__("Enter vehicle location on the map!")];
        tr = false;
      }
      if (!app.logistic.region) {
        app.errors.location = [app.__("Enter vehicle location on the map!")];
        tr = false;
      }

      if (!tr) return false;

      let loader = Vue.$loading.show();

      if (app.logistic.id)
        axios
          .patch("/web/v1/logistics/" + app.logistic.id, app.logistic)
          .then(function(res) {
            app.$fire({
              title: app.__("Success!"),
              text: app.__("The vehicle was updated successfully!"),
              type: "success",
              timer: 5000
            });
            if (res.data.user) {
            // console.log(res.data);
              app.$root.user.contragents[0].balance = res.data.user.contragents[0].balance;
              app.$root.company.balance = res.data.user.contragents[0].balance;
            }
            app.$router.replace("/personal/logistics/");
            loader.hide();
          })
          .catch(function(err) {
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
          .post("/web/v1/logistics", app.logistic)
          .then(function(res) {
            app.$fire({
              title: app.__("Success!"),
              text: app.__("The vehicle was added successfully!"),
              type: "success",
              timer: 5000
            });
            app.$router.replace("/personal/logistics/");
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
          if (!logistic.coords.split(",").length)
            obj.geoObjects.add(result.geoObjects);
        });
      obj.events.add("click", function(e) {
        var coords = e.get("coords");
        var address = e.get("address");
        ymaps.geocode(coords).then(function(res) {
          app.logistic.address = res.geoObjects.get(0).getAddressLine();
          let address = app.logistic.address.split(",");
          if (address.length) {
            axios
              .post("/web/v1/address", { address: address })
              .then(function(resp) {
                // if (resp.data[0]) {
                // console.log(resp.data);
                app.logistic.region = resp.data[0];
                app.logistic.federal_district = resp.data[1];
                // }
              });
          }
        });
        obj.geoObjects.removeAll().add(new ymaps.Placemark(coords));
        app.logistic.coords = coords.join(",");
      });
    }
  }
};
</script>