<template>
  <section class="container-fluid">
    <div class="table-responsive" id="results">
      <div class="h2 text-center">{{ __("Совместные закупки") }}</div>
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __("Auction number") }}</th>
            <th>{{ __("Утвержден") }}</th>
            <th>{{ __("Режим") }}</th>
            <th>{{ __("End date") }}</th>
            <th>{{ __("Contragent name") }}</th>
            <th>{{ __("Склад") }}</th>
            <th>{{ __("Лот") }}</th>
            <th>{{ __("Contract volume") }}</th>
            <th>{{ __("Multiplicity") }}</th>
            <th>{{ __("Contract bid") }}</th>
            <th>{{ __("Amount") }}</th>
            <th>{{ __("Сферы") }}</th>
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
            <td>{{ __(result.auction.mode)}}</td>
            <td>{{ result.auction.finish_at | formatDateTime }}</td>
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
            <td>
              <div v-if="result.defer.orbits">
                {{ result.defer.orbits.join(", ") }}
              </div>
            </td>
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
      .get("/web/v1/results/purchases")
      .then(function (res) {
        app.results = res.data.purchases;
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