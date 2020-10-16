<template>
  <section class="container">
    <contract-header></contract-header>

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
            <th>статусподписания</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contract in contracts" :key="'contract.' + contract.id">
            <td class="text-center">{{ contract.id }}</td>
            <td>{{ contract.acceptor }}</td>
            <td class="text-center">{{ contract.date }}</td>
            <td class="text-center">
              {{ contract.contract_template.version / 10 }}
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
import contractHeader from "./header";
export default {
  components: {
    contractHeader: contractHeader,
  },
  data: function () {
    return {
      contractTypesMine: [],
      contractTypes: window.contractTypes,
      contracts: [
        {
          id: 1,
          acceptor: "q",
          date: "1",
          contract_template: {
            version: 12,
          },
          status: "h",
        },
      ],
    };
  },
  mounted() {
    for (let ctm of window.contractTypesMine) {
      let name = "";
      for (let ct of window.contractTypes) {
        if (ctm.contract_type_id == ct.id) ctm.name = ct.name;
      }
      this.contractTypesMine.push(ctm);
    }
  },
  methods: {},
};
</script>
