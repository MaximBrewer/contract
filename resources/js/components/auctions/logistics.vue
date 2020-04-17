<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <a
            href="javascript:void(0)"
            @click="createNew"
            class="btn btn-success btn-lg"
          >{{ __('Create new vehicle') }}</a>
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
            v-model="filter.purpose"
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
            label="address"
            :searchable="false"
            @input="sorByDistance"
            :options="$root.stores"
            v-model="store"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="logistics_table">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('компания тип объем контакты ') }}</th>
            <th>{{ __('локация фед. округ область адрес') }}</th>
            <th>{{ __('свободен начиная с') }}</th>
            <th>{{ __('Description') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(logistic, index) in logisticsList" :key="index">
            <td>{{ index + 1 }}</td>
            <td>
              {{ logistic.title }}
              <br />
              {{ logistic.gosznak }}
              <br />
              {{ logistic.purpose.title }}
              <br />
              {{ logistic.capacity.title }}
              <br />
              <a
                @click="showPhone(logistic)"
                href="javascript:void(0)"
                v-if="!logistic.phone"
              >{{ __('Show phone') }}</a>
              <span v-if="!!logistic.phone">{{ logistic.phone }}</span>
            </td>
            <td>
              {{ logistic.federal_district.title }}
              <br />
              {{ logistic.region.title }}
            </td>
            <td>{{ logistic.available_from }}</td>
            <td>{{ logistic.description }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal
      :scrollable="true"
      name="add_logistic"
      height="auto"
      :adaptive="true"
      width="90%"
      :maxWidth="maxModalWidth"
    >
      <logistic-modal :id="logisticId" />
    </modal>
  </section>
</template>
<script>
import LogisticModal from "./logisticModal";
export default {
  components: { LogisticModal },
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
      logisticId: null,
      logistics: [],
      logisticsList: [],
      store: null,
      filter: {
        federal_district: null,
        purpose: null,
        capacity: null,
        region: null,
        contragent: null,
        available_grom: null
      }
    };
  },
  methods: {
    createNew() {
      let app = this;
      if (true) {
        this.$modal.show("add_logistic");
        let app = this;
      } else
        app.$fire({
          title: app.__("Error!"),
          text: app.__(""),
          type: "error"
        });
    },
    filterGetRegions() {
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
        if (
          (!f.federal_district ||
            f.federal_district.id == a.store.federal_district.id) &&
          (!f.region || f.region.id == a.store.region.id) &&
          (!f.purpose || f.purpose.id == a.purpose.id) &&
          (!f.capacity || f.capacity.id == a.capacity.id) &&
          (!f.contragent || f.contragent.id == a.contragent.id) &&
          (!f.available_from || f.contragent.id == a.contragent.id)
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
      for (let i in app.auctionsList) {
        ++cnt;
        let as = app.auctionsList[i].store.coords.split(",");
        app.auctionsList[i].range = 10000;
        if (as.length < 2) continue;
        app.auctionsList[i].range =
          Math.round(
            app.getDistanceFromLatLonInKm(
              coords[0].trim(),
              coords[1].trim(),
              as[0].trim(),
              as[1].trim()
            ) * 100
          ) / 100;
      }
      if (cnt == app.auctionsList.length) {
        app.auctionsList.sort(function(a, b) {
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