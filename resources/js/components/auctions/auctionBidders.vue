<template>
  <div class="row">
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
</template>
<script>
import switchCheckboxCanbet from "./switchCheckboxCanbet";
import switchCheckboxObserve from "./switchCheckboxObserve";
export default {
  components: {
    switchCheckboxCanbet: switchCheckboxCanbet,
    switchCheckboxObserve: switchCheckboxObserve
  },
  props: ["auction"]
};
</script>