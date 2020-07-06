<template>
  <section>
    <div class="container">
      <div class="h2 text-center">{{ __('Disputes') }}</div>
      <div class="table-responsive" id="disputes">
        <table class="table table-bordered">
          <tr>
            <th style="width:20%">{{ __('Disputes') }}</th>
            <th style="width:80%">
              {{ __('Proposals') }}
              <div v-if="!!dispute.votes && !!settings.dispute_target">
                <br />
                {{ __('tagret') }} {{ dispute.votes + ' / ' + settings.dispute_target }}
              </div>
            </th>
          </tr>
          <tr>
            <td>
              <ul class="list-unstyled">
                <li v-for="(dispute, index) in disputes" :key="index">
                  <a href="javascript:void(0)" @click="setDispute(dispute.id)">#{{dispute.id}}</a>
                </li>
              </ul>
            </td>
            <td>
              <dispute-proposals :dispute="dispute"></dispute-proposals>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <dispute-discussion :dispute="dispute"></dispute-discussion>
  </section>
</template>
<script>
import disputeDiscussion from "./disputeDiscussion";
import disputeProposals from "./disputeProposals";
export default {
  components: {
    disputeDiscussion: disputeDiscussion,
    disputeProposals: disputeProposals
  },
  data() {
    return {
      disputes: [],
      dispute: {},
      settings: {}
    };
  },
  methods: {
    setDispute(id) {
      var app = this;
      app.$router.push({ path: "/personal/disputes/" + id });
      axios
        .get("/web/v1/disputes/" + id)
        .then(function(res) {
          app.dispute = res.data.dispute;
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load disputes"),
            type: "error",
            timer: 5000
          });
        });
    }
  },
  mounted() {
    var app = this;
    let id = app.$route.params.id;
    app.$root.$on("gotDispute", function(dispute) {
      if (dispute.id == app.dispute.id) app.dispute = dispute;
      let r = 0;
      for (let d of app.disputes)
        d.id == dispute.id && (d = dispute) && (r = 1);
      r || app.disputes.push(dispute);
    });
    axios
      .get("/web/v1/disputes")
      .then(function(res) {
        app.disputes = res.data.disputes;
        app.settings = res.data.settings;
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load disputes"),
          type: "error",
          timer: 5000
        });
      });
    if (id !== undefined) {
      axios
        .get("/web/v1/disputes/" + id)
        .then(function(res) {
          app.dispute = res.data.dispute;
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to load dispute"),
            type: "error",
            timer: 5000
          });
        });
    }
  }
};
</script>