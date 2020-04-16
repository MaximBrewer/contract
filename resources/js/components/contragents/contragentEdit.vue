<template>
  <section class="contragent-edit-wrapper">
    <contragent-form></contragent-form>
  </section>
</template>
<script>
import contragentForm from "./contragentForm";
export default {
  components: {
    contragentForm: contragentForm
  },
  created() {
    if (!this.$route.params.id) this.$router.replace("/personal/contragents");
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/contragents/" + app.$route.params.id)
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
      });
  }
};
</script>