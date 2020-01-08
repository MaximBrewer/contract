<template>
  <section>
    <div class="table-responsive" id="auctions">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th></th>
            <th>{{ __('Contragent') }}</th>
            <th>{{ __('Product') }}</th>
            <th>{{ __('Multiplicity') }}</th>
            <th>{{ __('Federal district') }}</th>
            <th>{{ __('Region') }}</th>
            <th>{{ __('Address') }}</th>
            <th>{{ __('Start') }}</th>
            <th>{{ __('Finish') }}</th>
            <th>{{ __('Comment') }}</th>
            <th width="100">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="auction, index in auctions">
            <td>
              <button v-on:click="bidAuction(auction.id)" class="btn btn-danger">{{ __('Bid') }}</button>
            </td>
            <td>{{ auction.contragent.title }}</td>
            <td>{{ auction.product.title }}</td>
            <td>{{ auction.multiplicity.title }}</td>
            <td>{{ auction.store.federal_district.title }}</td>
            <td>{{ auction.store.region.title }}</td>
            <td>{{ auction.store.address }}</td>
            <td>{{ formatDate(auction.start_at) }}</td>
            <td>{{ formatDate(auction.finish_at) }}</td>
            <td>{{ auction.store.comment }}</td>
            <td>
              <div class="btn-group" role="group">
              <router-link
                :to="{name: 'editAuction', params: {id: auction.id}}"
                class="btn btn-sm btn-primary"
              >{{ __('Edit') }}</router-link>
              <a
                href="#"
                class="btn btn-sm btn-danger"
                v-on:click="deleteEntry(auction.id, index)"
              >{{ __('Delete') }}</a>
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
  data: function() {
    return {
      auctions: []
    };
  },
  mounted() {
    var app = this;
    axios
      .get("/api/v1/auctions")
      .then(function(resp) {
        app.auctions = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert( app.__("Failed to load auctions") );
      });
  },

  methods: {
    bidAuction(id){},
    formatDate(indate){
      let date = new Date(indate);
      return date.toLocaleString();
    },
    deleteEntry(id, index) {
      var app = this;
      if (confirm( app.__('Are you sure you want to delete the auction?') )) {
        axios
          .delete("/api/v1/auctions/" + id)
          .then(function(resp) {
            app.auctions.splice(index, 1);
          })
          .catch(function(resp) {
            alert( app.__('Failed to delete auction') );
          });
      }
    }
  }
};

</script>