<template>
  <div class="row">
    <div class="col-md-12">
      <div class="h4">{{ __("You are an auction participant") }}</div>
      <div class="row" v-if="!!can_bet && !auction.finished">
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">{{ __("Auction Volume") }}</label>
            <input
              type="number"
              v-model="bid.volume"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.volume }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.volume">
              <strong
                v-for="(error, index) in errors.volume"
                v-bind:key="index"
                >{{ error }}</strong
              >
            </span>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">{{ __("Price") }}</label>
            <input
              type="number"
              v-model="bid.price"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.price }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.price">
              <strong
                v-for="(error, index) in errors.price"
                v-bind:key="index"
                >{{ error }}</strong
              >
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">{{ __("Target store") }}</label>
            <v-select
              label="address"
              :options="$root.stores"
              v-model="bid.store"
              v-bind:class="{ 'is-invalid': errors['store.id'] }"
            >
              <div slot="no-options">{{ __("No Options Here!") }}</div>
            </v-select>
            <span
              role="alert"
              class="invalid-feedback"
              v-if="errors['store.id']"
            >
              <strong
                v-for="(error, index) in errors['store.id']"
                :key="index"
                >{{ error }}</strong
              >
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">{{ __("Interval") }}</label>
            <v-select
              label="label"
              :options="auction.intervals"
              v-model="bid.interval"
              v-bind:class="{ 'is-invalid': errors['auction.interval.id'] }"
            >
              <div slot="no-options">{{ __("No Options Here!") }}</div>
            </v-select>
            <span
              role="alert"
              class="invalid-feedback"
              v-if="errors['auction.interval.id']"
            >
              <strong
                v-for="(error, index) in errors['auction.interval.id']"
                :key="index"
                >{{ error }}</strong
              >
            </span>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <div>
              <label class="control-label">&nbsp;</label>
            </div>
            <button class="btn btn-primary" @click="betIt()">
              {{ __("Bet") }}
            </button>
          </div>
        </div>
      </div>
      <div class="row" v-for="(interval, ind) in auction.intervals" :key="ind">
        <div class="col-12">
          <div class="table-responsive auction_activity" v-if="!!observer">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th colspan="4">
                    {{ __("Interval start price") }}:
                    {{ interval.start_price }} | {{ __("Interval volume") }}:
                    {{ interval.volume }} | {{ __("Active volume") }}:
                    {{ interval.free_volume }} | {{ __("Approved volume") }}:
                    {{ interval.volume - interval.free_volume }} |
                    {{ __("Undistributed volume") }}:
                    {{ interval.undistributed_volume }} | {{ __("Interval") }}:
                    {{ interval.label }}
                  </th>
                </tr>
                <tr>
                  <th>{{ __("Volume") }}</th>
                  <th>{{ __("Price") }}</th>
                  <th>{{ __("Time") }}</th>
                  <th style="width:100%;"></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(bet, index) in filter(interval.bets)"
                  v-bind:key="index"
                  v-bind:class="{
                    'table-success': bet.contragent_id == company.id,
                  }"
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
                    <div v-if="bet.contragent" class="text-nowrap">
                      <div class="h6">
                        {{ bet.created_at | formatDateTimeBet }}
                      </div>
                    </div>
                  </td>
                  <td
                    v-if="
                      !bet.approved_volume || bet.contragent_id == company.id || bet.distributor_id == company.id
                    "
                  >
                    <div class="d-flex" v-if="bet.distributor">
                      <strong>{{ __("Совместно с ") + bet.distributor }}</strong>
                    </div>
                    <div class="d-flex">
                      <a
                        v-if="bet.contragent_id == company.id"
                        v-tooltip="__('Delete bet')"
                        href="javascript:void(0)"
                        class="btn-sm"
                        v-bind:class="{
                          'btn-danger': !auction.finished,
                          'btn-secondary': auction.finished,
                        }"
                        @click="
                          auction.finished
                            ? function () {
                                return false;
                              }
                            : removeBet(bet)
                        "
                      >
                        <i class="mdi mdi-delete" aria-hidden="true"></i> </a
                      >&nbsp;
                      <a
                        v-if="bet.contragent_id == company.id"
                        v-tooltip="
                          !bet.guarantee
                            ? __('я гарантирую приобрести данный объем')
                            : __('отозвать гарантию')
                        "
                        href="javascript:void(0)"
                        class="btn btn-sm d-block mr-2"
                        :disabled="!!auction.finished"
                        v-bind:class="{
                          'btn-success': !bet.guarantee,
                          'btn-danger': !!bet.guarantee,
                          'btn-secondary': auction.finished,
                        }"
                        @click="
                          auction.finished
                            ? function () {
                                return false;
                              }
                            : guarantee(bet)
                        "
                      >
                        <i class="mdi mdi-star" aria-hidden="true"></i>
                      </a>
                      <div
                        v-if="
                          !!bet.approved_volume &&
                          bet.contragent_id == company.id
                        "
                        class="text-nowrap mr-2"
                      >
                        <div class="h6">
                          {{ __("The volume of bet has approved") }}
                          {{
                            bet.correct ? __("Price") + ": " + bet.correct : ""
                          }}
                        </div>
                      </div>
                      <div
                        v-if="
                          !!bet.approved_contract &&
                          bet.contragent_id == company.id
                        "
                        class="text-nowrap mr-2"
                      >
                        <div class="h6">
                          {{ __("The contract has approved") }}
                        </div>
                      </div>
                      <div
                        v-if="
                          !!bet.approved_contract &&
                          bet.contragent_id == company.id
                        "
                        class="text-nowrap mr-2"
                      >
                        <a
                          :href="'/personal/invoice/' + bet.id"
                          class="btn btn-primary btn-sm"
                          target="_blank"
                          >{{ __("Get the Invoice") }}</a
                        >
                      </div>
                    </div>
                  </td>
                  <td v-else></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["auction", "can_bet", "observer"],
  data: function () {
    return {
      bid: {},
      errors: {},
    };
  },
  methods: {
    filter(bets) {
      var app = this;
      let betsList = [];
      for (let i in bets) {
        if (
          bets[i].contragent_id == app.company.id ||
          bets[i].distributor_id == app.company.id ||
          !bets[i].approved_contract
        )
          betsList.push(bets[i]);
      }
      return betsList;
    },
    guarantee(bet) {
      var app = this;
      if (app.auction) {
        bet.guarantee = !bet.guarantee * 1;
        axios
          .post("/web/v1/auctions/guarantee", {
            id: bet.id,
          })
          .then(function (resp) {
            bet = resp.bet;
          })
          .catch(function (err) {
            app.$fire({
              title: app.__("Error!"),
              text: err.response.data.message,
              type: "error",
              timer: 5000,
            });
          });
      }
    },
    betIt() {
      var app = this;
      if (app.auction)
        axios
          .post("/web/v1/auctions/bet", {
            auction: app.auction.id,
            interval: app.bid.interval.id,
            bidder: app.user.contragents[0].id,
            distributor: app.user.contragents[0].distributor ? app.user.contragents[0].distributor.id : null,
            volume: app.bid.volume,
            price: app.bid.price,
            store: app.bid.store ? app.bid.store.id : false,
          })
          .then(function (resp) {
            //
          })
          .catch(function (err) {
            app.$fire({
              title: app.__("Error!"),
              text: err.response.data.message,
              type: "error",
              timer: 5000,
            });
          });
    },
    removeBet(bet) {
      var app = this;
      app
        .$confirm(app.__("Вы хотите удалить свою бронь. Вы уверены?"))
        .then(() => {
          if (app.auction)
            axios
              .get("/web/v1/auctions/unbet/" + bet.id)
              .then(function (resp) {
                //
              })
              .catch(function (err) {
                app.$fire({
                  title: app.__("Error!"),
                  text: err.response.data.message,
                  type: "error",
                  timer: 5000,
                });
              });
        });
    },
  },
};
</script>