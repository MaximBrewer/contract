<template>
  <section class="container-fluid">
    <div class="row" v-if="action == 'my'">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <a
            href="javascript:void(0)"
            @click="createNew"
            class="btn btn-primary btn-lg"
          >{{ __('Create new auction') }}</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2 col-sm-3 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Federal district') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterGetRegions"
            :options="$root.federalDistricts"
            v-model="filter.federal_district"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Region') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.regions"
            v-model="filter.region"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Contragent') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.contragents"
            v-model="filter.contragent"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Product') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.products"
            v-model="filter.product"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Multiplicity') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.multiplicities"
            v-model="filter.multiplicity"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Confirmed') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.confirmedOptions"
            v-model="filter.confirmed"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Tags') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.tags"
            :multiple="true"
            v-model="filter.tags"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Sort by distance from store') }}</label>
          <v-select
            label="address"
            :searchable="false"
            @input="sorByDistance"
            :options="$root.stores"
            v-model="store"
          ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
        </div>
      </div>
    </div>
    <auctions-table :auctions="auctionsList" :action="action"></auctions-table>
  </section>
</template>
<script>
import auctionsTable from "./auctionsTable";
export default {
  components: {
    auctionsTable: auctionsTable
  },
  props: ["action"],
  mounted() {
    this.getAuctions();
  },
  data: function() {
    return {
      auctions: [],
      auctionsList: [],
      store: null,
      filter: {
        federal_district: null,
        product: null,
        multiplicity: null,
        region: null,
        confirmed: 0,
        tags: []
      }
    };
  },
  methods: {
    createNew() {
      let app = this;
      if (!!app.company.rating && app.company.rating > 2)
        app.$router.push("/personal/auctions/create");
      else
        app.$fire({
          title: app.__("Error!"),
          text: app.__(
            "You have a low rating, contact the administration or ask other Russian companies to write reviews about your company."
          ),
          type: "error"
        });
    },
    getAuctions() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/api/v1/auctions/" + app.action)
        .then(function(res) {
          app.auctions = res.data.data;
          app.filterAuctions();
          loader.hide();
        })
        .catch(function(res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load auctions"),
            type: "error",
            timer: 5000
          });
        });
    },
    filterGetRegions() {
      this.$root.getRegions(this.filter.federal_district.id);
      this.filterAuctions();
    },
    filterAuctions() {
      let app = this;
      app.auctionsList = [];
      let cnt = 0;
      let f = app.filter;
      for (let v in app.auctions) {
        ++cnt;
        let a = app.auctions[v];
        let intags = false;
        if (f.tags.length) {
          for (let g in f.tags) {
            for (let d in a.tags) {
              intags = intags ? intags : a.tags[d].id == f.tags[g].id;
            }
          }
        }
        if (
          (!f.federal_district ||
            f.federal_district.id == a.store.federal_district.id) &&
          (!f.region || f.region.id == a.store.region.id) &&
          (!f.product || f.product.id == a.product.id) &&
          (!f.multiplicity || f.multiplicity.id == a.multiplicity.id) &&
          (!f.confirmed || f.confirmed.id - 1 == a.confirmed) &&
          (!f.contragent || f.contragent.id == a.contragent.id) &&
          (!f.tags.length || intags)
        )
          app.auctionsList.push(a);
      }
      if (app.auctions.length == cnt) app.sorByDistance();
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