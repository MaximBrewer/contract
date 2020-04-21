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
  mounted: function() {
    let app = this;
  },
  data: function() {
    return {
      logistic: {
        contragent: null,
        federal_district: null,
        region: null,
        purpose: null,
        address: null,
        capacity: null,
        title: null,
        gosznak: null,
        description: null,
        available_from: null,
        coords: null
      }
    };
  }
};
</script>