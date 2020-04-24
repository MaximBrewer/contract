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
            <picture-input
              ref="pictureInput"
              width="300"
              height="200"
              margin="0"
              @change="onPhotoChange"
              @remove="onPhotoRemove"
              :removable="true"
              accept="image/jpeg, image/png, image/webp"
              size="10"
              :prefill="!!auction.picture && auction.picture != 'null' ? '/storage/' + auction.picture : null"
              buttonClass="btn btn-primary"
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
import PictureInput from "../vue-picture-input";
export default {
  components: {
    PictureInput
  },
  mounted() {
    let loader = Vue.$loading.show();
    loader.hide();
  },
  data: function() {
    return { errors: {} };
  },
  props: ["auction"],
  methods: {
    onPhotoChange() {
      this.auction.picture = "";
    },
    onPhotoRemove() {
      this.auction.picture = "";
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();

      const data = new FormData();
      data.append("multiplicity_id", app.auction.multiplicity.id);
      data.append("product_id", app.auction.product.id);
      data.append("store_id", app.auction.store.id);
      data.append("autosale", app.auction.autosale);
      data.append("start_at", app.auction.start_at);
      data.append("finish_at", app.auction.finish_at);
      data.append("can_bet", app.auction.can_bet);
      data.append("mode", app.auction.mode);
      data.append("prepay", app.auction.prepay);
      data.append("comment", !!app.auction.comment ? app.auction.comment : "");
      data.append("volume", !!app.auction.volume ? app.auction.volume : "");
      data.append(
        "start_price",
        !!app.auction.start_price ? app.auction.start_price : ""
      );
      data.append("step", !!app.auction.step ? app.auction.step : "");

      let tags = [];
      for (let i in app.auction.tags) {
        tags.push(app.auction.tags[i].id);
      }
      data.append("tags", tags);

      if (!!app.$refs.pictureInput.$refs.fileInput.files[0])
        data.append("picture", app.$refs.pictureInput.$refs.fileInput.files[0]);
      else data.append("picture", app.auction.picture);

      if (!!app.auction.id) {
        axios
          .post("/web/v1/auctions/" + app.auction.id, data)
          .then(function(res) {
            app.$router.replace("/personal/auctions/show/" + res.data.id);
            app.errors = {};
            loader.hide();
          })
          .catch(function(err) {
            app.errors = {};
            if (!!err.response.data.errors)
              app.errors = err.response.data.errors;
            loader.hide();
          });
      } else {
        axios
          .post("/web/v1/auctions", data)
          .then(function(res) {
            app.$router.replace("/personal/auctions/show/" + res.data.id);
            loader.hide();
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