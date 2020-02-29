<template>
  <section>
    <div class="h2 text-center">{{ __('My reviews') }}</div>
    <div class="container" v-if="user">
      <div class="comment-form" v-for="(comment, index) in comments" :key="index">
        <form class="form" name="form">
          <div class="row p-1">
            <div class="col-sm-12">
              <star-rating
                class="float-left"
                :star-size="starSize"
                v-model="comment.votes"
                :show-rating="false"
              ></star-rating>
            </div>
          </div>
          <div class="row p-1">
            <div class="col-sm-8">
              <div class="form-group">
                <textarea
                  class="form-control sixe-100"
                  :placeholder="__('Add comment...')"
                  required
                  v-model="comment.comment"
                ></textarea>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group text-right">
                <picture-input
                  class="float-rigth"
                  ref="pictureInput5"
                  :id="'picture' + comment.id"
                  width="240"
                  height="160"
                  margin="30"
                  radius="5"
                  @remove="onPhotoRemove(comment)"
                  :removable="true"
                  accept="image/jpeg, image/png, image/webp"
                  size="10"
                  :prefill="comment.picture && comment.picture != 'null' ? '/storage/' + comment.picture : ''"
                  buttonClass="btn btn-primary btn-sm"
                  removeButtonClass="btn btn-secondary btn-sm"
                  :zIndex="1"
                  :customStrings="{
                    change:__('Change Photo'),
                    remove:__('Remove Photo'),
                    select:__('Select a Photo'),
                    upload:__('<p>Your device does not support file uploading.</p>'),
                    drag:__('Drag an image or <br>click here to select a file'),
                    tap:__('Tap here to select a photo <br>from your gallery'),
                    selected:__('<p>Photo successfully selected!</p>'),
                    fileSize:__('The file size exceeds the limit'),
                    fileType:__('This file type is not supported.'),
                    aspect:__('Landscape/Portrait')
                  }"
                ></picture-input>
              </div>
            </div>
          </div>
          <div class="row p-1">
            <div class="col-sm-8">
              <div class="form-group">
                <strong>{{ comment.to }}</strong>
                <br />
                <small>{{ comment.updated_at | formatDateTime }}</small>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group text-center">
                <input
                  type="button"
                  class="btn btn-success"
                  @click="saveComment(comment)"
                  :value="__('Save Comment')"
                />
              </div>
            </div>
          </div>
        </form>
        <hr />
      </div>
    </div>
  </section>
</template>
<script>
import StarRating from "vue-star-rating";
import PictureInput from "../vue-picture-input";
export default {
  components: { StarRating: StarRating, PictureInput },
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
    onPhotoRemove(comment) {
      comment.picture = "";
    },
    saveComment(comment) {
      var app = this;
      let loader = Vue.$loading.show();
      if (comment.comment != null && comment.comment != " ") {
        app.errorComment = null;

        const data = new FormData();
        data.append("contragent_id", comment.contragent_id);
        data.append("comment", comment.comment);
        data.append("rate", comment.votes);

        if (document.getElementById("picture" + comment.id).files[0])
          data.append(
            "picture",
            document.getElementById("picture" + comment.id).files[0]
          );
        else data.append("picture", !!comment.picture ? comment.picture : '');

        axios
          .post("/api/v1/commentsmy", data)
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