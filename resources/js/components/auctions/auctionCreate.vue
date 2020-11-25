<template>
  <section class="auction-edit-wrapper">
    <auction-form :auction="auction"></auction-form>
  </section>
</template>
<script>
import auctionForm from "./auctionForm";
export default {
  components: {
    auctionForm: auctionForm
  },
  mounted: function() {
    let app = this;
    if (!app.company.rating || app.company.rating < 3) {
      app.$router.push("/personal/auctions/my");
      app.$fire({
        title: app.__("Error!"),
        text: app.__(
          "You have a low rating, contact the administration or ask other Russian companies to write reviews about your company."
        ),
        type: "error"
      });
    }
  },
  data: function() {
    return {
      auction: {
        contragent: null,
        prepay: 10,
        store: null,
        start_at: null,
        finish_at: null,
        delay_sell: 30,
        delay_buy: 30,
        comment: "",
        product: null,
        multiplicity: null,
        mode: 'price2day',
        can_bet: 'yes',
        autosale: 'no',
        intervals: [
          {}
        ]
      }
    };
  }
};
</script>