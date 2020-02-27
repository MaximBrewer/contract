<template>
  <div class="row">
    <div class="col-md-12">
      <div class="h4">{{ __("You are an auction participant") }}</div>
      <div class="row" v-if="!!can_bet && !auction.finished">
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">{{ __('Auction Volume') }}</label>
            <input
              type="number"
              v-model="bid.volume"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.volume }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.volume">
              <strong v-for="(error, index) in errors.volume" v-bind:key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">{{ __('Price') }}</label>
            <input
              type="number"
              v-model="bid.price"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.price }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.price">
              <strong v-for="(error, index) in errors.price" v-bind:key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">{{ __('Target store') }}</label>
            <v-select
              label="address"
              :options="$root.stores"
              v-model="bid.store"
              v-bind:class="{ 'is-invalid': errors['store.id'] }"
            ></v-select>
            <span role="alert" class="invalid-feedback" v-if="errors['store.id']">
              <strong v-for="(error, index) in errors['store.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <div>
              <label class="control-label">&nbsp;</label>
            </div>
            <button class="btn btn-primary" @click="betIt">{{ __('Bet') }}</button>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          {{ __("Auction activity") }}
          <strong>{{ auction.free_volume }}/{{ auction.volume }}</strong>
        </div>
        <div
          class="table-responsive"
          id="auction_activity"
          v-if="auction.bets && auction.bets.length && !!observer"
        >
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>{{ __('Active volume') }}</th>
                <th>{{ __('Active price') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(bet, index) in betsList"
                v-bind:key="index"
                v-bind:class="{ 'table-success': bet.contragent_id == company.id}"
              >
                <td>
                  <div v-if="bet.contragent" class="text-nowrap">
                    <div class="h6">{{ bet.volume }}</div>
                  </div>
                </td>
                <td>
                  <div v-if="bet.contragent" class="text-nowrap">
                    <div class="h6">{{ bet.price }}₽</div>
                  </div>
                </td>
                <td>
                  <div
                    v-if="!!bet.approved_volume && bet.contragent_id == company.id"
                    class="text-nowrap"
                  >
                    <div class="h6">{{ __('The volume of bet has approved') }}</div>
                  </div>
                  <div
                    v-if="!!bet.approved_contract && bet.contragent_id == company.id"
                    class="text-nowrap"
                  >
                    <div class="h6">{{ __('The contract has approved') }}</div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["auction", "can_bet", "observer"],
  data: function() {
    return {
      bid: {},
      errors: {},
      betsList: []
    };
  },
  mounted() {
    var app = this;
    app.auction.bets.forEach(bet => {
      if (bet.contragent_id == app.company.id || !bet.approved_contract)
        app.betsList.push(bet);
    });
  },
  methods: {
    betIt() {
      var app = this;
      if (app.auction)
        axios
          .post("/api/v1/auctions/bet", {
            auction: app.auction.id,
            bidder: app.user.contragents[0].id,
            volume: app.bid.volume,
            price: app.bid.price,
            store: app.bid.store ? app.bid.store.id : false
          })
          .then(function(resp) {
            app.$modal.hide("add_bidder");
          })
          .catch(function(err) {
            app.$fire({
              title: app.__("Error!"),
              text: err.response.data.message,
              type: "error",
              timer: 5000
            });
          });
    }
  }
};
</script>