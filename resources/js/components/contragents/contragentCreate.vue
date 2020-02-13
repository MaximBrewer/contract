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
  mounted() {
    this.$root.contragent = {
      title: "",
      inn: "",
      typeIds: [],
      fio: "",
      phone: "",
      legal_address: "",
      federal_district: 0,
      region: 0,
      types: [],
      stores: []
    };
  }
};
</script>