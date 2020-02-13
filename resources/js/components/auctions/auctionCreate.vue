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