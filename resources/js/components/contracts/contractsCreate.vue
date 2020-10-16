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
        <div>, с одной стороны, и</div>
      </div>
      <div class="form-group">
        <input
          type="text"
          disabled
          class="form-control"
          placeholder="ООО ..., в лице ..., действующий на основании ... (не заполнять)"
        />
        <div>
          , с другой стороны, а вместе именуемые "Стороны", заключили договор о
          нижеследующем:
        </div>
      </div>

      <div class="form-group">
        <textarea
          v-model="template.text"
          class="form-control"
          rows="30"
          :placeholder="__('Текст договора')"
          v-bind:class="{ 'is-invalid': errors.text }"
        ></textarea>
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
export default {
  components: {
    switchCheckbox: switchCheckbox,
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
          app.$router.replace("/personal/contracts");
          loader.hide();
          return true;
        })
        .catch(function (err) {
          app.errors = err.response.data.errors;
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
            };
          } else {
            app.template = {
              title: app.contractType.title,
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
