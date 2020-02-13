<template>
  <section>
    <div class="container-fluid">
      <div class="h2 text-center">{{ __('My auctions') }}</div>
      <auctions-list :action="'archive'"></auctions-list>
    </div>
  </section>
</template>
<script>
import auctionsList from "./auctionsList";
export default {
  components: {
    auctionsList: auctionsList
  }
};
</script>