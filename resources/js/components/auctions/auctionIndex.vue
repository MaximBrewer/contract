<template>
  <section>
    <div class="container-fluid">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <div class="h2 text-center">{{ __('Upcoming auctions') }}</div>
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
            <tr v-for="auction, index in auctions">
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
                    :title="__('Take part in the auction')"
                    v-if="!auction.bidder && user.contragents[0].id != auction.contragent.id"
                    href="javascript:void(0)"
                    class="btn btn-success"
                    @click="bidAuction(auction.id)"
                  >
                    <i class="mdi mdi-account-plus" aria-hidden="true"></i>
                  </a>
                  <a
                    :title="__('Unsubscribe from the auction')"
                    v-if="auction.bidder"
                    href="javascript:void(0)"
                    class="btn btn-danger"
                    @click="unbidAuction(auction.id)"
                  >
                    <i class="mdi mdi-account-remove" aria-hidden="true"></i>
                  </a>
                  <router-link
                    :title="__('Go to auction page')"
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
export default {
  data: function() {
    return {
      auctions: []
    };
  },
  mounted() {
    var app = this;
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