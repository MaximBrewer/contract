<template>
  <section>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="h2 text-center">{{ __('Messages') }}</div>
          <div class="input-group mb-3">
            <input v-model="search" placeholder class="form-control" />
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">{{ __('Search') }}</button>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="table-responsive" id="dialogues">
            <table class="table table-hover table-bordered">
              <tbody>
                <tr v-for="(dialogue, index) in dialoguesList" :key="index">
                  <td>
                    <h6>{{ dialogue.contragent.title }}</h6>
                    <router-link
                      :to="'/personal/dialogue/show/' + dialogue.id"
                    >{{ __("Messages:") }} {{ dialogue.count }}</router-link>
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
      dialogues: [],
      dialoguesList: [],
      search: ""
    };
  },
  mounted: function() {
    let app = this;
    axios
      .get("/api/v1/dialogues")
      .then(function(res) {
        app.dialogues = res.data.data;
        app.dialoguesList = res.data.data;
      })
      .catch(function(err) {});
  }
};
</script>