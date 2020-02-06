<template>
  <section class="auction-edit-wrapper" v-if="auction">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div
              class="card-header"
              v-if="auction.contragent && auction.contragent.title"
            >#{{ auction.id }} {{ auction.contragent.title }}</div>
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
              <li class="list-group-item" v-if="auction.contragent">{{ auction.contragent.fio }}</li>
              <li class="list-group-item" v-if="auction.contragent">{{ auction.contragent.phone }}</li>
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
              <li
                class="list-group-item"
                v-if="!!auction.start_at"
              >{{ auction.start_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('During time') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item" v-if="$root.time">{{ $root.time | formatDateTime}}</li>
            </ul>
          </div>
          <br />
        </div>
        <div class="col-md-4">
          <div class="card text-center">
            <div class="card-header">{{ __('Auction finish') }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-if="!!auction.finish_at"
              >{{ auction.finish_at | formatDateTime }}</li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4" v-if="(mine && !auction.confirmed) || (!auction.finished)">
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
                v-if="!auction.finished"
                @click="showPopupAddBidder(auction.id)"
              >
                <i class="mdi mdi-account-plus" aria-hidden="true"></i>
              </a>
              <router-link
                v-tooltip="__('Edit auction')"
                :to="{name: 'editAuction', 'params': {'id': auction.id}}"
                v-if="mine && !auction.finished"
                class="btn btn-dark"
              >
                <i class="mdi mdi-pencil" aria-hidden="true"></i>
              </router-link>
              <a
                v-tooltip="__('Delete auction')"
                href="javascript:void(0)"
                class="btn btn-danger"
                v-if="mine && !auction.confirmed"
                @click="delAuction(auction.id)"
              >
                <i class="mdi mdi-delete" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <br />
        </div>
        <div
          class="col-md-8"
          v-bind:class="{ 'col-md-8': (mine && !auction.confirmed) || (!auction.finished) ,
                    'col-md-12': !(mine && !auction.confirmed) && auction.finished
                    }"
        >
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
      <div v-if="auction.started && !auction.finished">
        <div class="row" v-if="bidding">
          <div class="col-md-12">
            <div class="h4">{{ __("You are an auction participant") }}</div>
            <div class="row" v-if="!!can_bet">
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
                    :options="stores"
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
                    v-for="(bet, index) in auction.bets"
                    v-bind:key="index"
                    v-bind:class="{ 'table-success': user.contragents && user.contragents[0] && bet.contragent_id == user.contragents[0].id}"
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
                        v-if="!!bet.approved_volume && user.contragents && user.contragents[0] && bet.contragent_id == user.contragents[0].id"
                        class="text-nowrap"
                      >
                        <div class="h6">{{ __('The volume of bet has approved') }}₽</div>
                      </div>
                      <div
                        v-if="!!bet.approved_contract && user.contragents && user.contragents[0] && bet.contragent_id == user.contragents[0].id"
                        class="text-nowrap"
                      >
                        <div class="h6">{{ __('The contract has approved') }}₽</div>
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
                            v-bind:class="{ 'is-online': bet.took_part, 'is-offline': !bet.took_part }"
                          ></span>
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Delete bet')"
                            href="javascript:void(0)"
                            class="btn-sm"
                            :disabled="bet.approved_volume || approved_contract"
                            v-bind:class="{ 'btn-danger': !bet.approved_volume, 'btn-secondary': bet.approved_volume }"
                            @click="bet.approved_volume || approved_contract ? function(){ return false; } : removeBet(bet)"
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
                            :disabled="!!bet.approved_volume"
                            v-bind:class="{ 'btn-success': !bet.approved_volume, 'btn-secondary': bet.approved_volume }"
                            @click="bet.approved_volume ? function(){ return false; } : approveVolume(bet)"
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
                            :disabled="!!bet.approved_contract"
                          />
                        </div>
                      </td>
                      <td class="text-center">
                        <div class="text-nowrap">
                          <a
                            v-tooltip="__('Approve contract')"
                            href="javascript:void(0)"
                            class="btn btn-sm"
                            :disabled="!!bet.approved_contract"
                            v-bind:class="{ 'btn-success': !bet.approved_contract, 'btn-secondary': bet.approved_contract }"
                            @click="bet.approved_contract ? function(){ return false; } : approveContract(bet)"
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
        <div class="col-md-12" v-if="mine">
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
                        <span v-if="bet.created_at ">{{ bet.created_at | formatDateTime }}</span>
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
      <div class="row" v-if="mine">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">{{ __('Auction bidders') }}</div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item" v-for="(bidder, index) in auction.bidders" :key="index">
                {{ bidder.title }} ({{ bidder.fio }}, {{ __('Phone') }}: {{ bidder.phone }})
                <h6>{{ __('Can observe') }}</h6>
                <switch-checkbox-observe
                  v-if="!auction.finished"
                  v-model="bidder.observer"
                  :value="bidder.observer"
                  :auction="auction"
                  :bidder="bidder"
                ></switch-checkbox-observe>
                <h6>{{ __('Can bet') }}</h6>
                <switch-checkbox-canbet
                  v-if="!auction.finished"
                  v-model="bidder.can_bet"
                  :value="bidder.can_bet"
                  :auction="auction"
                  :bidder="bidder"
                ></switch-checkbox-canbet>
              </li>
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
            :multiple="true"
            @search="fetchBidders"
            v-model="add_bidders"
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
    <div>
      <beautiful-chat
        :participants="participants"
        :titleImageUrl="titleImageUrl"
        :onMessageWasSent="onMessageWasSent"
        :messageList="auctionMessages"
        :newMessagesCount="newMessagesCount"
        :isOpen="isChatOpen"
        :close="closeChat"
        :icons="icons"
        :open="openChat"
        :showEmoji="false"
        :placeholder="placeholderMessage"
        :showFile="false"
        :showTypingIndicator="showTypingIndicator"
        :colors="colors"
        :alwaysScrollToBottom="alwaysScrollToBottom"
        :messageStyling="messageStyling"
        @onType="handleOnType"
        @edit="editMessage"
        @remove="deleteMessage"
      >
        <template v-slot:header>&nbsp;</template>
        <template v-slot:text-message-body="{ message, me }">
           <div style="font-weight:bold;">{{message.data.text}}</div><i><small>{{me ? user.contragents[0].title : message.author }} - {{ message.created_at | formatChatTime}}</small></i>
        </template>
        <template v-slot:user-avatar>&nbsp;</template>

        
        <!-- <template v-slot:user-avatar="scopedProps">
        <slot name="user-avatar" :user="scopedProps.user" :message="scopedProps.message">
        </slot>
      </template>
      <template v-slot:text-message-body="scopedProps">
        <slot name="text-message-body" :message="scopedProps.message" :messageText="scopedProps.messageText" :messageColors="scopedProps.messageColors" :me="scopedProps.me">
        </slot>
        </template>-->
      </beautiful-chat>
    </div>
  </section>
</template>
<script>
import CloseIcon from "vue-beautiful-chat/src/assets/close-icon.png";
import OpenIcon from "vue-beautiful-chat/src/assets/logo-no-bg.svg";
import FileIcon from "vue-beautiful-chat/src/assets/file.svg";
import CloseIconSvg from "vue-beautiful-chat/src/assets/close.svg";
export default {
  computed: {
    // геттер вычисляемого значения
    auctionMessages: function () {
      // `this` указывает на экземпляр vm
      let ar = [];
      for(let j in this.auction.messages) 
      ar.push({
        id: this.auction.messages[j].id,
        author: this.user.id == this.auction.messages[j].user_id ? 'me' : this.auction.messages[j].author,
        auction_id: this.auction.messages[j].auction_id,
        type: this.auction.messages[j].type,
        data: this.auction.messages[j].data,
        created_at: this.auction.messages[j].created_at,
        updated_at: this.auction.messages[j].updated_at
      });
      return ar;
    }
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    let id = app.$route.params.id;
    app.$root.getMyStores(app);
    app.auctionId = id;
    app.$root.$on("gotAuction", function(auction) {
      if (auction.id == app.auction.id) app.auction = auction;
      app.renew();
    });
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
        loader.hide();
        app.bid.price = app.auction.price;
        app.bid.volume = 1;
        app.renew();
      });
    // .catch(function() {
    //   app.$fire({
    //     title: app.__("Error!"),
    //     text: app.__("Failed to load auction"),
    //     type: "error",
    //     timer: 5000
    //   });
    //   loader.hide();
    // });
  },
  data: function() {
    return {
      isLoading: true,
      onCancel: false,
      fullPage: true,
      multiplicities: [],
      contragents: [],
      stores: [],
      products: [],
      auctionId: null,
      bidding: 0,
      can_bet: 0,
      observer: 0,
      bidders: [],
      add_bidders: [],
      mine: 0,
      placeholderMessage: this.__("Write a message..."),
      maxModalWidth: 600,
      auction: {},
      bid: {},
      errors: {},
      icons: {
        open: {
          img: OpenIcon,
          name: "default"
        },
        close: {
          img: CloseIcon,
          name: "default"
        },
        file: {
          img: FileIcon,
          name: "default"
        },
        closeSvg: {
          img: CloseIconSvg,
          name: "default"
        }
      },
      participants: [], // the list of all the participant of the conversation. `name` is the user name, `id` is used to establish the author of a message, `imageUrl` is supposed to be the user avatar.
      titleImageUrl: "",
      messageList: [], // the list of the messages to show, can be paginated and adjusted dynamically
      newMessagesCount: 0,
      isChatOpen: true, // to determine whether the chat window should be open or closed
      showTypingIndicator: "", // when set to a value matching the participant.id it shows the typing indicator for the specific user
      colors: {
        header: {
          bg: "#343a40",
          text: "#ffffff"
        },
        launcher: {
          bg: "#343a40"
        },
        messageList: {
          bg: "#ffffff"
        },
        sentMessage: {
          bg: "#343a40",
          text: "#ffffff"
        },
        receivedMessage: {
          bg: "#eaeaea",
          text: "#222222"
        },
        userInput: {
          bg: "#f4f7f9",
          text: "#565867"
        }
      }, // specifies the color scheme for the component
      alwaysScrollToBottom: false, // when set to true always scrolls the chat to the bottom when new events are in (new message, user starts typing...)
      messageStyling: true // enables *bold* /emph/ _underline_ and such (more info at github.com/mattezza/msgdown)
    };
  },
  created() {},
  methods: {
    onMessageWasSent(message) {
      this.sendMessage(message);
      // called when the user sends a message
      //this.messageList = [...this.messageList, message];
    },
    editMessage(message) {
      console.log(message);
      // called when the user sends a message
      //this.messageList = [...this.messageList, message];
      return message;
    },
    deleteMessage(message) {
      axios.delete(
        "/api/v1/messages/" +
          message.id +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      );
    },
    sendMessage(body) {
      let app = this;
      let message = {
        auction_id: app.auction.id,
        user_id: app.user.id,
        message: body.data.text
      };
      axios
        .post(
          "/api/v1/messages?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token,
          message
        )
        .then(function(resp) {})
        .catch(function(errors) {
          app.$fire({
            title: app.__("Error!"),
            text: errors.response.data.message,
            type: "error",
            timer: 5000
          });
        });
    },
    openChat() {
      // called when the user clicks on the fab button to open the chat
      this.isChatOpen = true;
      this.newMessagesCount = 0;
    },
    closeChat() {
      // called when the user clicks on the botton to close the chat
      this.isChatOpen = false;
    },
    handleScrollToTop() {
      // called when the user scrolls message list to top
      // leverage pagination for loading another page of messages
    },
    handleOnType() {},
    editMessage(message) {
      const m = this.messageList.find(m => m.id === message.id);
      m.isEdited = true;
      m.data.text = message.data.text;
    },
    renew() {
      let app = this;
      if (app.user && app.user.contragents && app.user.contragents[0]) {
        let contr = app.user.contragents[0].id;
        for (let r in app.auction.bidders) {
          if (app.auction.bidders[r].id == contr) {
            app.can_bet = app.auction.bidders[r].can_bet;
            app.observer = app.auction.bidders[r].observer;
            app.bidding = 1;
          }
        }
        if (app.auction.contragent.id == contr) app.mine = 1;
      }
    },
    removeBet(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .get(
            "/api/v1/auctions/bet/remove/" +
              bet.id +
              "?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token
          )
          .then(function(resp) {
            bet.delete();
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    },
    approveContract(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .post(
            "/api/v1/auctions/bet/contract?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              id: bet.id,
              correct: bet.correct
            }
          )
          .then(function(resp) {
            bet.approved_contract = 1;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    },
    approveVolume(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .get(
            "/api/v1/auctions/bet/volume/" +
              bet.id +
              "?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token
          )
          .then(function(resp) {
            bet.approved_volume = 1;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    },
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
              price: app.bid.price,
              store: app.bid.store ? app.bid.store.id : false
            }
          )
          .then(function(resp) {
            console.log(app.bid);
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
      if (app.auction && app.add_bidders.length)
        axios
          .post(
            "/api/v1/auctions/add_bidder?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              auction: app.auction.id,
              bidders: app.add_bidders
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
    }
  }
};
</script>