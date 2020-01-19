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
                v-if="mine"
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
                <div class="form-group text-right">
                  <button class="btn btn-primary" @click="betIt">{{ __('Edit auction') }}</button>
                </div>
              </div>
            </div>
            <div
              class="table-responsive"
              id="auction_activity"
              v-if="auction.results && auction.results.length"
            >
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{ __('Contragent') }}</th>
                    <th>{{ __('Is online') }}</th>
                    <th>{{ __('Can bet') }}</th>
                    <th>{{ __('Active volume') }}</th>
                    <th>{{ __('Active price') }}</th>
                    <th>{{ __('Approve volume') }}</th>
                    <th>{{ __('Correcting price') }}</th>
                    <th>{{ __('Approve contract') }}</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

        <!--Mine-->
        <div class="row" v-if="auction.results && mine">
          <div class="col-md-12" v-if="auction.results.length">
            <div class="card">
              <div class="card-header">{{ __("Auction activity") }}</div>
              <div class="table-responsive" id="auction_activity">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>{{ __('Contragent') }}</th>
                      <th>{{ __('Is online') }}</th>
                      <th>{{ __('Can bet') }}</th>
                      <th>{{ __('Active volume') }}</th>
                      <th>{{ __('Active price') }}</th>
                      <th>{{ __('Approve volume') }}</th>
                      <th>{{ __('Correcting price') }}</th>
                      <th>{{ __('Approve contract') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="result, index in auction.results">
                      <td>
                        <div v-if="result.contragent" class="text-nowrap">
                          <div class="h6">{{ result.contragent.title }}</div>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <span
                            v-tooltip="__('Is online')"
                            href="javascript:void(0)"
                            class="btn"
                            v-bind:class="{ 'btn-success': result.took_part, 'btn-danger': !result.took_part }"
                            @click="canBet(auction.id, result.id)"
                          >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Can bet')"
                            href="javascript:void(0)"
                            class="btn"
                            v-bind:class="{ 'btn-success': result.can_bet, 'btn-danger': !result.can_bet }"
                            @click="canBet(auction.id, result.id)"
                          >
                            <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <span>{{ auction.active_volume }}/{{ auction.volume }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <span>{{ auction.active_price }}</span>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Approve volume')"
                            href="javascript:void(0)"
                            class="btn"
                            v-bind:class="{ 'btn-warning': !result.approved, 'btn-secondary': result.approved }"
                            @click="approveVolume(result)"
                          >
                            <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <input
                            class="form-control"
                            size="10"
                            type="text"
                            v-model="result.correct"
                          />
                        </div>
                      </td>
                      <td>
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Approve contract')"
                            href="javascript:void(0)"
                            class="btn"
                            v-bind:class="{ 'btn-warning': !(result.correct*1), 'btn-secondary': (result.correct*1) }"
                            @click="approveContract(result)"
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
            <div class="card-header">{{ __("Auction Results") }}</div>
            <div class="table-responsive" id="auction_results">
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
                  <tr v-for="result, index in auction.results">
                    <td>
                      <div v-if="result.contragent" class="text-nowrap">
                        <div class="h6">{{ result.contragent.title }}</div>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.took_part ? __("Yes") : __("No") }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.can_bet ? __("Yes") : __("No") }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.volume }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.price }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.correct }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-nowrap">
                        <span>{{ result.created_at | formatDateTime }}</span>
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
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __('Auction bidders') }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="bidder, index in auction.bidders"
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
      })
      .catch(function() {
        alert(app.__("Failed to load auction"));
        app.isLoading = false;
      });
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
      bid: {}
    };
  },
  created() {
    this.listenForBroadcast();
  },
  methods: {
    betIt(){
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
          console.log(e);
        }
      );
    }
  }
};
</script>