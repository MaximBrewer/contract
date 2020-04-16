<template>
  <section class="target-edit-wrapper">
    <div class="container">
      <div class="h2 text-center">{{ __('Edit target') }}</div><br/>
    </div>
    <target-form></target-form>
  </section>
</template>
<script>
import targetForm from "./targetForm";
export default {
  components: {
    targetForm: targetForm
  },
  created() {
    if (!this.$route.params.id) this.$router.replace("/personal/auctions/bid");
  },
  mounted() {
    let app = this;
    axios
      .get("/web/v1/targets/" + app.$route.params.id)
      .then(function(res) {
        app.$root.target = res.data;
      })
      .catch(function() {
        app.$router.push("/personal/auctions");
      });
  }
};
</script>