<template>
  <div class="row">
    <div class="col-md-12">
      <div class="h4" style="font-weight: 900">
        {{ __("You are an auction participant") }}
      </div>

      <div>
        <div class="dropdown">
          <button
            class="btn btn-danger w-100 dropdown-toggle"
            type="button"
            @click="
              () => {
                showDropdown = !showDropdown;
              }
            "
          >
            {{
              user.contragents[0].distributor
                ? user.contragents[0].distributor.title
                : __("Принять участие в совместной закупке")
            }}
          </button>
          <ul class="dropdown-menu w-100" v-bind:class="{ show: showDropdown }">
            <li>
              <a
                class="dropdown-item"
                href="javascript:;"
                @click="
                  () => {
                    user.contragents[0].distributor = null;
                    showDropdown = false;
                  }
                "
                >{{ __("Отменить выбор") }}</a
              >
            </li>
            <li
              v-for="(item, index) in auction.contragent.distributors"
              :key="index"
            >
              <a
                class="dropdown-item"
                href="javascript:;"
                @click="() => checkJoint(item)"
                >{{ item.title }}
                <span
                  style="display: block; font-size: 0.9em; white-space: normal"
                  v-if="item.description"
                  >{{ item.description }}</span
                ></a
              >
            </li>
          </ul>
        </div>
        <br />
      </div>
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
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th colspan="4">
                    {{ __("Interval") }}: {{ interval.label }} |
                    {{ __("Interval start price") }}:
                    {{ interval.start_price }} | {{ __("Interval volume") }}:
                    {{ interval.volume }} <br />
                    {{ __("Undistributed volume") }}:
                    {{ interval.undistributed_volume }} |
                    {{ __("Active volume") }}: {{ interval.free_volume }} |
                    {{ __("Approved volume") }}:
                    {{ interval.volume - interval.free_volume }}
                  </th>
                </tr>
                <tr>
                  <th>{{ __("Volume") }}</th>
                  <th>{{ __("Price") }}</th>
                  <th>{{ __("Time") }}</th>
                  <th style="width: 100%"></th>
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
                      !bet.approved_volume ||
                      bet.contragent_id == company.id ||
                      bet.distributor_id == company.id
                    "
                  >
                    <div class="d-flex" v-if="bet.distributor">
                      <strong>{{
                        __("Совместно с ") + bet.distributor
                      }}</strong>
                    </div>
                    <div class="d-flex" v-if="bet.contragent_id == company.id">
                      <div class="d-flex">
                        <div>
                          <a
                            v-tooltip="__('Delete bet')"
                            href="javascript:void(0)"
                            class="btn btn-sm d-block mr-1"
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
                            <i class="mdi mdi-delete" aria-hidden="true"></i>
                          </a>
                        </div>
                        <div>
                          <a
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
                        </div>
                      </div>
                      <div class="text-nowrap mr-2">
                        <div class="h6" v-if="!!bet.approved_volume">
                          {{ __("The volume of bet has approved") }}
                          {{
                            bet.correct ? __("Price") + ": " + bet.correct : ""
                          }}
                        </div>
                        <div
                          class="d-flex"
                          v-if="
                            auction.mode == 'price2day' ||
                            auction.mode == 'callApp'
                          "
                        >
                          <input
                            style="max-width: 100px"
                            type="number"
                            step="0.01"
                            v-model="bet.self"
                            v-bind:class="{ 'is-invalid': errors.price }"
                          />
                          <button
                            class="btn btn-sm btn-primary ml-1"
                            @click="self(bet)"
                          >
                            предложить свою цену
                          </button>
                        </div>
                      </div>
                      <div
                        v-if="!!bet.approved_contract"
                        class="text-nowrap mr-2"
                      >
                        <div class="h6">
                          {{ __("The contract has approved") }}
                        </div>
                      </div>
                      <div
                        v-if="!!bet.approved_contract"
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
      showDropdown: false,
      bid: {},
      errors: {},
    };
  },
  methods: {
    checkJoint(item) {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/joints/check/", {
          item: item,
        })
        .then(function (res) {
          if (res.data.contragent) {
            app.user.contragents[0].distributor = item;
          } else {
            app.user.contragents[0].distributor = null;
            app.$fire({
              // title: app.__("Error!"),
              text: app.__(
                `Запрос в ${item.title} отправлен, совместная закупка с Вами не согласована!`
              ),
              type: "success",
              timer: 5000,
            });
          }
          app.showDropdown = false;
          loader.hide();
        })
        .catch(function (err) {
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load auction"),
            type: "error",
            timer: 5000,
          });
          loader.hide();
        });
    },
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
    self(bet) {
      var app = this;
      if (app.auction) {
        bet.guarantee = !bet.guarantee * 1;
        axios
          .post("/web/v1/auctions/self", {
            id: bet.id,
            self: bet.self,
          })
          .then(function (resp) {
            app.$fire({
              title: "Успешно",
              text: "Ваше предложение отправлено",
              type: "succes",
              timer: 5000,
            });
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
            distributor: app.user.contragents[0].distributor
              ? app.user.contragents[0].distributor.id
              : null,
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