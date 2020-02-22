<template>
  <section>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="h4 text-center">{{ __('New message') }}</div>
          <div class="form-group">
            <textarea v-model="text" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <button
              class="btn btn-primary"
              type="button"
              @click="sendMessage"
            >{{ __('Send message') }}</button>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="table-responsive" id="dialogues">
            <table class="table table-hover table-bordered">
              <tbody>
                <tr v-for="(item, index) in dialogue.phrases" :key="index">
                  <td class="pl-4">
                    <div class="position-relative">
                      <svg
                        class="position-absolute"
                        style="left: -1.5rem;top: 0;"
                        xmlns="http://www.w3.org/2000/svg"
                        height="24"
                        viewBox="0 0 24 24"
                        width="24"
                      >
                        <path d="M10 17l5-5-5-5v10z" />
                        <path d="M0 24V0h24v24H0z" fill="none" />
                      </svg>
                      <strong>
                        <router-link
                          v-if="item.contragent.id != company.id"
                          :to="'/personal/contragents/show/' + item.contragent.id "
                        >{{ item.contragent.title }}</router-link>
                        <router-link
                          v-if="item.contragent.id == company.id"
                          :to="'/personal/company'"
                        >{{ item.contragent.title }}</router-link>
                        <small>{{ item.created_at }}</small>
                      </strong>
                      <div>{{ item.text }}</div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import VueRouter from "vue-router";
export default {
  data: function() {
    return {
      dialogue: [],
      phrases: [],
      search: "",
      text: ""
    };
  },
  mounted: function() {
    let app = this;
    let id = app.$route.params.id;
    axios
      .get("/api/v1/dialogue/" + id)
      .then(function(res) {
        app.dialogue = res.data.data;
        app.listenForDialog();
      })
      .catch(function(err) {});
  },
  methods: {
    sendMessage: function() {
      let app = this;
      let id = app.$route.params.id;
      axios
        .post("/api/v1/dialogues", { id: id, text: app.text })
        .then(function(res) {})
        .catch(function(err) {});
    },
    listenForDialog() {
      let app = this;
      Echo.channel("dialog." + app.dialogue.id).listen("Dialog", function(e) {
        console.log(e);
      });
    }
  }
};
</script>