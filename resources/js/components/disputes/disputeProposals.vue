
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

    <div id="proposals">
      <h5>{{ __('Предложения и голосование по выходу из проблемы:') }}</h5>
      <hr />
      <ul class="list-unstyled">
        <li v-for="(proposal, index) in dispute.proposals" :key="index">
          <div>
            {{ proposal.message }}
            <br />
            <div class="d-flex justify-content-between">
              <div>
                <small>{{ proposal.created_at | formatChatTime}} / {{ proposal.contragent.title }}</small>
              </div>
              <div>
                <a
                  href="javascript:void(0);"
                  @click="toggleVote(proposal.id)"
                  class="text-decoration-none"
                  v-tooltip="!proposal.vote ? __('Нравится') : __('Не нравится')"
                >
                  <i
                    aria-hidden="true"
                    class="mdi"
                    v-bind:class="{ 'mdi-thumb-up': !!proposal.vote, 'mdi-thumb-up-outline': !proposal.vote }"
                  ></i>
                  &nbsp;&nbsp;{{ proposal.votes.length }}
                </a>
              </div>
            </div>
          </div>
          <hr />
        </li>
      </ul>
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
    <div v-if="dispute != undefined && dispute && !dispute.sent && !!dispute.contragents && dispute.contragents.length > 1">
      <form
        v-on:submit="sendEmails()"
        v-if="dispute.contragents[0].id == user.contragents[0].id || dispute.contragents[1].id == user.contragents[0].id"
      >
        <div class="form-group">
          <label class="control-label">{{ __('Отправить приглашение') }}</label>
          <textarea v-model="dispute.message" class="form-control"></textarea>
        </div>
        <div class="button-group">
          <button class="btn btn-primary">{{ __('Send') }}</button>
        </div>
      </form>
    </div>
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
    sendEmails() {
      axios
        .patch("/web/v1/disputes/" + app.dispute.id + "/emails")
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
    },
    toggleVote(id) {
      event.preventDefault();
      let app = this;
      axios
        .patch(
          "/web/v1/disputes/" + app.dispute.id + "/proposal/" + id + "/vote"
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
    },
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