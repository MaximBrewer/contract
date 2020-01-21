<template>
  <div class="comments-app">
    <h3>{{ __('Comments') }}</h3>
    <!-- From -->
    <div class="comment-form" v-if="user">
      <form class="form" name="form">
        <div class="form-group">
          <div class="clearfix">
            <star-rating class="float-right" @rating-selected="rate" :star-size="starSize" v-model="rating" :show-rating="false"></star-rating>
          </div>
          <textarea
            class="form-control sixe-200"
            :placeholder="__('Add comment...')"
            required
            v-model="message"
          ></textarea>
          <span class="input" v-if="errorComment" style="color:red">{{errorComment}}</span>
        </div>
        <div class="form-group text-right">
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
      <div class="card">
        <div class="card-header">
          <star-rating class="float-right" :star-size="starSizeSmall" v-model="comment.votes" :show-rating="false" :read-only="true"></star-rating>
          <strong>{{ comment.writer }}</strong>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><p>{{comment.comment}}</p>
            <small>{{ comment.updated_at | formatDateTime }}</small>
          </li>
        </ul>
      </div><br>
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
    let app = this;
    app.isLoading = true;
    app.fetchComments();
  },
  data() {
    return {
      contragent: this.$route.params.id,
      comments: [],
      rating: 0,
      message: null,
      user: window.user,
      errorComment: null,
      starSize: 20,
      starSizeSmall: 15
    };
  },
  methods: {
    rate() {},
    fetchComments() {
      let app = this;
      axios
        .get(
          "/api/v1/comments/" +
            app.contragent +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.comments = resp.data[0];
          app.rating = resp.data[1];
          app.message = resp.data[2].comment;
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
              rate: app.rating
            }
          )
          .then(function(resp) {
            app.comments = resp.data[0];
            app.rating = resp.data[1];
            app.message = resp.data[2].comment;
          });
      } else {
        app.errorComment = "Please enter a comment to save";
      }
    }
  }
};
</script>