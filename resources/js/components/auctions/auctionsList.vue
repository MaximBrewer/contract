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
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :placeholder="__('Federal district')"
            :searchable="true"
            :disabled="!canaction"
            @input="filterGetRegions"
            :options="$root.federalDistricts"
            v-model="filter.federal_district"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :placeholder="__('Region')"
            :searchable="true"
            :disabled="!canaction"
            @input="filterAuctions"
            :options="$root.regions"
            v-model="filter.region"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :placeholder="__('Contragent')"
            :searchable="true"
            :disabled="!canaction"
            @input="filterAuctions"
            :options="$root.contragents"
            v-model="filter.contragent"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-4 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :placeholder="__('Product')"
            :searchable="true"
            :disabled="!canaction"
            @input="filterAuctions"
            :options="$root.products"
            v-model="filter.product"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-4 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :searchable="true"
            :placeholder="__('Multiplicity')"
            :disabled="!canaction"
            @input="filterAuctions"
            :options="$root.multiplicities"
            v-model="filter.multiplicity"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <datetime
            type="date"
            zone="Europe/Moscow"
            :placeholder="__('Start at from')"
            :disabled="!canaction"
            value-zone="Europe/Moscow"
            class="theme-primary"
            :input-class="'form-control'"
            @input="filterAuctions"
            v-model="filter.start_at"
          ></datetime>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <datetime
            type="date"
            zone="Europe/Moscow"
            :placeholder="__('to')"
            :disabled="!canaction"
            value-zone="Europe/Moscow"
            class="theme-primary"
            :input-class="'form-control'"
            @input="filterAuctions"
            v-model="filter.finish_at"
          ></datetime>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <datetime
            type="date"
            zone="Europe/Moscow"
            :placeholder="__('Interval From')"
            :disabled="!canaction"
            value-zone="Europe/Moscow"
            class="theme-primary"
            :input-class="'form-control'"
            @input="filterAuctions"
            v-model="filter.interval_from"
          ></datetime>
        </div>
      </div>
      <div class="col-md-2 col-sm-3 col-12">
        <div class="form-group">
          <datetime
            type="date"
            zone="Europe/Moscow"
            :placeholder="__('Interval To')"
            :disabled="!canaction"
            value-zone="Europe/Moscow"
            class="theme-primary"
            :input-class="'form-control'"
            @input="filterAuctions"
            v-model="filter.interval_to"
          ></datetime>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :searchable="false"
            :disabled="!canaction"
            :placeholder="__('Confirmed?')"
            @input="filterAuctions"
            :options="$root.confirmedOptions"
            :reduce="cod => cod.id"
            :cod="filter.confirmed"
            v-model="filter.confirmed"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-3 col-12">
        <div class="form-group">
          <v-select
            label="title"
            :placeholder="__('Tags')"
            :searchable="true"
            :disabled="!canaction"
            @input="filterAuctions"
            :options="$root.tags"
            :multiple="true"
            v-model="filter.tags"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-3 col-12">
        <div class="form-group">
          <v-select
            :searchable="false"
            :disabled="!canaction"
            :placeholder="__('Mode')"
            @input="filterAuctions"
            :options="[{code: 'future', label: 'срочный аукцион впрок'}, {code: 'price2day', label: 'price2day'}]"
            :reduce="cod => cod.code"
            :cod="filter.mode"
            :multiple="false"
            v-model="filter.mode"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
          <v-select
            label="address"
            :placeholder="__('Sort by distance from store')"
            :searchable="false"
            :disabled="!canaction"
            @input="sorByDistance"
            :options="$root.stores"
            v-model="store"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
    </div>
    <auctions-table :auctions="auctionsList" :action="action" :store="store"></auctions-table>
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
    if (this.action == "all") {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/auctions/confirmed")
        .then(function(res) {
          app.auctionsList = res.data.data;
          loader.hide();
          app.getAuctions(false);
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
    } else this.getAuctions(true);
  },
  data: function() {
    return {
      canaction: false,
      auctions: [],
      auctionsList: [],
      store: null,
      filter: {
        federal_district: null,
        product: null,
        multiplicity: null,
        region: null,
        confirmed: this.action == "all" ? 2 : null,
        start_at: null,
        finish_at: null,
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
    getAuctions(sl) {
      let app = this;
      let loader;
      if (sl) loader = Vue.$loading.show();
      axios
        .get("/web/v1/auctions/" + app.action)
        .then(function(res) {
          app.canaction = true;
          app.auctions = res.data.data;
          app.filterAuctions();
          if (sl) loader.hide();
        })
        .catch(function(res) {
          if (sl) loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load auctions"),
            type: "error",
            timer: 5000
          });
        });
    },
    filterGetRegions() {
      if (!this.canaction) return false;
      this.$root.getRegions(this.filter.federal_district.id);
      this.filterAuctions();
    },
    filterAuctions() {
      if (!this.canaction) return false;
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
        let interval = false;
        for (let d in a.intervals) {
          console.log(new Date(a.intervals[d].to).getTime() + 24 * 3600000);
          console.log(new Date(f.interval_from).getTime());
          if (
            (!f.interval_from || 
              new Date(a.intervals[d].to).getTime() + 24 * 3600000 >=
                new Date(f.interval_from).getTime()
                ) &&
            (!f.interval_to || a.intervals[d].from <= f.interval_to)
          ) {
            interval = true;
            break;
          }
        }
        // let interval_to = false;
        // if (f.interval_to) {
        //   for (let d in a.intervals) {
        //     if (
        //       a.intervals[d].from <= f.interval_to
        //       // new Date(f.interval_to).getTime() + 24 * 3600000 >=
        //       // new Date(a.intervals[d].from).getTime()
        //     )
        //       interval_to = true;
        //     break;
        //   }
        // }
        if (
          (!f.federal_district ||
            f.federal_district.id == a.store.federal_district.id) &&
          (!f.region || f.region.id == a.store.region.id) &&
          (!f.product || f.product.id == a.product.id) &&
          (!f.multiplicity || f.multiplicity.id == a.multiplicity.id) &&
          (!f.confirmed || f.confirmed - 1 == a.confirmed) &&
          (!f.contragent || f.contragent.id == a.contragent.id) &&
          (!f.can_bet || f.can_bet == a.can_bet) &&
          (!f.mode || f.mode == a.mode) &&
          (!f.start_at || f.start_at <= a.start_at) &&
          (!f.finish_at ||
            new Date(f.finish_at).getTime() + 24 * 3600000 >=
              new Date(a.start_at).getTime()) &&
          (!f.tags.length || intags) &&
          interval
        )
          app.auctionsList.push(a);
      }
      if (app.auctions.length == cnt) app.sorByDistance();
    },
    sorByDistance() {
      if (!this.canaction) return false;
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
      if (!this.canaction) return false;
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