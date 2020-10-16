<template>
  <section class="container">
    <contract-header></contract-header>
    <form @submit="saveForm()">
      <div class="mb-2">
        <v-select
          :placeholder="__('Contragent')"
          label="title"
          :options="$root.contragents"
          v-model="contragent"
          ><div slot="no-options">{{ __("No Options Here!") }}</div></v-select
        >
      </div>
      <div
        class="table-responsive mb-2"
        id="templates"
        v-if="contractTemplates.length"
      >
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
              v-for="template in contractTemplates"
              :key="'template.' + template.id"
            >
              <td>{{ template.name }}</td>
              <td class="text-center">{{ template.version / 10 }}</td>
              <td class="text-center">
                <router-link
                  class="btn btn-secondary btn-sm"
                  :to="
                    '/personal/contracts/create/' + template.contract_type_id
                  "
                  ><i class="mdi mdi-eye" aria-hidden="true"></i
                ></router-link>
                <button
                  class="btn btn-sm"
                  v-bind:class="{
                    'btn-success': template.id == contractTemplate.id,
                    'btn-secondary': template.id != contractTemplate.id,
                  }"
                  @click="() => (contractTemplate = template)"
                  type="button"
                >
                  <i
                    class="mdi"
                    v-bind:class="{
                      'mdi-radiobox-marked': template.id == contractTemplate.id,
                      'mdi-radiobox-blank': template.id != contractTemplate.id,
                    }"
                    aria-hidden="true"
                  ></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="form-group" v-if="!!contract.contract_template_id">
        <span></span>
        <input
          type="text"
          placeholder="ООО ..."
          v-model="contract.acceptor_header"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.acceptor_header }"
        />
        <span role="alert" class="invalid-feedback" v-if="errors.acceptor_header">
          <strong v-for="(error, index) in errors.acceptor_header" :key="index">{{
            error
          }}</strong>
        </span>
      </div>
    </form>
  </section>
</template>
<script>
import contractHeader from "./header";
import switchCheckbox from "./switchCheckbox";
export default {
  components: {
    contractHeader: contractHeader,
    switchCheckbox: switchCheckbox,
  },
  methods: {
    saveForm() {
      let app = this;
      event.preventDefault();
      app.errors = {};
      let loader = Vue.$loading.show();
      //   axios
      //     .post("/web/v1/contractTemplates", this.template)
      //     .then(function (res) {
      //       app.$router.replace("/personal/contracts/recipient");
      //       loader.hide();
      //       return true;
      //     })
      //     .catch(function (err) {
      //       app.errors = err.response.data.errors;
      //       loader.hide();
      //     });
    },
  },
  data: function () {
    return {
      errors: {},
      contragent: null,
      contractTemplate: {},
      contractTemplates: [],
      contract: {
        acceptor_header: this.user.contragents[0].title,
        contract_template_id: "",
        contragent_id: "",
      },
    };
  },
  watch: {
    contragent: {
      handler() {
        let app = this;
        let loader = Vue.$loading.show();
        axios
          .get("/web/v1/contractTemplates/contragent/" + app.contragent.id)
          .then(function (res) {
            for (let ctm of res.data) {
              let name = "";
              for (let ct of window.contractTypes) {
                if (ctm.contract_type_id == ct.id) ctm.name = ct.name;
              }
              app.contractTemplates.push(ctm);
            }
            app.contract.contragent_id = app.contragent.id;
            loader.hide();
          })
          .catch(function (err) {
            console.log(err);
            loader.hide();
          });
      },
    },
    contractTemplate: {
      handler() {
        let app = this;
        app.contract.contract_template_id = app.contractTemplate.id;
      },
    },
  },
};
</script>
