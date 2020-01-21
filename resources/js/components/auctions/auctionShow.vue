<template>
  <section class="auction-edit-wrapper">
    <div class="container">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div
              class="card-header"
              v-if="auction.contragent && auction.contragent.title"
            >{{ auction.contragent.title }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.federal_district"
              >{{ auction.store.federal_district.title }}</li>
              <li
                class="list-group-item"
                v-if="auction.region && auction.store.region"
              >{{ auction.store.region.title }}</li>
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.address"
              >{{ auction.store.address }}</li>
              <li
                class="list-group-item"
                v-if="auction.store && auction.store.coords"
              >{{ auction.store.coords }}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-6">
          <div class="card">
            <div
              class="card-header"
              v-if="auction.product && auction.product.title"
            >{{ auction.product.title }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="auction.multiplicity"
              >{{ auction.multiplicity.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Auction volume') }}: {{ auction.volume }} {{ __('un') }}.</li>
              <li
                class="list-group-item"
              >{{ __('Auction start price') }}: {{ auction.start_price }}₽</li>
              <li class="list-group-item">{{ __('Auction step') }}: {{ auction.step }}₽</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('Auction start') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ auction.start_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('During time') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ time | formatDateTime}}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('Auction finish') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">{{ auction.finish_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center">
            <div class="btn-group" role="group" aria-label="Basic example">
              <a
                v-tooltip="__('Confirm auction')"
                href="javascript:void(0)"
                v-if="mine && !auction.confirmed"
                class="btn btn-primary"
                @click="confirm(auction.id)"
              >
                <i class="mdi mdi-check-circle" aria-hidden="true"></i>
              </a>
              <a
                v-tooltip="__('Add bidder')"
                href="javascript:void(0)"
                class="btn btn-success"
                @click="showPopupAddBidder(auction.id)"
              >
                <i class="mdi mdi-account-plus" aria-hidden="true"></i>
              </a>
              <router-link
                v-tooltip="__('Edit auction')"
                :to="{name: 'editAuction', 'params': {'id': auction.id}}"
                v-if="mine"
                class="btn btn-dark"
              >
                <i class="mdi mdi-pencil" aria-hidden="true"></i>
              </router-link>
              <a
                v-tooltip="__('Delete auction')"
                href="javascript:void(0)"
                class="btn btn-danger"
                v-if="mine"
                @click="delAuction(auction.id)"
              >
                <i class="mdi mdi-delete" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <br />
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">{{ __('Auction comment') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item" v-if="auction.comment">{{ auction.comment }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <!--Started-->
      <!--Bidding-->
      <div v-if="auction.started">
        <div class="row" v-if="bidding">
          <div class="col-md-12">
            <div class="h4">{{ __("You are an auction participant") }}</div>
            <div class="row">
              <div class="col-md-4">
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
              <div class="col-md-4">
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
              <div class="col-md-4">
                <div class="form-group">
                  <div>
                    <label class="control-label">&nbsp;</label>
                  </div>
                  <button class="btn btn-primary" @click="betIt">{{ __('Bet') }}</button>
                </div>
              </div>
            </div>
            <div
              class="table-responsive"
              id="auction_activity"
              v-if="auction.bets && auction.bets.length"
            >
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{ __('Active volume') }}</th>
                    <th>{{ __('Active price') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(bet, index) in auction.bets" v-bind:key="index">
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
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!--Mine-->
        <div class="row" v-if="auction.bets && mine">
          <div class="col-md-12" v-if="auction.bets.length">
            <div class="card">
              <div class="card-header">
                {{ __("Auction activity") }}
                <strong>{{ auction.free_volume }}/{{ auction.volume }}</strong>
              </div>
              <div class="table-responsive" id="auction_activity">
                <table class="table table-bordered line-height-22">
                  <thead>
                    <tr>
                      <th>{{ __('Contragent') }}</th>
                      <th>{{ __('Is online') }}</th>
                      <th>{{ __('Remove bet') }}</th>
                      <th>{{ __('Active volume') }}</th>
                      <th>{{ __('Active price') }}</th>
                      <th>{{ __('Approve volume') }}</th>
                      <th>{{ __('Correcting price') }}</th>
                      <th>{{ __('Approve contract') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(bet, index) in auction.bets" :key="index">
                      <td>
                        <div v-if="bet.contragent" class="text-nowrap">
                          <div class="h6">{{ bet.contragent.title }}</div>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <span
                            v-tooltip="__('Is online')"
                            href="javascript:void(0)"
                            class="online"
                            v-bind:class="{ 'is-online': bet.contragent.is_online, 'is-offline': !bet.contragent.is_online }"
                          ></span>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Can bet')"
                            href="javascript:void(0)"
                            class="btn btn-danger btn-sm"
                            @click="removeBet(bet.id)"
                          >
                            <i class="mdi mdi-delete" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <span>{{ bet.volume }}</span>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <span>{{ bet.price }}</span>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Approve volume')"
                            href="javascript:void(0)"
                            class="btn btn-sm"
                            v-bind:class="{ 'btn-success': !bet.approved, 'btn-secondary': bet.approved }"
                            @click="approveVolume(bet)"
                          >
                            <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <input
                            class="text-right form-control"
                            size="10"
                            type="text"
                            v-model="bet.correct"
                          />
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Approve contract')"
                            href="javascript:void(0)"
                            class="btn btn-sm"
                            v-bind:class="{ 'btn-success': !(bet.correct*1), 'btn-secondary': (bet.correct*1) }"
                            @click="approveContract(bet)"
                          >
                            <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Finished-->
      <div class="row" v-if="auction.finished">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __("Auction Bets") }}</div>
            <div class="table-responsive" id="auction_bets">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{ __('Contragent') }}</th>
                    <th>{{ __('Took part') }}</th>
                    <th>{{ __('Made bets') }}</th>
                    <th>{{ __('Volume') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Correct') }}</th>
                    <th>{{ __('Time') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(bet, index) in auction.bets" :key="index">
                    <td>
                      <div v-if="bet.contragent" class="text-nowrap">
                        <div class="h6">{{ bet.contragent.title }}</div>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.took_part ? __("Yes") : __("No") }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.can_bet ? __("Yes") : __("No") }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.volume }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.price }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.correct }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ bet.created_at | formatDateTime }}</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <br />
        </div>
      </div>
      <!--Bidders-->
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __('Auction bidders') }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="(bidder, index) in auction.bidders"
                :key="index"
              >{{ bidder.title }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
    </div>
    <modal name="add_bidder" height="auto" :adaptive="true" width="90%" :maxWidth="maxModalWidth">
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __('Auction bidder add') }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('add_bidder')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <v-select
            max-height="50px"
            :options="bidders"
            label="title"
            @search="fetchBidders"
            v-model="bidder"
          ></v-select>
          <br />
          <br />
          <br />
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          @click="$modal.hide('target');addBidder()"
        >{{ __('Add bidder') }}</button>
      </div>
    </modal>
  </section>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import vSelect from "vue-select";
