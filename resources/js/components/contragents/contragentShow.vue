<template>
  <section class="contragent-edit-wrapper">
    <div class="container" v-if="$root.contragent">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <strong>{{ $root.contragent.title }}</strong>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item" v-if="$root.contragent.federal_district">
                <strong>{{ __('Contragent region') }}:</strong>
                {{ $root.contragent.federal_district.title }}
              </li>
              <li class="list-group-item" v-if="$root.contragent.region">
                <strong>{{ __('Contragent federal district') }}:</strong>
                {{ $root.contragent.region.title }}
              </li>
              <li class="list-group-item">
                <strong>{{ __('Contragent type') }}:</strong>
                <span v-for="(type, index) in $root.contragent.types" :key="index">{{ type.title }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ __('Contragent Legal address') }}:</strong>
                {{ $root.contragent.legal_address }}
              </li>
              <li class="list-group-item">
                <strong>{{ __('Contragent contact') }}:</strong>
                {{ $root.contragent.fio }}
              </li>
              <li class="list-group-item">
                <strong>{{ __('Contragent phone') }}:</strong>
                {{ $root.contragent.phone }}
              </li>
              <li class="list-group-item">
                <strong>{{ __('Contragent TIN') }}:</strong>
                {{ $root.contragent.inn }}
              </li>
            </ul>
          </div>
          <br />
        </div>
      </div>
      <div class="h4">{{ __('Contragent stores') }}</div>
      <div class="row">
        <div class="col-md-6" v-for="(store, index) in $root.contragent.stores" :key="index">
          <div class="card">
            <div class="card-header">{{ store.address }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
              >{{ __('Store federal district #', {store: index + 1}) }}: {{ store.federal_district.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Store region #', {store: index + 1}) }}: {{ store.region.title }}</li>
              <li
                class="list-group-item"
              >{{ __('Store coords #', {store: index + 1}) }}: {{ store.coords }}</li>
            </ul>
          </div>
        </div>
      </div>
      <comment></comment>
    </div>
  </section>
</template>
<script>
import Comment from "./comments.vue";
export default {
  components: {
    Comment: Comment
  },
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    let id = app.$route.params.id;
    axios
      .get("/api/v1/contragents/" + id)
      .then(function(resp) {
        app.$root.contragent = resp.data;
        loader.hide();
      })
      .catch(function(err) {
        cconsole.log(err);
        loader.hide();
      });
  }
};
</script>