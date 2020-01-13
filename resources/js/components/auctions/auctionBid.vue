<template>
  <section>
    <div class="container-fluid">
      <div class="table-responsive" id="auctions">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th></th>
              <th>{{ __('Contragent') }}</th>
              <th>{{ __('Product') }}</th>
              <th>{{ __('Start Price') }}</th>
              <th>{{ __('Volume') }}</th>
              <th>{{ __('Multiplicity') }}</th>
              <th>{{ __('Federal district') }}</th>
              <th>{{ __('Region') }}</th>
              <th>{{ __('Address') }}</th>
              <th>{{ __('Start') }}</th>
              <th>{{ __('Finish') }}</th>
              <th>{{ __('Comment') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="auction, index in auctions">
              <td>
                <button
                  v-if="!auction.bidder && user.contragents[0].id != auction.contragent.id"
                  v-on:click="bidAuction(auction.id)"
                  class="btn btn-danger"
                >{{ __('Bid') }}</button>
              </td>
              <td><router-link :to="{name: 'showAuction', 'params': {'id': auction.id}}" class="dropdown-item">{{ auction.contragent.title }}</router-link></td>
              <td>{{ auction.product.title }}</td>
              <td>{{ auction.start_price }} ₽</td>
              <td>{{ auction.volume }}</td>
              <td>{{ auction.multiplicity.title }}</td>
              <td>{{ auction.store.federal_district.title }}</td>
              <td>{{ auction.store.region.title }}</td>
              <td>{{ auction.store.address }}</td>
              <td>{{ formatDate(auction.start_at) }}</td>
              <td>{{ formatDate(auction.finish_at) }}</td>
              <td>{{ auction.comment }}</td>
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
    let action = "bid";
    axios
      .get("/api/v1/auctions/" + action + "?csrf_token=" + window.csrf_token + "&api_token=" + window.api_token)
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
          "/api/v1/auctions/bid/bid/" +
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
    },
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