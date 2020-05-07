<template>
  <!-- 'future', 'price2day' -->
  <div class="container">
    <form v-on:submit="saveForm()">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Mode') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': errors.mode }"
              :options="[{code: 'future', label: 'впрок'}, {code: 'price2day', label: 'price2day'}]"
              :reduce="cod => cod.code"
              :cod="auction.mode"
              v-model="auction.mode"
              :searchable="false"
              :clearable="false"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors.mode">
              <span v-for="(error, index) in errors.mode" :key="index">{{ error }}</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('New can bet?') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': errors.can_bet }"
              :options="[{code: 'no', label: 'Нет'}, {code: 'yes', label: 'Да'}]"
              :reduce="cod => cod.code"
              :cod="auction.can_bet"
              v-model="auction.can_bet"
              :searchable="false"
              :clearable="false"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors.can_bet">
              <span v-for="(error, index) in errors.can_bet" :key="index">{{ error }}</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Autosale') }}</label>
            <v-select
              v-bind:class="{ 'is-invalid': errors['types.id'] }"
              :options="[{code: 'no', label: 'Нет'}, {code: 'yes', label: 'Да'}]"
              :reduce="cod => cod.code"
              :cod="auction.autosale"
              v-model="auction.autosale"
              :searchable="false"
              :clearable="false"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <div role="alert" class="invalid-feedback" v-if="errors.autosale">
              <span v-for="(error, index) in errors.autosale" :key="index">{{ error }}</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction lot') }}</label>
            <v-select
              label="title"
              :options="$root.products"
              :searchable="false"
              v-model="auction.product"
              v-bind:class="{ 'is-invalid': errors['product.id'] }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <span role="alert" class="invalid-feedback" v-if="errors['product.id']">
              <strong v-for="(error, index) in errors['product.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction multiplicity') }}</label>
            <v-select
              label="title"
              :options="$root.multiplicities"
              :searchable="false"
              v-model="auction.multiplicity"
              v-bind:class="{ 'is-invalid': errors['multiplicity.id'] }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <span role="alert" class="invalid-feedback" v-if="errors['multiplicity.id']">
              <strong v-for="(error, index) in errors['multiplicity.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction store') }}</label>
            <v-select
              label="address"
              :options="$root.stores"
              :searchable="false"
              v-model="auction.store"
              v-bind:class="{ 'is-invalid': errors['store.id'] }"
            >
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
            <span role="alert" class="invalid-feedback" v-if="errors['store.id']">
              <strong v-for="(error, index) in errors['store.id']" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction Volume') }}</label>
            <input
              step=".01"
              type="number"
              v-model="auction.volume"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.volume }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.volume">
              <strong v-for="(error, index) in errors.volume" :key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">{{ __('Auction tags') }}</label>
            <v-select label="title" :multiple="true" :options="$root.tags" v-model="auction.tags">
              <div slot="no-options">{{ __('No Options Here!') }}</div>
            </v-select>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction Start Price') }}</label>
            <input
              step=".01"
              type="number"
              v-model="auction.start_price"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.start_price }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.start_price">
              <strong v-for="(error, index) in errors.start_price" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction Step') }}</label>
            <input
              step=".01"
              type="number"
              v-model="auction.step"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.step }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.step">
              <strong v-for="(error, index) in errors.step" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction start') }}</label>
            <datetime
              type="datetime"
              class="theme-primary"
              zone="Europe/Moscow"
              value-zone="Europe/Moscow"
              input-class="form-control"
              v-model="auction.start_at"
              v-bind:class="{ 'is-invalid': errors.start_at }"
            ></datetime>
            <span role="alert" class="invalid-feedback" v-if="errors.start_at">
              <strong v-for="(error, index) in errors.start_at" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction finish') }}</label>
            <datetime
              type="datetime"
              zone="Europe/Moscow"
              value-zone="Europe/Moscow"
              class="theme-primary"
              input-class="form-control"
              v-model="auction.finish_at"
              v-bind:class="{ 'is-invalid': errors.finish_at }"
            ></datetime>
            <span role="alert" class="invalid-feedback" v-if="errors.finish_at">
              <strong v-for="(error, index) in errors.finish_at" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction Prepay') }}</label>
            <input
              step=".01"
              type="number"
              v-model="auction.prepay"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.prepay }"
            />
            <span role="alert" class="invalid-feedback" v-if="errors.prepay">
              <strong v-for="(error, index) in errors.prepay" :key="index">{{ error }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label class="control-label">{{ __('Auction comment') }}</label>
            <textarea
              v-model="auction.comment"
              class="form-control"
              v-bind:class="{ 'is-invalid': errors.comment }"
            ></textarea>
            <span role="alert" class="invalid-feedback" v-if="errors['auction.comment']">
              <strong v-for="(error, index) in errors.comment" :key="index">{{ error }}</strong>
            </span>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
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
              :data-images="auction.images"
              idUpload="imagesComments"
              editUpload="myIdEdit"
            ></vue-upload-multiple-image>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group text-right">
            <button class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import VueUploadMultipleImage from "../vue-upload-multiple-image";
export default {
  components: {
    VueUploadMultipleImage
  },
  toSlider(images) {
    let sim = [];
    for (let s of images) {
      sim.push(s.path);
    }
    return sim;
  },
  mounted() {
    let loader = Vue.$loading.show();
    loader.hide();
  },
  data: function() {
    return {
      errors: {},
      formData: new FormData()
    };
  },
  props: ["auction"],
  methods: {
    onPhotoChange() {
      this.auction.picture = "";
    },
    onPhotoRemove() {
      this.auction.picture = "";
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
    saveForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();
      if (
        !app.auction.multiplicity ||
        !app.auction.product ||
        !app.auction.store
      ) {
        loader.hide();
        return false;
      }

      this.formData.set("multiplicity_id", app.auction.multiplicity.id);
      this.formData.set("product_id", app.auction.product.id);
      this.formData.set("store_id", app.auction.store.id);
      this.formData.set("autosale", app.auction.autosale);
      this.formData.set("start_at", app.auction.start_at);
      this.formData.set("finish_at", app.auction.finish_at);
      this.formData.set("can_bet", app.auction.can_bet);
      this.formData.set("mode", app.auction.mode);
      this.formData.set("prepay", app.auction.prepay);
      this.formData.set(
        "comment",
        !!app.auction.comment ? app.auction.comment : ""
      );
      this.formData.set(
        "volume",
        !!app.auction.volume ? app.auction.volume : ""
      );
      this.formData.set(
        "start_price",
        !!app.auction.start_price ? app.auction.start_price : ""
      );
      this.formData.set("step", !!app.auction.step ? app.auction.step : "");

      let tags = [];
      for (let i in app.auction.tags) {
        tags.push(app.auction.tags[i].id);
      }
      this.formData.set("tags", tags);

      this.formData.delete("pics[]");

      if (this.auction.images)
        for (let img of this.auction.images) {
          if (img.id) this.formData.append("pics[]", img.id);
        }

      if (!!app.auction.id) {
        axios
          .post("/web/v1/auctions/" + app.auction.id, this.formData)
          .then(function(res) {
            app.$router.replace("/personal/auctions/show/" + res.data.data.id);
            app.errors = {};
            loader.hide();
            app.formData = new FormData();
          })
          .catch(function(err) {
            app.errors = {};
            if (!!err.response.data.errors)
              app.errors = err.response.data.errors;
            loader.hide();
          });
      } else {
        axios
          .post("/web/v1/auctions", this.formData)
          .then(function(res) {
            app.$router.replace("/personal/auctions/show/" + res.data.data.id);
            loader.hide();
            app.formData = new FormData();
          })
          .catch(function(err) {
            app.errors = {};
            if (!!err.response.data.errors)
              app.errors = err.response.data.errors;
            loader.hide();
          });
      }
    }
  }
};
</script>