<template>
  <section class="container">
    <div class="d-md-flex">
      <div class="mr-auto mb-3 d-md-flex">
        <div class="dropdown mr-1">
          <button
            class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            {{ __("создать шаблон моего договора") }}
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <router-link
              class="dropdown-item"
              v-for="type in contractTypes"
              :key="'type.' + type.id"
              :to="'/personal/contracts/create/' + type.id"
              >{{ type.name }}</router-link
            >
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="templates">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>тип договора</th>
            <th class="text-center">редакция</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="contractType in contractTypesMine"
            :key="'template.' + contractType.id"
          >
            <td>{{ contractType.name }}</td>
            <td class="text-center">{{ contractType.version / 10 }}</td>
            <td class="text-center">
              <router-link
                class="btn btn-primary btn-sm"
                :to="
                  '/personal/contracts/create/' + contractType.contract_type_id
                "
                ><i class="mdi mdi-pencil" aria-hidden="true"></i
              ></router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="table-responsive" id="templates">
      <table class="table table-bordered">
        <thead>
          <tr class="text-center">
            <th>номер договора</th>
            <th>принявший договор</th>
            <th>дата договора</th>
            <th>номер редакции</th>
            <th>статус подписания</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contract in contracts" :key="'contract.' + contract.id">
            <td class="text-center">{{ contract.id }}</td>
            <td>{{ contract.acceptor }}</td>
            <td class="text-center">{{ contract.date }}</td>
            <td class="text-center">
              {{ contract.version / 10 }}
            </td>
            <td class="text-center">
              {{ contract.statusText }}<br />
              <a
                v-if="contract.status < 2"
                class="btn btn-success btn-sm"
                @click="sign(contract.id)"
                href="javascript:;"
                ><span>Подписать</span></a
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
    for (let ctm of window.contractTypesMine) {
      console.log(ctm)
      let name = "";
      for (let ct of window.contractTypes) {
      console.log(ct)
        if (ctm.contract_type_id == ct.id) ctm.name = ct.name;
      }
      this.contractTypesMine.push(ctm);
    }
    app.errors = {};
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/contracts/my")
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
        .patch("/web/v1/contracts/signMy/" + id)
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
        .patch("/web/v1/contracts/unsignMy/" + id)
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
