<template>
  <section class="container">
    <form @submit="saveForm()">
      <div>Вы создаете шаблон договора типа "{{ contractType.name }}"</div>
      <br />
      <input type="hidden" v-model="template.contract_type_id" />
      <div class="form-group">
        <input
          type="text"
          :placeholder="contractType.title"
          v-model="template.title"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.title }"
        />
        <span role="alert" class="invalid-feedback" v-if="errors.title">
          <strong v-for="(error, index) in errors.title" :key="index">{{
            error
          }}</strong>
        </span>
      </div>

      <div class="form-group">
        <input
          type="text"
          :placeholder="
            user.contragents[0].title +
            ', в лице ..., действующий на основании ...'
          "
          v-model="template.proposer_header"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.proposer_header }"
        />
        <span
          role="alert"
          class="invalid-feedback"
          v-if="errors.proposer_header"
        >
          <strong
            v-for="(error, index) in errors.proposer_header"
            :key="index"
            >{{ error }}</strong
          >
        </span>
        <div>(сторона/представитель пр.: ООО "ААА", в лице Иванова Ивана Ивановича, действующего на основании устава)</div>
      </div>
      <div class="form-group">
        <input
          type="text"
          :placeholder="'поставщик'"
          v-model="template.recipient_title"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.recipient_title }"
        />
        <span
          role="alert"
          class="invalid-feedback"
          v-if="errors.recipient_title"
        >
          <strong
            v-for="(error, index) in errors.recipient_title"
            :key="index"
            >{{ error }}</strong
          >
        </span>
        <div>(наименование предлагающей стороны, пр.: поставщик)</div>
      </div>
      <div class="form-group">
        <input
          type="text"
          :placeholder="'покупатель'"
          v-model="template.receiver_title"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.receiver_title }"
        />
        <span
          role="alert"
          class="invalid-feedback"
          v-if="errors.receiver_title"
        >
          <strong
            v-for="(error, index) in errors.receiver_title"
            :key="index"
            >{{ error }}</strong
          >
        </span>
        <div>(наименование принимающей стороны, пр.: покупатель)</div>
      </div>
      <div class="form-group">
        <input
          type="text"
          :placeholder="'стороны'"
          v-model="template.common_title"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.common_title }"
        />
        <span
          role="alert"
          class="invalid-feedback"
          v-if="errors.common_title"
        >
          <strong
            v-for="(error, index) in errors.common_title"
            :key="index"
            >{{ error }}</strong
          >
        </span>
        <div>(наименование сторон, пр.: стороны)</div>
      </div>

      <div class="form-group">
        <vue-editor v-model="template.text"></vue-editor>
        <span role="alert" class="invalid-feedback" v-if="errors.text">
          <strong v-for="(error, index) in errors.text" :key="index">{{
            error
          }}</strong>
        </span>
      </div>
      <div class="form-group d-flex align-items-center justify-content-end">
        <div>подтверждать всем запрос на подписание договора?</div>
        <switch-checkbox v-model="template.accepting"></switch-checkbox>
      </div>
      <div class="form-group text-right">
        <button class="btn btn-primary">{{ __("создать/обновить") }}</button>
      </div>
    </form>
  </section>
</template>
<script>
import contractHeader from "./header";
import switchCheckbox from "./switchCheckbox";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    switchCheckbox,
    VueEditor
  },
  methods: {
    saveForm() {
      let app = this;
      event.preventDefault();
      app.errors = {};
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/contractTemplates", this.template)
        .then(function (res) {
          location.href = "/personal/contracts";
          loader.hide();
          return true;
        })
        .catch(function (err) {
          err.response &&
            err.response.data &&
            (app.errors = err.response.data.errors);
          loader.hide();
        });
    },
    fetchData() {
      let app = this;
      let loader = Vue.$loading.show();
      loader.hide();
      for (const type of window.contractTypes) {
        if (app.$route.params.id == type.id) {
          app.contractType = type;
          break;
        }
      }
      axios
        .get(
          "/web/v1/contractTemplates/get?contract_type_id=" +
            app.$route.params.id
        )
        .then(function (res) {
          if (res.data) {
            app.template = {
              title: res.data.title,
              proposer_header: res.data.proposer_header,
              text: res.data.text,
              accepting: res.data.accepting,
              contract_type_id: res.data.contract_type_id,
              common_title: res.data.common_title,
              recipient_title: res.data.recipient_title,
              receiver_title: res.data.receiver_title,
            };
          } else {
            app.template = {
              title: app.contractType.title,
              common_title: app.contractType.common_title,
              recipient_title: app.contractType.recipient_title,
              receiver_title: app.contractType.receiver_title,
              proposer_header: "",
              text: "",
              accepting: 0,
              contract_type_id: app.$route.params.id,
            };
          }
          loader.hide();
        })
        .catch(function (err) {
          console.log(err);
          loader.hide();
        });
    },
  },
  mounted() {
    this.fetchData();
  },
  data: function () {
    return {
      errors: {},
      contractType: {},
      template: {
        title: "",
        proposer_header: "",
        text: "",
        accepting: 0,
        contract_type_id: this.$route.params.id,
      },
    };
  },
  watch: {
    "$route.params.id": {
      handler() {
        this.fetchData();
      },
      immediate: true,
    },
  },
};
</script>
