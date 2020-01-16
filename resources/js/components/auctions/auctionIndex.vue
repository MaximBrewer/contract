<template>
  <section>
    <div class="container-fluid">
      <!--<loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>-->
      <div class="h2 text-center">{{ __('Upcoming auctions') }}</div>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Federal district') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterGetRegions"
              :options="federalDistricts"
              v-model="filter.federal_district"
            ></v-select>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Region') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctions"
              :options="regions"
              v-model="filter.region"
            ></v-select>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Product') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctions"
              :options="products"
              v-model="filter.product"
            ></v-select>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Multiplicity') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctions"
              :options="multiplicities"
              v-model="filter.multiplicity"
            ></v-select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Sort by distance from store') }}</label>
            <v-select
              label="address"
              :searchable="false"
              @input="sorByDistance"
              :options="stores"
              v-model="store"
            ></v-select>
          </div>
        </div>
      </div>
      <br />
      <div class="table-responsive" id="auctions" v-if="auctions.length">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('Auction') }}</th>
              <th>{{ __('Time') }}</th>
              <th>{{ __('Store') }}</th>
              <th>{{ __('Description') }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="auction, index in auctions" v-if="!auction.hidden">
              <th>{{ index + 1 }}</th>
              <td>
                <div v-if="auction.contragent" class="text-nowrap">
                  <strong v-if="false">{{ __('Contragent') }}:</strong>
                  <div class="h6">{{ auction.contragent.title }}</div>
                </div>
                <div v-if="auction.product" class="text-nowrap">
                  <strong>{{ __('Product') }}:</strong>
                  <span>{{ auction.product.title }}</span>
                </div>
                <div v-if="auction.multiplicity" class="text-nowrap">
                  <strong>{{ __('Multiplicity') }}:</strong>
                  <span>{{ auction.multiplicity.title }}</span>
                </div>
                <div class="text-nowrap">
                  <strong>{{ __('Volume') }}:</strong>
                  <span>{{ auction.volume }}</span>
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
                <strong>{{ __('Auction comment') }}:</strong>
                <br />
                <span>{{ auction.comment }}</span>
              </td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a
                    v-tooltip="__('Take part in the auction')"
                    v-if="!auction.bidder && user.contragents[0].id != auction.contragent.id"
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
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>
<script>
import { Datetime } from "vue-datetime";
import vSelect from "vue-select";
import Loading from "vue-loading-overlay";
export default {
  components: {
    vSelect: vSelect,
    Datetime: Datetime,
    Loading: Loading
  },
  data: function() {
    return {
      auctions: [],
      isLoading: false,
      fullPage: true,
      store: null,
      filter: {
        federal_district: null,
        product: null,
        multiplicity: null,
        region: null
      },
      federalDistricts: [],
      products: [],
      multiplicities: [],
      regions: [],
      stores: []
    };
  },
  mounted() {
    let app = this;
    app.getMultiplicities();
    app.getProducts();
    app.getStores();
    app.getFederalDistricts();
    app.isLoading = false;
    let contragent_id = app.user.contragents[0].id;
    let action = "all";
    axios
      .get(
        "/api/v1/auctions/" +
          action +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.auctions = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load auctions"));
      });
  },
  methods: {
    getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
      let app = this;
      var R = 6371; // Radius of the earth in km
      var dLat = app.deg2rad(lat2 - lat1); // deg2rad below
      var dLon = app.deg2rad(lon2 - lon1);
      var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(app.deg2rad(lat1)) *
          Math.cos(app.deg2rad(lat2)) *
          Math.sin(dLon / 2) *
          Math.sin(dLon / 2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      var d = R * c; // Distance in km
      return d;
    },
    deg2rad(deg) {
      return deg * (Math.PI / 180);
    },
    sorByDistance() {
      let app = this;
      if (!app.store || !app.store.coords) return false;
      let coords = app.store.coords.split(" ");
      if (coords.length < 2) return true;
      this.auctions.sort(function(a, b) {
        let as = a.store.coords.split(" ");
        let bs = b.store.coords.split(" ");
        if (as.length < 2) return false;
        if (bs.length < 2) return true;
        console.log(coords, as, bs);
        let arange = app.getDistanceFromLatLonInKm(
          coords[0].trim(),
          coords[1].trim(),
          as[0].trim(),
          as[1].trim()
        );
        let brange = app.getDistanceFromLatLonInKm(
          coords[0].trim(),
          coords[1].trim(),
          bs[0].trim(),
          bs[1].trim()
        );
        console.log(a.store.address);
        console.log(b.store.address);
        console.log(arange);
        console.log(brange);
        return arange - brange;
      });
    },
    filterGetRegions() {
      this.getRegions();
      this.filterAuctions();
    },
    filterAuctions() {
      var app = this;
      for (let v in app.auctions) {
        let a = app.auctions[v];
        let f = app.filter;
        let s = a.store;

        a.hidden =
          (f.federal_district &&
            f.federal_district.id != s.federal_district.id) ||
          (f.region && f.region.id != s.region.id) ||
          (f.product && f.product.id != a.product.id) ||
          (f.multiplicity && f.multiplicity.id != a.multiplicity.id);
      }
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
      if (!app.filter.federal_district) return [];
      axios
        .get(
          "/api/v1/regions?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token +
            "&federal_district_id=" +
            app.filter.federal_district.id
        )
        .then(function(resp) {
          app.regions = resp.data;
        });
    },
    getMultiplicities() {
      let app = this;
      axios
        .get(
          "/api/v1/multiplicities?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.multiplicities = resp.data;
        });
    },
    getStores() {
      let app = this;
      axios
        .get(
          "/api/v1/stores?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.stores = resp.data;
        });
    },
    getProducts() {
      let app = this;
      axios
        .get(
          "/api/v1/products?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.products = resp.data;
        });
    },
    bidAuction(id) {
      var app = this;
      axios
        .get(
          "/api/v1/auctions/all/bid/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.auctions = resp.data;
        })
        .catch(function(resp) {
          alert(app.__("Failed to bid auction"));
        });
    },
    unbidAuction(id) {
      var app = this;
      axios
        .get(
          "/api/v1/auctions/all/unbid/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.auctions = resp.data;
        })
        .catch(function(resp) {
          alert(app.__("Failed to bid auction"));
        });
    },
    formatDate(indate) {
      let date = new Date(indate);
      return date.toLocaleString();
    }
    // deleteEntry(id, index) {
    //   var app = this;
    //   if (confirm(app.__("Are you sure you want to delete the auction?"))) {
    //     axios
    //       .delete("/api/v1/auctions/" + id)
    //       .then(function(resp) {
    //         app.auctions.splice(index, 1);
    //       })
    //       .catch(function(resp) {
    //         alert(app.__("Failed to delete auction"));
    //       });
    //   }
    // }
  }
};
</script>