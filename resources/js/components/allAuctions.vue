<template>
  <section>
    <div class="container-fluid" v-if="auctions.length">
      <div class="h2 text-center">{{ __('Upcoming auctions') }}</div>
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Federal district') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterGetRegions"
              :options="federalDistricts"
              v-model="filter.federal_district"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Region') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctionsAuctions"
              :options="regions"
              v-model="filter.region"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Product') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctionsAuctions"
              :options="products"
              v-model="filter.product"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <label class="control-label">{{ __('Multiplicity') }}</label>
            <v-select
              label="title"
              :searchable="false"
              @input="filterAuctionsAuctions"
              :options="multiplicities"
              v-model="filter.multiplicity"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
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
              <th>{{ __('Confirmed') }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="auction in _auctions" :key="'auction.' + auction.id">
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
                <div class="text-nowrap" v-if="auction.range">
                  <strong>{{ __('Range') }}:</strong>
                  <span>{{ auction.range }}</span>
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
                <span
                  v-tooltip="auction.confirmed ? __('Confirmed') : __('Not confirmed')"
                  class="online"
                  v-bind:class="{ 'is-online': auction.confirmed, 'is-offline': !auction.confirmed }"
                ></span>
              </td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a
                    v-tooltip="__('Take part in the auction')"
                    href="javascript:void(0)"
                    class="btn btn-success"
                    @click="loginDropdown"
                  >
                    <i class="mdi mdi-account-plus" aria-hidden="true"></i>
                  </a>
                  <a
                    v-tooltip="__('Unsubscribe from the auction')"
                    href="javascript:void(0)"
                    class="btn btn-danger"
                    @click="loginDropdown"
                  >
                    <i class="mdi mdi-account-remove" aria-hidden="true"></i>
                  </a>
                  <a
                    v-tooltip="__('Go to auction page')"
                    href="javascript:void(0)"
                    class="btn btn-secondary"
                    @click="loginDropdown"
                    target="_blank"
                  >
                    <i class="mdi mdi-eye" aria-hidden="true"></i>
                  </a>
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
export default {
  data: function() {
    return {
      auctions: [],
      _auctions: [],
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
      action = "all";
    let loader = Vue.$loading.show();
    axios
      .get("/auctions/all")
      .then(function(resp) {
        app.filterAuctions(resp.data.data);
        app.auctions = resp.data.data;
        loader.hide();
        app.$root.getFederalDistricts(app);
        app.$root.getRegions(
          app,
          app.filter.federal_district ? app.filter.federal_district.id : false
        );
        app.$root.getProducts(app);
        app.$root.getMultiplicities(app)
      })
      .catch(function(res) {
        loader.hide();
        alert(app.__("Failed to load auctions"));
      });
  },
  methods: {
      loginDropdown(e){
          $("#loginDropdown").attr('aria-expanded', true)
          $("#loginDropdown").parent().addClass('show')
          $("#loginDropdownMenu").addClass('show')
          e.stopPropagation()
          window.scrollTo(0, 0)
      },
    filterGetRegions() {
      var app = this;
      app.$root.getRegions(
        app,
        app.filter.federal_district ? app.filter.federal_district.id : false
      );
      this.filterAuctionsAuctions();
    },
    filterAuctionsAuctions() {
      this.filterAuctions(this.auctions);
    },
    filterAuctions(auctions) {
      var app = this;
      app._auctions = [];
      let cnt = 0;
      for (let v in auctions) {
        let a = auctions[v];
        let f = app.filter;
        let s = a.store;
        if (
          (!f.federal_district ||
            f.federal_district.id == s.federal_district.id) &&
          (!f.region || f.region.id == s.region.id) &&
          (!f.product || f.product.id == a.product.id) &&
          (!f.multiplicity || f.multiplicity.id == a.multiplicity.id)
        )
          app._auctions.push(a);
        ++cnt;
      }
    //   if (auctions.length == cnt) app.sorByDistance(app._auctions);
    },
    formatDate(indate) {
      let date = new Date(indate);
      return date.toLocaleString();
    }
  }
};
</script>