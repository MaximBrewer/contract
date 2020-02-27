<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 text-right">
          <div class="form-group">
            <router-link
              :to="{name: 'createTarget'}"
              class="btn btn-primary btn-lg"
            >{{ __('Create new target') }}</router-link>
          </div>
        </div>
      </div>
    </div>
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