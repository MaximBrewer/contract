<template>
  <div class="comments-app">
    <h1>{{ __('Comments') }}</h1>
    <!-- From -->
    <div class="comment-form" v-if="user">
      <form class="form" name="form">
        <div class="form-row">
          <star-rating v-model="contragent.rating"></star-rating>
          <textarea class="input" :placeholder="__('Add comment...')" required v-model="message"></textarea>
          <span class="input" v-if="errorComment" style="color:red">{{errorComment}}</span>
        </div>
        <div class="form-row">
          <input
            type="button"
            class="btn btn-success"
            @click="saveComment"
            :value="__('Add Comment')"
          />
        </div>
      </form>
    </div>
    <!-- Comments List -->
    <div class="comments" v-for="(comment,index) in comments" :key="index">
      <!-- Comment -->
      <div class="comment">
        <!-- Comment Box -->
        <div class="comment-box">
          <div class="comment-text">{{comment.comment}}</div>
          <div class="comment-footer">
            <div class="comment-info">
              <span class="comment-author">
                <em>{{ comment.name}}</em>
              </span>
              <span class="comment-date">{{ comment.date}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var _ = require("lodash");
import StarRating from "vue-star-rating";

export default {
  components: {
    StarRating: StarRating
  },
  mounted() {
    let app = this
    app.isLoading = true;
    app.fetchComments();
  },
  data() {
    return {
      contragent: app.$route.params.id,
      comments: [],
      message: null,
      user: window.user
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      axios
        .get(
          "/api/v1/comments/?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.comments = resp.data;
          app.isLoading = false;
        })
        .catch(function(e) {
          console.log(e);
          app.isLoading = false;
        });
    },
    saveComment() {
      var app = this;
      if (app.message != null && app.message != " ") {
        app.errorComment = null;
        axios
          .post(
            "/api/v1/comments?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              contragent_id: app.contragent,
              comment: app.message,
              user_id: app.user.id
            }
          )
          .then(function(resp) {
            if (resp.data.status) {
              app.comments = resp.data;
            }
          });
      } else {
        app.errorComment = "Please enter a comment to save";
      }
    }
  },
};
</script>