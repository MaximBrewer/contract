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
        </div>
        <div class="form-group">
          <div class="row p-1">
            <div class="col-sm-8">
              <textarea
                class="form-control sixe-200"
                :placeholder="__('Add comment...')"
                required
                v-model="message"
              ></textarea>
              <span class="input" v-if="errorComment" style="color:red">{{errorComment}}</span>
            </div>
            <div class="col-sm-4">
              <picture-input
                class="float-rigth"
                id="pictureInput"
                width="300"
                height="200"
                margin="30"
                radius="5"
                @remove="onPhotoRemove()"
                :removable="true"
                accept="image/jpeg, image/png, image/webp"
                size="10"
                :prefill="!!picture ? '/storage/' + picture : null"
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
        <div class="form-group text-right">
          <input
            type="button"
            class="btn btn-success"
            @click="saveComment"
            :value="message ? __('Save Comment') :  __('Add Comment')"
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
            <div class="row">
              <div class="col-sm-8">
                <p>{{comment.comment}}</p>
                <small>{{ comment.updated_at | formatDateTime }}</small>
              </div>
              <div class="col-sm-4" v-if="comment.picture">
                <vue-pure-lightbox
                  style="width: 20em"
                  :thumbnail="'/storage/' + comment.picture"
                  :images="['/storage/' + comment.picture]"
                ></vue-pure-lightbox>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <br />
    </div>
  </div>
</template>
<script>
import PictureInput from "../vue-picture-input";
import VuePureLightbox from 'vue-pure-lightbox';
export default {
  components: {
    PictureInput,
    VuePureLightbox
  },
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
      picture: null,
      starSize: 20,
      starSizeSmall: 15
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/comments/" + app.contragent)
        .then(function(res) {
          app.comments = res.data[0];
          app.rating = res.data[1];
          app.message = res.data[2].comment;
          app.picture = res.data[2].picture;
          loader.hide();
        })
        .catch(function(e) {
          loader.hide();
        });
    },
    onPhotoRemove(comment) {
      this.picture = "";
    },
    saveComment() {
      var app = this;
      if (app.message != null && app.message != " ") {
        let loader = Vue.$loading.show();
        app.errorComment = null;

        const data = new FormData();
        data.append("contragent_id", app.contragent);
        data.append("comment", app.message);
        data.append("rate", app.rating);

        if (document.getElementById("pictureInput").files[0])
          data.append(
            "picture",
            document.getElementById("pictureInput").files[0]
          );
        else data.append("picture", !!app.picture ? app.picture : '');

        axios.post("/web/v1/comments", data).then(function(res) {
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