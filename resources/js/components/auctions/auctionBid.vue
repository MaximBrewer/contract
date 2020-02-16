<template>
  <div>
    <targets-list action="my"></targets-list>
    <auctions-list :action="'bid'"></auctions-list>
  </div>
</template>
<script>
import auctionsList from "./auctionsList";
import targetsList from "../targets/targetsList";
export default {
  components: {
    auctionsList: auctionsList,
    targetsList: targetsList
  }
};
</script>