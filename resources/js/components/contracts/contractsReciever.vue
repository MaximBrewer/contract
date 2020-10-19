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
            <th>статус подписания</th>
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
            <td class="text-center">
              {{ contract.statusText }}<br />
              <a
                v-if="contract.status == 0 || contract.status == 2"
                class="btn btn-success btn-sm"
                @click="sign(contract.id)"
                href="javascript:;"
                ><span v-if="contract.status < 1"
                  >Запросить разрешение на подписание</span
                ><span v-else>Подписать</span></a
              >
              <a
                v-if="contract.status > 2"
                class="btn btn-danger btn-sm"
                @click="unsign(contract.id)"
                href="javascript:;"
                ><span>Расторгнуть</span></a
              >
            </td>
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
        app.contracts = res.data.contracts;
        loader.hide();
        return true;
      })
      .catch(function (err) {
        loader.hide();
      });
  },
  methods: {
    sign(id) {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .patch("/web/v1/contracts/sign/" + id)
        .then(function (res) {
          let nc = [];
          for (let y in app.contracts) {
            if (app.contracts[y].id == res.data.contract.id) {
              nc.push(res.data.contract);
            } else {
              nc.push(app.contracts[y]);
            }
          }
          app.contracts = nc;
          loader.hide();
          return true;
        })
        .catch(function (err) {
          console.log(err);
          loader.hide();
        });
    },
    unsign(id) {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .patch("/web/v1/contracts/unsign/" + id)
        .then(function (res) {
          let nc = [];
          for (let y in app.contracts) {
            if (app.contracts[y].id == res.data.contract.id) {
              nc.push(res.data.contract);
            } else {
              nc.push(app.contracts[y]);
            }
          }
          app.contracts = nc;
          loader.hide();
          return true;
        })
        .catch(function (err) {
          console.log(err);
          loader.hide();
        });
    },
  },
};
</script>
