<template>
  <section>
    <div class="row" v-for="(interval, ind) in auction.intervals" :key="ind">
      <div class="col-md-12" v-if="interval.bets.length">
        <div class="card">
          <div class="card-header">vbvb
            <strong>
              {{ __('Interval start price') }}: {{ interval.start_price }} |
              {{ __('Interval volume') }}: {{ interval.volume }} |
              {{ __('Active volume') }}: {{ interval.free_volume }} |
              {{ __('Approved volume') }}: {{ interval.volume - interval.free_volume }} |
              {{ __('Undistributed volume') }}: {{ interval.undistributed_volume }} |
              {{ __('Interval') }}: {{ interval.label }}
            </strong>
          </div>
          <div class="table-responsive" id="auction_activity">
            <table class="table table-bordered line-height-22">
              <thead>
                <tr>
                  <th>{{ __('Contragent') }}</th>
                  <th>{{ __('Is online') }}</th>
                  <th>{{ __('Remove bet') }}</th>
                  <th>{{ __('Active volume') }}</th>
                  <th>{{ __('Active price') }}</th>
                  <th>{{ __('Approve volume') }}</th>
                  <th>{{ __('Correcting price') }}</th>
                  <th>{{ __('Approve contract') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(bet, index) in interval.bets" :key="index">
                  <td>
                    <div v-if="bet.contragent" class="text-nowrap">
                      <div class="h6">{{ bet.contragent.title }}</div>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <span
                        v-tooltip="__('Is online')"
                        href="javascript:void(0)"
                        class="online"
                        v-bind:class="{ 'is-online': bet.took_part, 'is-offline': !bet.took_part }"
                      ></span>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <a
                        v-tooltip="__('Delete bet')"
                        href="javascript:void(0)"
                        class="btn-sm"
                        :disabled="bet.approved_volume || approved_contract"
                        v-bind:class="{ 'btn-danger': !bet.approved_volume, 'btn-secondary': bet.approved_volume }"
                        @click="bet.approved_volume || approved_contract ? function(){ return false; } : removeBet(bet)"
                      >
                        <i class="mdi mdi-delete" aria-hidden="true"></i>
                      </a>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <span>{{ bet.volume }}</span>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <span>{{ bet.price }}</span>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <a
                        v-tooltip="__('Approve volume')"
                        href="javascript:void(0)"
                        class="btn btn-sm"
                        :disabled="!!bet.approved_volume"
                        v-bind:class="{ 'btn-success': !bet.approved_volume, 'btn-secondary': bet.approved_volume }"
                        @click="bet.approved_volume ? function(){ return false; } : approveVolume(bet)"
                      >
                        <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                      </a>
                    </div>
                  </td>
                  <td>
                    <div class="text-nowrap">
                      <input
                        class="text-right form-control"
                        size="10"
                        type="text"
                        v-model="bet.correct"
                        :disabled="!!bet.approved_contract"
                      />
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="text-nowrap">
                      <a
                        v-tooltip="__('Approve contract')"
                        href="javascript:void(0)"
                        class="btn btn-sm"
                        :disabled="!!bet.approved_contract"
                        v-bind:class="{ 'btn-success': !bet.approved_contract, 'btn-secondary': bet.approved_contract }"
                        @click="bet.approved_contract ? function(){ return false; } : approveContract(bet)"
                      >
                        <i class="mdi mdi-check-circle" aria-hidden="true"></i>
                      </a>
                      <a
                        v-if="!!bet.approved_contract"
                        href="javascript:void(0)"
                        class="btn btn-primary"
                      >{{__('Get the Invoice')}}</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  props: ["auction"],
  methods: {
    removeBet(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .get("/web/v1/auctions/bet/remove/" + bet.id)
          .then(function(resp) {
            bet.delete();
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    },
    approveContract(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .post("/web/v1/auctions/bet/contract", {
            id: bet.id,
            correct: bet.correct
          })
          .then(function(resp) {
            bet.approved_contract = 1;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    },
    approveVolume(bet) {
      var app = this;
      app.$confirm(app.__("Are you sure?")).then(() => {
        axios
          .get("/web/v1/auctions/bet/volume/" + bet.id)
          .then(function(resp) {
            bet.approved_volume = 1;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      });
    }
  }
};
</script>