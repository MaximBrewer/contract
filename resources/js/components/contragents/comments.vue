<template>
  <div class="comments-app">
    <h3>{{ __('Comments') }}</h3>
    <!-- From -->
    <div class="comment-form" v-if="user">
      <form class="form" name="form">
        <div class="form-group">
          <div class="clearfix">
            <star-rating
              class="float-right"
              :star-size="starSize"
              v-model="rating"
              :show-rating="false"
            ></star-rating>
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
          <star-rating
            class="float-right"
            :star-size="starSizeSmall"
            v-model="comment.votes"
            :show-rating="false"
            :read-only="true"
          ></star-rating>
          <strong>{{ comment.by }}</strong>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <p>{{comment.comment}}</p>
            <small>{{ comment.updated_at | formatDateTime }}</small>
          </li>
        </ul>
      </div>
      <br />
    </div>
  </div>
</template>
<script>
export default {
  mounted() {
    let app = this;
    app.fetchComments();
  },
  data() {
    return {
      contragent: this.$route.params.id,
      comments: [],
      rating: 0,
      message: null,
      errorComment: null,
      starSize: 20,
      starSizeSmall: 15
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/api/v1/comments/" + app.contragent)
        .then(function(res) {
          app.comments = res.data[0];
          app.rating = res.data[1];
          app.message = res.data[2].comment;
          loader.hide();
        })
        .catch(function(e) {
          console.log(e);
          loader.hide();
        });
    },
    saveComment() {
      var app = this;
      if (app.message != null && app.message != " ") {
        let loader = Vue.$loading.show();
        app.errorComment = null;
        axios
          .post("/api/v1/comments", {
            contragent_id: app.contragent,
            comment: app.message,
            rate: app.rating
          })
          .then(function(res) {
            app.comments = res.data[0];
            app.rating = res.data[1];
            app.message = res.data[2].comment;
            loader.hide();
          });
      }
    }
  }
};
</script>