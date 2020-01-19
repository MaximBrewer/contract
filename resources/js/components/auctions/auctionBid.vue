<template>
  <section>
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
    <div class="container-fluid" v-if="auctions.length">
      <div class="row justify-content-end">
        <div class="col-lg-6">
          <div class="table-responsive" id="tergets" v-if="targets.length">
            <div class="h2 text-center">{{ __('My Targets') }}</div>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Product') }}</th>
                  <th>{{ __('Volume') }}</th>
                  <th>{{ __('Remain') }}</th>
                  <th></th>
                  <!-- <th>{{ __('Remain') }}</th>
              <th>{{ __('Multiplicity') }}</th>
              <th>{{ __('Federal district') }}</th>
              <th>{{ __('Region') }}</th>
                  <th>{{ __('Address') }}</th>-->
                </tr>
              </thead>
              <tbody>
                <tr v-for="target, index in targets">
                  <td width="1">{{ index + 1 }}</td>
                  <td>{{ target.product.title }}</td>
                  <td>{{ target.volume }}</td>
                  <td>{{ target.remain }}</td>
                  <!-- <td>{{ target.renain }}</td>
              <td
                v-if="target.multiplicity && target.multiplicity.title"
              >{{ target.multiplicity.title }}</td>
              <td v-else></td>
              <td
                v-if="target.store && target.store.federal_district && target.store.federal_district.title"
              >{{ target.store.federal_district.title }}</td>
              <td v-else></td>
              <td
                v-if="target.store && target.store.region && target.store.region.title"
              >{{ target.store.region.title }}</td>
              <td v-else></td>
              <td v-if="target.store && target.store.address">{{ target.store.address }}</td>
                  <td v-else></td>-->
                  <td width="1">
                    <div class="btn-group btn-group-sm" role="group">
                      <a
                        href="javascript:void(0)"
                        class="btn btn-secondary"
                        @click="showPopup('targets', target.id, 'target', index)"
                      >
                        <i class="mdi mdi-eye" aria-hidden="true"></i>
                      </a>
                      <router-link
                        class="btn btn-primary"
                        :to="{name: 'editTarget', 'params': {'id': target.id}}"
                      >
                        <i class="mdi mdi-pencil" aria-hidden="true"></i>
                      </router-link>
                      <a
                        href="javascript:void(0)"
                        class="btn btn-danger"
                        @click="deleteTarget(target.id)"
                      >
                        <i class="mdi mdi-delete" aria-hidden="true"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-sm-6 col-md-5th">
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
            <div class="col-md-5th col-sm-6">
              <div class="form-group">
                <label class="control-label">{{ __('Region') }}</label>
                <v-select
                  label="title"
                  :searchable="false"
                  @input="filterAuctionsAuctions"
                  :options="regions"
                  v-model="filter.region"
                ></v-select>
              </div>
            </div>
            <div class="col-md-5th col-sm-6">
              <div class="form-group">
                <label class="control-label">{{ __('Product') }}</label>
                <v-select
                  label="title"
                  :searchable="false"
                  @input="filterAuctionsAuctions"
                  :options="products"
                  v-model="filter.product"
                ></v-select>
              </div>
            </div>
            <div class="col-md-5th col-sm-6">
              <div class="form-group">
                <label class="control-label">{{ __('Multiplicity') }}</label>
                <v-select
                  label="title"
                  :searchable="false"
                  @input="filterAuctionsAuctions"
                  :options="multiplicities"
                  v-model="filter.multiplicity"
                ></v-select>
              </div>
            </div>
            <div class="col-md-5th col-sm-6">
              <div class="form-group">
                <label class="control-label">{{ __('Sort by distance from store') }}</label>
                <v-select
                  label="address"
                  :searchable="false"
                  @input="sorByDistanceAuctions"
                  :options="stores"
                  v-model="store"
                ></v-select>
              </div>
            </div>
          </div>
          <br />
          <div class="table-responsive" id="auctions">
            <table class="table table-bordered">
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
                  <th>{{ auction.id }}</th>
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
                    <span>{{ auction.comment }}</span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
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
      </div>
    </div>
    <modal name="target" height="auto" :adaptive="true" width="90%" :maxWidth="maxModalWidth">
      <div class="modal-header" v-if="modal_target">
        <h5 class="modal-title" v-if="modal_target.product">
          <strong>{{ __('Target') }}:</strong>
          {{ modal_target.product.title }}
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('target')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" v-if="modal_target">
        <p v-if="modal_target.store && modal_target.store.federal_district">
          <strong>{{ __('Target federal district title') }}:</strong>
          {{ modal_target.store.federal_district.title }}
        </p>
        <p v-if="modal_target.store && modal_target.store.region">
          <strong>{{ __('Target region title') }}:</strong>
          {{ modal_target.store.region.title }}
        </p>
        <p v-if="modal_target.store">
          <strong>{{ __('Target store address') }}:</strong>
          {{ modal_target.store.address }}
        </p>
        <p v-if="modal_target.store">
          <strong>{{ __('Target store coords') }}:</strong>
          {{ modal_target.store.coords }}
        </p>
        <p>
          <strong>{{ __('Target volume') }}:</strong>
          {{ modal_target.volume }}
        </p>
      </div>
      <div class="modal-footer" v-if="modal_target">
        <router-link
          class="btn btn-primary"
          :to="{name: 'editTarget', 'params': {id: modal_target.id}}"
        >{{ __('Edit') }}</router-link>
        <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="$modal.hide('target');deleteTarget(modal_target.id, index)"
        >{{ __('Delete') }}</button>
      </div>
    </modal>
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
      targets: [],
      modal_target: null,
      modal_auction: null,
      maxModalWidth: 600,
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
    let app = this,
    contragent_id = app.user.contragents[0].id,
    action = "bid";
    app.isLoading = true;
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
        app.isLoading = false;
        app.getMultiplicities();
        app.getProducts();
        app.getStores();
        app.getFederalDistricts();
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load auctions"));
        app.isLoading = false;
      });
    axios
      .get(
        "/api/v1/targets/?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.targets = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load targets"));
      });
  },
  methods: {
    showPopup(controller, id, template, index) {
      var app = this;
      axios
        .get(
          "/api/v1/" +
            controller +
            "/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.modal_target = resp.data;
          app.modal_target.index = index;
          app.$modal.show("target");
        });
    },
    deleteTarget(id, index) {
      var app = this;
      this.$confirm(this.__("Are you sure?")).then(() => {
        axios
          .delete(
            "/api/v1/targets/" +
              id +
              "?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token
          )
          .then(function(resp) {
            if (resp.data) app.targets.splice(index, 1);
            else
              app.$fire({
                title: app.__("Failed to delete target"),
                type: "error",
                timer: 3000
              });
          });
      });
    },
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
    sorByDistanceAuctions() {
      this.sorByDistance(this.auctions);
    },
    sorByDistance(auctions) {
      let app = this;
      if (!app.store || !app.store.coords) return false;
      let coords = app.store.coords.split(" ");
      if (coords.length < 2) return true;
      auctions.sort(function(a, b) {
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
      this.filterAuctionsAuctions();
    },
    filterAuctionsAuctions() {
      this.filterAuctions(this.auctions);
    },
    filterAuctions(auctions) {
      var app = this;
      for (let v in auctions) {
        let a = auctions[v];
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
    unbidAuction(id) {
      var app = this;
      app.isLoading = true;
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
          app.sorByDistance(resp.data);
          app.filterAuctions(resp.data);
          app.auctions = resp.data;
          app.isLoading = false;
        })
        .catch(function(resp) {
          alert(app.__("Failed to bid auction"));
          app.isLoading = false;
        });
    },
    formatDate(indate) {
      let date = new Date(indate);
      return date.toLocaleString();
    }
  }
};
</script>