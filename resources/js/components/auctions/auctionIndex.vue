<template>
  <auctions-list :action="'all'"></auctions-list>
</template>
<script>
import auctionsList from "./auctionsList";
export default {
  components: {
    auctionsList: auctionsList
  }
};
</script>