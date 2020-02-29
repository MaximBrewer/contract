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
        store: null,
        start_at: null,
        finish_at: null,
        comment: "",
        product: null,
        multiplicity: null
      }
    };
  }
};
</script>