<template>
  <section class="p-3 dropdown-send-message">
    <div class="form-group">
      <v-select
        :placeholder="__('Contragent')"
        label="title"
        :options="$root.contragents"
        v-model="contragent"
      ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
    </div>
    <div class="form-group">
      <textarea v-model="text" class="form-control"></textarea>
    </div>
    <button class="btn btn-primary" @click="sendMessage">{{ __('Send') }}</button>
  </section>
</template>
<script>
export default {
  data: function() {
    return {
      contragent: null,
      text: ""
    };
  },
  mounted() {
    var app = this;
    axios
      .get("/web/v1/contragents")
      .then(function(res) {
        app.$root.contragents = res.data;
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load contragents"),
          type: "error",
          timer: 5000
        });
      });
  },
  methods: {
    sendMessage: function() {
      let app = this;
      axios
        .post("/web/v1/dialogues", {
          id: null,
          contragent_id: app.contragent.id,
          text: app.text
        })
        .then(function(res) {
          app.text = "";
        })
        .catch(function(err) {});
    }
  }
};
</script>