<template>
  <section class="container">
    <div class="d-md-flex">
      <div class="mr-auto mb-3 d-md-flex">
        <router-link class="btn btn-primary" :to="'/personal/contracts/get'">{{
          __("добавить договор со мной")
        }}</router-link>
      </div>
    </div>

    <div class="table-responsive" id="templates">
      <table class="table table-bordered">
        <thead>
          <tr class="text-center">
            <th>номер договора</th>
            <th>предложивший договор</th>
            <th>дата договора</th>
            <th>номер редакции</th>
            <th>статусподписания</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contract in contracts" :key="'contract.' + contract.id">
            <td class="text-center">{{ contract.id }}</td>
            <td>{{ contract.recipient }}</td>
            <td class="text-center">{{ contract.date | formatDate }}</td>
            <td class="text-center">
              {{ contract.version / 10 }}
            </td>
            <td class="text-center">{{ contract.status }}</td>
            <td class="text-center">
              <a
                class="btn btn-secondary btn-sm"
                target="_blank"
                :href="'/personal/contracts/pdf/' + contract.id"
                ><i class="mdi mdi-eye" aria-hidden="true"></i>/<i
                  class="mdi mdi-printer"
                  aria-hidden="true"
                ></i
              ></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
export default {
  components: {},
  data: function () {
    return {
      contractTypesMine: [],
      contractTypes: window.contractTypes,
      contracts: [],
    };
  },
  mounted() {
    let app = this;
    app.errors = {};
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/contracts/tome")
      .then(function (res) {
        app.contracts = res.data.contracts
        loader.hide();
        return true;
      })
      .catch(function (err) {
        loader.hide();
      });
  },
  methods: {},
};
</script>
