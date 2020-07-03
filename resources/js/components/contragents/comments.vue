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
              <vue-upload-multiple-image
                dragText="перетащите изображения (несколько)"
                browseText="(или) выберите"
                primaryText="по умолчанию"
                markIsPrimaryText="Установить по умолчанию"
                popupText="Это изображение будет отображаться по умолчанию"
                dropText="Перетащите свой файл сюда ..."
                @upload-success="uploadImageSuccess"
                @before-remove="beforeRemove"
                @edit-image="editImage"
                :showPrimary="false"
                :data-images="images"
                idUpload="imagesComments"
                editUpload="myIdEdit"
              ></vue-upload-multiple-image>
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
          <a
            v-if="comment.writer == $root.user.contragents[0].id || comment.contragent_id == $root.user.contragents[0].id"
            href="javascript:void(0)"
            class="btn btn-primary float-right btn-sm"
            @click="openDispute"
            style="margin-right:1rem;"
          >{{ __('Open Dispute') }}</a>
          <strong>{{ comment.by }}</strong>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="row">
              <div class="col-sm-8">
                <p>{{comment.comment}}</p>
                <small>{{ comment.updated_at | formatDateTime }}</small>
              </div>
              <div class="col-sm-4" v-if="comment.images">
                <vue-pure-lightbox
                  style="width: 100%;max-width:100px;margin: 0 auto;"
                  :thumbnail="toSlider(comment.images)[0]"
                  :images="toSlider(comment.images)"
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
import VueUploadMultipleImage from "../vue-upload-multiple-image";
import VuePureLightbox from "vue-pure-lightbox";
export default {
  components: {
    VueUploadMultipleImage,
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
      formData: new FormData(),
      images: [],
      starSize: 20,
      starSizeSmall: 15
    };
  },
  methods: {
    openDispute() {
      let app = this;
      axios
        .patch("/web/v1/disputes/", {
          contragent_id: this.$route.params.id
        })
        .then(function(res) {
          app.$router.push({
            path: "/personal/disputes/" + res.data.dispute.id
          });
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
    toSlider(images) {
      let sim = [];
      for (let s of images) {
        sim.push(s.path);
      }
      return sim;
    },
    fetchComments() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/comments/" + app.contragent)
        .then(function(res) {
          app.comments = res.data[0];
          app.rating = res.data[1];
          app.message = res.data[2].comment;
          app.images = res.data[2].images;
          loader.hide();
        })
        .catch(function(e) {
          loader.hide();
        });
    },
    uploadImageSuccess(formData, index, fileList) {
      for (var pair of formData.entries()) {
        this.formData.set("images[" + index + "]", pair[1]);
      }
    },
    beforeRemove(index, done, fileList) {
      this.formData.delete("images[" + index + "]");
      var r = confirm("remove image");
      if (r == true) {
        done();
      }
    },
    editImage(formData, index, fileList) {
      for (var pair of formData.entries()) {
        this.formData.set("images[" + index + "]", pair[1]);
      }
    },
    saveComment() {
      var app = this;
      if (app.message != null && app.message != " ") {
        let loader = Vue.$loading.show();
        app.errorComment = null;

        this.formData.set("contragent_id", app.contragent);
        this.formData.set("comment", app.message);
        this.formData.set("rate", app.rating);

        this.formData.delete("pics[]");

        for (let img of this.images) {
          if (img.id) this.formData.append("pics[]", img.id);
        }

        axios.post("/web/v1/comments", this.formData).then(function(res) {
          app.comments = res.data[0];
          app.rating = res.data[1];
          app.message = res.data[2].comment;
          loader.hide();
          app.formData = new FormData();
        });
      }
    }
  }
};
</script>