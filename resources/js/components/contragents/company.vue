<template>
  <section class="contragent-edit-wrapper">
    <contragent-form></contragent-form>
    <div class="container description">
      <p>
        Теперь для закупки необходимых товаров 
        <router-link :to="{name: 'createTarget'}">создайте свои Цели - Тендеры</router-link>
      </p>
      <p>
        После этого 
        <router-link :to="{name: 'createAuction'}">Создайте свой аукцион(ы)</router-link>
      </p>
      <p>
        Как это сделать - посмотрите Об аукционе
        <a
          href="https://www.cross-contract.ru/aukciony"
        >Cross-Contract</a>
      </p>
    </div>
  </section>
</template>
<script>
import contragentForm from "./contragentForm";
export default {
  components: {
    contragentForm: contragentForm
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/company")
      .then(function(res) {
        app.$root.contragent = res.data;
        loader.hide();
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: err.response.data.message,
          type: "error",
          timer: 5000
        });
        loader.hide();
      });
  }
};
</script>