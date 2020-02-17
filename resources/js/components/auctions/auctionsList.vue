<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Federal district') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterGetRegions"
            :options="$root.federalDistricts"
            v-model="$root.filter.federal_district"
          ></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Region') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.regions"
            v-model="$root.filter.region"
          ></v-select>
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
            v-model="$root.filter.product"
          ></v-select>
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
            v-model="$root.filter.multiplicity"
          ></v-select>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-1">
        <div class="form-group">
          <label class="control-label">{{ __('Confirmed') }}</label>
          <v-select
            label="title"
            :searchable="false"
            @input="filterAuctions"
            :options="$root.confirmedOptions"
            v-model="$root.filter.confirmed"
          ></v-select>
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
          ></v-select>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="auctions">
      <div class="h2 text-center">{{ title }}</div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('Auction') }}</th>
            <th>{{ __('Time') }}</th>
            <th>{{ __('Store') }}</th>
            <th>{{ __('Description') }}</th>
            <th v-if="action != 'archive'">{{ __('Confirmed') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(auction, index) in auctionsList" :key="index">
            <th>{{ auction.id }}</th>
            <td>
              <div v-if="auction.contragent" class="text-nowrap">
                <strong v-if="false">{{ __('Contragent') }}:</strong>
                <div class="h6">{{ auction.contragent.title }}</div>
              </div>
              <div v-if="auction.contragent" class="text-nowrap">
                <strong>{{ __('Rating') }}:</strong> {{ auction.contragent.rating }}
              </div>
              <div v-if="auction.product" class="text-nowrap">
                <strong>{{ __('Product') }}:</strong>
                <span>{{ auction.product.title }}</span>
              </div>
              <div v-if="auction.multiplicity" class="text-nowrap">
                <strong>{{ __('Multiplicity') }}:</strong>
                <span>{{ auction.multiplicity.title }}</span>
              </div>
              <div class="text-nowrap" v-if="auction.volume">
                <strong>{{ __('Volume') }}:</strong>
                <span>{{ auction.volume }}</span>
              </div>
              <div class="text-nowrap" v-if="auction.range != undefined && auction.range != 10000 && store">
                <strong>{{ __('Range') }}:</strong>
                <span>{{ auction.range * 1 }} {{ __('km') }}</span>
              </div>
            </td>
            <td>
              <div class="text-nowrap">
                <strong>{{ __('Auction start price') }}:</strong>
                <span>{{ auction.start_price }}₽</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __('Auction step') }}:</strong>
                <span>{{ auction.step }}₽</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __('Auction start') }}:</strong>
                <span>{{ auction.start_at | formatDateTime }}</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __('Auction finish') }}:</strong>
                <span>{{ auction.finish_at | formatDateTime }}</span>
              </div>
            </td>
            <td>
              <div v-if="auction.store && auction.store.federal_district" class="text-nowrap">
                <strong>{{ __('Auction store federal district') }}:</strong>
                <span>{{ auction.store.federal_district.title }}</span>
              </div>
              <div v-if="auction.store && auction.store.region" class="text-nowrap">
                <strong>{{ __('Auction store region') }}:</strong>
                <span>{{ auction.store.region.title }}</span>
              </div>
              <div v-if="auction.store">
                <strong>{{ __('Auction store address') }}:</strong>
                <span>{{ auction.store.address }}</span>
              </div>
              <div v-if="auction.store && false" class="text-nowrap">
                <strong>{{ __('Auction store coords') }}:</strong>
                <br />
                <span>{{ auction.store.coords }}</span>
              </div>
            </td>
            <td>
              <span>{{ auction.comment }}</span>
            </td>
            <td v-if="action != 'archive'">
              <span
                v-tooltip="auction.confirmed ? __('Confirmed') : __('Not confirmed')"
                class="online"
                v-bind:class="{ 'is-online': auction.confirmed, 'is-offline': !auction.confirmed }"
              ></span>
            </td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <a
                  v-tooltip="__('Copy the auction')"
                  v-if="company.id == auction.contragent.id"
                  href="javascript:void(0)"
                  class="btn btn-primary"
                  @click="copyAuction(auction.id)"
                >
                  <i class="mdi mdi-content-copy" aria-hidden="true"></i>
                </a>
                <a
                  v-tooltip="__('Take part in the auction')"
                  v-if="!auction.bidder && company.id != auction.contragent.id"
                  href="javascript:void(0)"
                  class="btn btn-success"
                  @click="bidAuction(auction.id)"
                >
                  <i class="mdi mdi-account-plus" aria-hidden="true"></i>
                </a>
                <a
                  v-tooltip="__('Unsubscribe from the auction')"
                  v-if="auction.bidder"
                  href="javascript:void(0)"
                  class="btn btn-danger"
                  @click="unbidAuction(auction.id)"
                >
                  <i class="mdi mdi-account-remove" aria-hidden="true"></i>
                </a>
                <router-link
                  v-tooltip="__('Go to auction page')"
                  :to="{name: 'showAuction', 'params': {'id': auction.id}}"
                  class="btn btn-secondary"
                >
                  <i class="mdi mdi-eye" aria-hidden="true"></i>
                </router-link>
                <router-link
                  v-if="company.id == auction.contragent.id"
                  v-tooltip="__('Edit auction')"
                  :to="{name: 'editAuction', 'params': {'id': auction.id}}"
                  class="btn btn-primary"
                >
                  <i class="mdi mdi-pencil" aria-hidden="true"></i>
                </router-link>
              </div>
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
  data: function() {
    return {
      auctions: [],
      auctionsList: [],
      store: null,
      title: ''
    };
  },
  mounted() {
    this.getAuctions();
    switch(this.action){
      case "my":
        this.title = this.__('My auctions')
        break;
      case "all":
        this.title = this.__('All auctions')
        break;
      case "bid":
        this.title = this.__('Bidder')
        break;
    }
  },
  methods: {
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
          console.log(res);
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
      this.$root.getRegions(this.$root.filter.federal_district.id);
      this.filterAuctions();
    },
    filterAuctions() {
      let app = this;
      app.auctionsList = [];
      let cnt = 0;
      let f = app.$root.filter;
      for (let v in app.auctions) {
        ++cnt;
        let a = app.auctions[v];
        console.log(f);
        if (
          (!f.federal_district ||
            f.federal_district.id == a.store.federal_district.id) &&
          (!f.region || f.region.id == a.store.region.id) &&
          (!f.product || f.product.id == a.product.id) &&
          (!f.multiplicity || f.multiplicity.id == a.multiplicity.id) &&
          (!f.confirmed || f.confirmed.id - 1 == a.confirmed)
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
    },
    bidAuction(id) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/api/v1/auctions/" + app.action + "/bid/" + id)
        .then(function(res) {
          app.auctions = res.data.data;
          app.filterAuctions();
          loader.hide();
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Failed to bid auction"),
            type: "error",
            timer: 2000
          });
          loader.hide();
        });
    },
    unbidAuction(id) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/api/v1/auctions/" + app.action + "/unbid/" + id)
        .then(function(res) {
          app.auctions = res.data.data;
          app.filterAuctions();
          loader.hide();
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Failed to unbid auction"),
            type: "error",
            timer: 2000
          });
          loader.hide();
        });
    },
    copyAuction(id) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/api/v1/auction/copy", { id: id })
        .then(function(res) {
          loader.hide();
          app.$router.push("/personal/auctions/edit/" + res.data.id);
        })
        .catch(function(err) {
          console.log(err);
          app.$fire({
            title: app.__("Error!"),
            text: err.response ? err.response.data.message : "",
            type: "error",
            timer: 2000
          });
          loader.hide();
        });
    }
  }
};
</script>