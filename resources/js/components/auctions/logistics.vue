<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <router-link
            v-tooltip="__('Create new vehicle')"
            :to="{name: 'createLogistic'}"
            class="btn btn-success btn-lg"
          >{{ __('Create new vehicle') }}</router-link>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Capacity') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterList"
            :options="$root.capacities"
            v-model="filter.capacity"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-5th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Federal district') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterGetRegions"
            :options="$root.federalDistricts"
            v-model="filter.federal_district"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-5th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Region') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.regions"
            v-model="filter.region"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-5th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Contragent') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.contragents"
            v-model="filter.contragent"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-5th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Available from') }}</label>
          <datetime
            type="date"
            zone="Europe/Moscow"
            value-zone="Europe/Moscow"
            class="theme-primary"
            input-class="form-control"
            v-model="filter.avilable_from"
          ></datetime>
        </div>
      </div>
      <div class="col-md-25th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Purpose') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.purposes"
            :multiple="true"
            v-model="filter.purposes"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-5th"></div>
      <div class="col-md-25th col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __('Sort by distance from store') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="sorByDistance"
            :options="$root.allStores"
            v-model="store"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="logistics_table">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('компания тип объем контакты ') }}</th>
            <th>{{ __('локация фед. округ область адрес') }}</th>
            <th>{{ __('свободен начиная с') }}</th>
            <th>{{ __('Description') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(logistic, index) in logisticsList" :key="index">
            <td>{{ index + 1 }}</td>
            <td>
              {{ logistic.contragent.title }}
              <br />
              {{ logistic.title }}
              <br />
              {{ logistic.gosznak }}
              <ul>
                <li v-for="(purpose, ind) in logistic.purposes" :key="ind">{{ purpose.title }}</li>
              </ul>
              {{ logistic.capacity.title }}
              <br />
              <a
                @click="showPhone(index)"
                href="javascript:void(0)"
                v-if="!logistic.phone"
              >{{ __('show phone') }}</a>
              <span v-if="!!logistic.phone">{{ logistic.phone }}</span>
            </td>
            <td>
              {{ logistic.federal_district.title }}
              <br />
              {{ logistic.region.title }}
              <br />
              {{ logistic.address }}
              <br />
              <span v-if="logistic.range">{{ logistic.range }} км</span>
            </td>
            <td>{{ logistic.available_from | formatDate }}</td>
            <td>{{ logistic.description }}</td>
            <td class="text-center">
              <router-link
                v-tooltip="__('Edit vehicle')"
                href="javascript:void(0)"
                class="btn btn-primary btn-sm"
                v-if="logistic.contragent.id == company.id"
                :to="{name: 'editLogistic', 'params': {'id': logistic.id}}"
              >
                <i class="mdi mdi-pencil" aria-hidden="true"></i>
              </router-link>
              <a
                v-tooltip="__('Delete vehicle')"
                href="javascript:void(0)"
                class="btn btn-danger btn-sm"
                v-if="logistic.contragent.id == company.id"
                @click="deleteLogistic(logistic)"
              >
                <i class="mdi mdi-delete" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
export default {
  props: ["action"],
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/logistics")
      .then(function(res) {
        app.logistics = res.data.data;
        app.filterList();
        loader.hide();
      })
      .catch(function(res) {
        loader.hide();
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load logistics"),
          type: "error",
          timer: 5000
        });
      });
  },
  data: function() {
    return {
      maxModalWidth: 800,
      logistic: {},
      logistics: [],
      logisticsList: [],
      store: null,
      filter: {
        federal_district: null,
        purposes: [],
        capacity: null,
        region: null,
        contragent: null,
        available_grom: null
      }
    };
  },
  methods: {
    showPhone(index) {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/show_phone/" + this.logisticsList[index].id)
        .then(function(res) {
          app.logistics = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function(err) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load logistics"),
            type: "error",
            timer: 5000
          });
        });
    },
    deleteLogistic(logistic) {
      var app = this;
      if (logistic)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios.delete("/web/v1/logistics/" + logistic.id).then(function(res) {
            app.logistics = res.data.data;
            app.filterList();
          });
        });
    },
    filterGetRegions() {
      if (this.filter.federal_district)
        this.$root.getRegions(this.filter.federal_district.id);
      this.filterList();
    },
    filterList() {
      let app = this;
      app.logisticsList = [];
      let cnt = 0;
      let f = app.filter;
      for (let v in app.logistics) {
        ++cnt;
        let a = app.logistics[v];
        let inpurposes = false;
        if (f.purposes.length) {
          for (let g in f.purposes) {
            for (let d in a.purposes) {
              inpurposes = inpurposes
                ? inpurposes
                : a.purposes[d].id == f.purposes[g].id;
            }
          }
        }
        if (
          (!f.federal_district ||
            f.federal_district.id == a.federal_district.id) &&
          (!f.region || f.region.id == a.region.id) &&
          (!f.purpose || f.purpose.id == a.purpose.id) &&
          (!f.capacity || f.capacity.id == a.capacity.id) &&
          (!f.contragent || f.contragent.id == a.contragent.id) &&
          (!f.available_from || f.contragent.id == a.contragent.id) &&
          (!f.purposes.length || inpurposes)
        )
          app.logisticsList.push(a);
      }
      if (app.logistics.length == cnt) app.sorByDistance();
    },
    sorByDistance() {
      let app = this;
      let store = app.store;
      if (!store || !store.coords) return false;
      let coords = store.coords.split(",");
      if (coords.length < 2) return true;
      let cnt = 0;
      for (let i in app.logisticsList) {
        ++cnt;
        let as = app.logisticsList[i].coords.split(",");
        app.logisticsList[i].range = 10000;
        if (as.length < 2) continue;
        app.logisticsList[i].range =
          Math.round(
            app.getDistanceFromLatLonInKm(
              coords[0].trim(),
              coords[1].trim(),
              as[0].trim(),
              as[1].trim()
            ) * 100
          ) / 100;
      }
      if (cnt == app.logisticsList.length) {
        app.logisticsList.sort(function(a, b) {
          return a.range - b.range;
        });
      }
    },
    getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
      var R = 6371; // Radius of the earth in km
      var dLat = this.deg2rad(lat2 - lat1); // deg2rad below
      var dLon = this.deg2rad(lon2 - lon1);
      var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(this.deg2rad(lat1)) *
          Math.cos(this.deg2rad(lat2)) *
          Math.sin(dLon / 2) *
          Math.sin(dLon / 2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      var d = R * c; // Distance in km
      return d;
    },
    deg2rad(deg) {
      return deg * (Math.PI / 180);
    }
  }
};
</script>