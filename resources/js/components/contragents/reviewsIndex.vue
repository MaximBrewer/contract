<template>
  <section>
    <div class="h2 text-center">{{ __('My reviews') }}</div>
    <div class="container" v-if="user">
      <div class="comment-form" v-for="(comment, index) in comments" :key="index">
        <form class="form" name="form">
          <div class="form-group">
            <div class="clearfix">
              <star-rating
                class="float-right"
                @rating-selected="rate"
                :star-size="starSize"
                v-model="comment.votes"
                :show-rating="false"
              ></star-rating>
            </div>
            <textarea
              class="form-control sixe-100"
              :placeholder="__('Add comment...')"
              required
              v-model="comment.comment"
            ></textarea>
          </div>
          <div class="form-group">
            <input
              type="button"
              class="btn btn-success float-right"
              @click="saveComment(comment)"
              :value="__('Save Comment')"
            />
            <strong>{{ comment.to }}</strong>
            <br />
            <small>{{ comment.updated_at | formatDateTime }}</small>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
<script>
var _ = require("lodash");
import StarRating from "vue-star-rating";
export default {
  components: {
    StarRating: StarRating
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    app.fetchComments();
  },
  data() {
    return {
      comments: [],
      user: window.user,
      starSize: 20
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      axios
        .get(
          "/api/v1/comments?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.comments = resp.data[0];
          loader.hide();
        })
        .catch(function(e) {
          console.log(e);
          loader.hide();
        });
    },
    saveComment(comment) {
      var app = this;
      if (comment.comment != null && comment.comment != " ") {
        app.errorComment = null;
        axios
          .post(
            "/api/v1/commentsmy?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              contragent_id: comment.contragent_id,
              comment: comment.comment,
              rate: comment.votes
            }
          )
          .then(function(resp) {
            app.comments = resp.data[0];
          });
      } else {
        app.errorComment = "Please enter a comment to save";
      }
    }
  }
};
</script>