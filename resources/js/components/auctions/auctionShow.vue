<template>
  <section class="auction-edit-wrapper" v-if="!!auction.id">
    <div class="container">
      <auction-info :auction="auction"></auction-info>
      <auction-actions :auction="auction"></auction-actions>
      <!--Started-->
      <div v-if="auction.started">
        <auction-bidding :auction="auction" :can_bet="can_bet" :observer="observer" v-if="bidding"></auction-bidding>
        <auction-mine :auction="auction" v-if="auction.bets && auction.contragent.id == company.id"></auction-mine>
      </div>
      <!--Bidders-->
      <auction-bidders :auction="auction" v-if="auction.contragent.id == company.id"></auction-bidders>
    </div>
    <auction-chat :auction="auction"></auction-chat>
  </section>
</template>
<script>
import auctionInfo from "./auctionInfo";
import auctionActions from "./auctionActions";
import auctionBidding from "./auctionBidding";
import auctionMine from "./auctionMine";
import auctionFinishedMine from "./auctionFinishedMine";
import auctionBidders from "./auctionBidders";
import auctionChat from "./auctionChat";
export default {
  components: {
    auctionInfo: auctionInfo,
    auctionActions: auctionActions,
    auctionBidding: auctionBidding,
    auctionMine: auctionMine,
    auctionFinishedMine: auctionFinishedMine,
    auctionBidders: auctionBidders,
    auctionChat: auctionChat
  },
  mounted() {
    let app = this;
    let id = app.$route.params.id;
    let loader = Vue.$loading.show();
    app.$root.$on("gotAuction", function(auction) {
      if (auction.id == app.auction.id) app.auction = auction;
      app.renew();
    });
    axios
      .get("/api/v1/auction/" + id)
      .then(function(resp) {
        app.auction = resp.data;
        loader.hide();
        app.bid.price = app.auction.price;
        app.bid.volume = 1;
        app.renew();
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load auction"),
          type: "error",
          timer: 5000
        });
        loader.hide();
      });
  },
  data: function() {
    return {
      bidding: 1,
      can_bet: 0,
      observer: 0,
      auction: {},
      bid: {}
    };
  },
  methods: {
    renew() {
      for (let r in this.auction.bidders) {
        if (this.auction.bidders[r].id == this.company.id) {
          this.can_bet = this.auction.bidders[r].can_bet;
          this.observer = this.auction.bidders[r].observer;
          this.bidding = 1;
        }
      }
    }
  }
};
</script>