export default {
  components: {
    vSelect: vSelect,
    Datetime: Datetime,
    Loading: Loading
  },
  mounted() {
    let app = this;
    app.isLoading = true;
    let id = app.$route.params.id;
    app.auctionId = id;
    axios
      .get(
        "/api/v1/contragents?search=csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.bidders = resp.data;
      });
    axios
      .get(
        "/api/v1/auction/" +
          id +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.auction = resp.data;
        app.isLoading = false;
        if (app.user && app.user.contragents && app.user.contragents[0]) {
          let contr = app.user.contragents[0].id;
          for (let r in app.auction.bidders) {
            if (app.auction.bidders[r].id == contr) app.bidding = 1;
          }
          if (app.auction.contragent.id == contr) app.mine = 1;
        }
        app.bid.price = aucton.price;
        app.bid.volume = 1;
      });
    // .catch(function() {
    //   app.$fire({
    //     title: app.__("Error!"),
    //     text: app.__("Failed to load auction"),
    //     type: "error",
    //     timer: 5000
    //   });
    //   app.isLoading = false;
    // });
  },
  data: function() {
    return {
      time: window.document.querySelector('meta[name="server-time"]').content,
      isLoading: true,
      onCancel: false,
      fullPage: true,
      multiplicities: [],
      contragents: [],
      stores: [],
      products: [],
      auctionId: null,
      bidding: 0,
      bidders: [],
      bidder: null,
      mine: 0,
      maxModalWidth: 600,
      auction: {},
      bid: {},
      errors: {}
    };
  },
  created() {
    this.listenForBroadcast();
  },
  methods: {
    betIt() {
      var app = this;
      if (app.auction)
        axios
          .post(
            "/api/v1/auctions/bet?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              auction: app.auction.id,
              bidder: app.user.contragents[0].id,
              volume: app.bid.volume,
              price: app.bid.price
            }
          )
          .then(function(resp) {
            app.auction = resp.data;
            app.$modal.hide("add_bidder");
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
    },
    addBidder() {
      var app = this;
      if (app.auction && app.bidder)
        axios
          .post(
            "/api/v1/auctions/add_bidder?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              auction: app.auction.id,
              bidder: app.bidder.id
            }
          )
          .then(function(resp) {
            app.auction = resp.data;
            app.$modal.hide("add_bidder");
          });
    },
    delAuction() {
      var app = this;
      if (app.auction)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios
            .get(
              "/api/v1/auction/delete/" +
                app.auction.id +
                "?csrf_token=" +
                window.csrf_token +
                "&api_token=" +
                window.api_token
            )
            .then(function(resp) {
              app.$router.replace("/personal/auctions");
            });
        });
    },
    confirm() {
      var app = this;
      if (app.auction)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios
            .get(
              "/api/v1/auction/confirm/" +
                app.auction.id +
                "?csrf_token=" +
                window.csrf_token +
                "&api_token=" +
                window.api_token
            )
            .then(function(resp) {
              app.auction = resp.data;
            });
        });
    },
    fetchBidders(search, loading) {
      var app = this;
      loading(true);
      axios
        .get(
          "/api/v1/contragents?search=" +
            search +
            "csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.bidders = resp.data;
          loading(false);
        });
    },
    showPopupAddBidder() {
      var app = this;
      app.$modal.show("add_bidder");
    },
    listenForBroadcast() {
      var app = this;
      Echo.channel("cross_contractru_database_every-minute").listen(
        "PerMinute",
        function(e) {
          app.time = e.time;
          e.started.forEach(auction => {
            if (auction.id == app.auction.id) {
              app.auction.started = true;
            }
          });
          e.started.forEach(auction => {
            if (auction.id == app.auction.id) {
              app.auction.finished = true;
            }
          });
        }
      );
      Echo.channel("cross_contractru_database_message-pushed").listen(
        "MessagePushed",
        function(e) {
          console.log(e.auction)
          if (app.auction.id == e.auction.id) app.auction == e.auction;
        }
      );
    }
  }
};
</script>