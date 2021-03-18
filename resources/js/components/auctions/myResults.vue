<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <router-link
            class="btn btn-primary btn-lg"
            :to="'/personal/settlements'"
          >{{ __('Взаиморасчеты') }}</router-link>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="results">
      <div class="h2 text-center">{{ __('My results') }}</div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('Auction number') }}</th>
            <th>{{ __('End date') }}</th>
            <th>{{ __('Contragent name') }}</th>
            <th>{{ __("Склад") }}</th>
            <th>{{ __("Лот") }}</th>
            <th>{{ __('Contract volume') }}</th>
            <th>{{ __('Multiplicity') }}</th>
            <th>{{ __('Contract bid') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Gain') }}</th>
            <th>{{ __('Platform reward') }}</th>
            <th width="40%">{{ __('If you do not agree with the calculations, leave a comment!') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(result, index) in results" :key="index">
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="'/personal/auctions/show/' + result.auction.id"
              >{{ result.auction.id }}</router-link>
            </td>
            <td>{{ result.auction.finish_at | formatDateTime }}</td>
            <td>
              <router-link
                :to="'/personal/contragents/show/' + result.contragent.id"
              >{{ result.contragent.title }}</router-link>
            </td>
            <td>{{ result.store.address }}</td>
            <td>{{ result.volume }}</td>
            <td>{{ result.auction.multiplicity.title }}</td>
            <td>{{ result.bid }}₽</td>
            <td>{{ result.sum }}₽</td>
            <td>{{ result.rest }}₽</td>
            <td v-tooltip="result.reward.tooltip">{{ result.reward.sum }}₽</td>
            <td>
              <div class="form-group">
                <textarea class="form-control" v-model="result.message"></textarea>
              </div>
              <div class="form-group text-right">
                <button class="btn btn-primary" @click="send(result)">{{ __('Send') }}</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div><br><br>
    <div class="table-responsive" id="logistic_logs" v-if="logistic_logs.length">
      <div class="h2 text-center">{{ __('My results') }}</div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('Vehicle') }}</th>
            <th>{{ __('Who watched') }}</th>
            <th>{{ __('Date & time') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(logistic_log, index) in logistic_logs" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ logistic_log.vehicle }}</td>
            <td>{{ logistic_log.contragent.title }}</td>
            <td>{{ logistic_log.datetime }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
export default {
  data: function() {
    return {
      results: [],
      message: [],
      logistic_logs: []
    };
  },
  mounted() {
    var app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/results")
      .then(function(res) {
        app.results = res.data.results;
        app.logistic_logs = res.data.logistic_logs;
        loader.hide();
      })
      .catch(function(err) {
        // console.log(err);
        app.$fire({
          title: app.__("Failed to load results"),
          type: "error",
          timer: 2000
        });
        loader.hide();
      });
  },
  methods: {
    send(result) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/results", { message: result.message, id: result.id })
        .then(function(res) {
          app.$fire({
            title: app.__("Thank you , Your message is accepted!"),
            type: "success",
            timer: 2000
          });
          loader.hide();
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Failed to sen message"),
            type: "error",
            timer: 2000
          });
          loader.hide();
        });
    }
  }
};
</script>