<template>
  <section class="target-edit-wrapper">
    <div class="container">
      <div class="h2 text-center">{{ __('Create target') }}</div><br/>
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
  mounted() {
    this.$root.target = {
      store: null,
      volume: null,
      remain: null,
      product: null,
      multiplicity: null
    };
  }
};
</script>