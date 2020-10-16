<template>
  <section class="container">
    <contract-header></contract-header>
    <div class="d-md-flex">
      <div class="mr-auto mb-3">
        <div class="dropdown">
          <button class="btn btn-secondary" type="button">
            {{ __("договор со мной") }}
          </button>
        </div>
      </div>
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
    return {};
  },
  mounted() {},
  methods: {},
};
</script>
