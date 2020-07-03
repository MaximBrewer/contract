
<template>
  <section>
    <div v-if="!!dispute.first_review">
      <small>
        <b>отзыв компании {{dispute.contragents[0].title}} о компании {{dispute.contragents[1].title}}</b>
      </small>
      <p>{{dispute.first_review.comment}}</p>
      <hr />
    </div>
    <div v-if="!!dispute.second_review">
      <small>
        <b>отзыв компании {{dispute.contragents[1].title}} о компании {{dispute.contragents[0].title}}</b>
      </small>
      <p>{{dispute.second_review.comment}}</p>
      <hr />
    </div>

    <form v-on:submit="saveForm()" v-if="!!dispute.proposal">
      <div class="form-group">
        <label
          class="control-label"
        >{{ dispute.proposal.id ? __('Обновить предложение') : __('Добавить предложение') }}</label>
        <textarea
          v-bind:class="{ 'is-invalid':  errors.message }"
          v-model="dispute.proposal.message"
          class="form-control"
          ref="message"
        ></textarea>
        <div role="alert" class="invalid-feedback" v-if="errors.message">
          <span v-for="(error, index) in errors.message" :key="index">{{ error }}</span>
        </div>
      </div>
      <div class="button-group">
        <button class="btn btn-primary">{{ dispute.proposal.id ? __('Update') : __('Send') }}</button>
      </div>
    </form>
  </section>
</template>

<script>
export default {
  props: ["dispute"],
  data() {
    return {
      errors: {}
    };
  },
  mounted() {},
  methods: {
    saveForm() {
      event.preventDefault();
      let app = this;
      if (!app.dispute.proposal || !app.dispute.proposal.message) return false;
      if (!app.dispute.proposal.id) {
        axios
          .post("/web/v1/disputes/" + app.dispute.id + "/proposal", {
            message: app.dispute.proposal.message
          })
          .then(function(res) {
            app.dispute = res.data.dispute;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      } else {
        axios
          .patch(
            "/web/v1/disputes/" +
              app.dispute.id +
              "/proposal/" +
              app.dispute.proposal.id,
            app.dispute.proposal
          )
          .then(function(res) {
            app.dispute = res.data.dispute;
          })
          .catch(function(errors) {
            app.$fire({
              title: app.__("Error!"),
              text: errors.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      }
    }
  }
};
</script>

<style>
</style>