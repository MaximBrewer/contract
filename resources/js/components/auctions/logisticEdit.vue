<template>
  <section class="logistic-edit-wrapper">
    <logistic-form :logistic="logistic"></logistic-form>
  </section>
</template>
<script>
import logisticForm from "./logisticForm";
export default {
  components: {
    logisticForm: logisticForm
  },
  data: function() {
    return {
      logistic: {}
    };
  },
  created() {
    if (!this.$route.params.id) this.$router.replace("/personal/logistics");
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/logistics/" + app.$route.params.id)
      .then(function(res) {
        app.logistic = res.data.data;
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