<template>
  <section class="container-fluid">
    <div class="table-responsive" id="results">
      <div class="h2 text-center">{{ __("История броней") }}</div>
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __("Auction number") }}</th>
            <th>{{ __("Утвержден") }}</th>
            <th>{{ __("Режим") }}</th>
            <th>{{ __("Интервал отгрузки") }}</th>
            <th>{{ __("Contragent name") }}</th>
            <th>{{ __("Склад") }}</th>
            <th>{{ __("Лот") }}</th>
            <th>{{ __("Contract volume") }}</th>
            <th>{{ __("Multiplicity") }}</th>
            <th>{{ __("Contract bid") }}</th>
            <th>{{ __("Amount") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(result, index) in results" :key="index">
            <td>{{ index + 1 }}</td>
            <td>
              <router-link
                :to="'/personal/auctions/show/' + result.auction.id"
                >{{ result.auction.id }}</router-link
              >
            </td>
            <td>{{ result.approved_contract ? __("Да") : __("Нет") }}</td>
            <td>{{ __(result.auction.mode) }}</td>
            <td>
              <div class="text-nowrap">
                <strong>{{ __("From") }}:</strong>
                <span>{{ result.interval.from | formatDateTime }}</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("To") }}:</strong>
                <span>{{ result.interval.to | formatDateTime }}</span>
              </div>
            </td>
            <td>
              <router-link
                :to="'/personal/contragents/show/' + result.contragent.id"
                >{{ result.contragent.title }}</router-link
              >
            </td>
            <td>{{ result.store.address }}</td>
            <td>{{ result.lot ? result.lot.title : "" }}</td>
            <td>{{ result.volume }}</td>
            <td>{{ result.auction.multiplicity.title }}</td>
            <td>{{ result.bid }}₽</td>
            <td>{{ result.sum }}₽</td>
          </tr>
        </tbody>
      </table>
    </div>
    <br />
  </section>
</template>
<script>
export default {
  data: function () {
    return {
      results: [],
    };
  },
  mounted() {
    var app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/results/history")
      .then(function (res) {
        app.results = res.data.result;
        loader.hide();
      })
      .catch(function (err) {
        // console.log(err);
        app.$fire({
          title: app.__("Failed to load results"),
          type: "error",
          timer: 2000,
        });
        loader.hide();
      });
  },
};
</script>