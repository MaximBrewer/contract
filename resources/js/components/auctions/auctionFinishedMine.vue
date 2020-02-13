<template>
  <div class="col-md-12" v-if="auction.contragent.id == company.id">
    <div class="card">
      <div class="card-header">{{ __("Auction Bets") }}</div>
      <div class="table-responsive" id="auction_bets">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>{{ __('Contragent') }}</th>
              <th>{{ __('Took part') }}</th>
              <th>{{ __('Made bets') }}</th>
              <th>{{ __('Volume') }}</th>
              <th>{{ __('Price') }}</th>
              <th>{{ __('Correct') }}</th>
              <th>{{ __('Time') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(bet, index) in auction.bets" :key="index">
              <td>
                <div v-if="bet.contragent" class="text-nowrap">
                  <div class="h6">{{ bet.contragent.title }}</div>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span>{{ bet.took_part ? __("Yes") : __("No") }}</span>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span>{{ bet.can_bet ? __("Yes") : __("No") }}</span>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span>{{ bet.volume }}</span>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span>{{ bet.price }}</span>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span>{{ bet.correct }}</span>
                </div>
              </td>
              <td>
                <div class="text-nowrap">
                  <span v-if="bet.created_at ">{{ bet.created_at | formatDateTime }}</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />
  </div>
</template>
<script>
export default {
  props: ["auction"]
};
</script>