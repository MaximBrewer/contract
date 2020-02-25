<template>
  <div class="row">
    <div class="col-md-12" v-if="auction.bets.length">
      <div class="card">
        <div class="card-header">
          {{ __("Auction history") }}
        </div>
        <div class="table-responsive" id="auction_history">
          <table class="table table-bordered line-height-22">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">{{ __('Price') }}</th>
                <th class="text-center">{{ __('Volume') }}</th>
                <th class="text-center">{{ __('Contragent') }}</th>
                <th class="text-center">{{ __('Time') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(unit, index) in auction.histories" :key="index">
                <td class="text-center">{{ unit.id }}</td>
                <td class="text-center">{{ unit.price }}</td>
                <td class="text-center">{{ unit.volume }}</td>
                <td class="text-center">{{ unit.contragent }}</td>
                <td class="text-center">{{ unit.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["auction"],
  methods: {}
};
</script>