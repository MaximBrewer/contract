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
  data: function() {
    return {
      auction: {}
    };
  },
  created() {
    if (!this.$route.params.id) this.$router.replace("/personal/auctions");
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/auction/" + app.$route.params.id)
      .then(function(res) {
        app.auction = res.data.data;
        loader.hide();
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: err.response.data.message,
          type: "error",
          timer: 5000
        });
        loader.hide();
      });
  }
};
</script>