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
import StarRating from "vue-star-rating";
export default {
  components: { StarRating: StarRating },
  mounted() {
    this.fetchComments();
  },
  data() {
    return {
      comments: [],
      starSize: 20
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/api/v1/comments")
        .then(function(resp) {
          app.comments = resp.data[0];
          loader.hide();
        })
        .catch(function(e) {
          loader.hide();
        });
    },
    saveComment(comment) {
      var app = this;
      let loader = Vue.$loading.show();
      if (comment.comment != null && comment.comment != " ") {
        app.errorComment = null;
        axios
          .post("/api/v1/commentsmy", {
            contragent_id: comment.contragent_id,
            comment: comment.comment,
            rate: comment.votes
          })
          .then(function(resp) {
            loader.hide();
            app.$fire({
              title: app.__("Review is successfully updated!"),
              type: "success",
              timer: 5000
            });
            app.comments = resp.data[0];
          })
          .catch(function(err) {
            loader.hide();
            app.$fire({
              title: app.__("Error!"),
              text: err.response.data.message,
              type: "error",
              timer: 5000
            });
          });
      } else {
        app.errorComment = __("Please enter a comment to save");
      }
    }
  }
};
</script>