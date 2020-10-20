<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div
          class="card-header"
          v-if="auction.contragent"
        >#{{ auction.id }} {{ auction.contragent.title }}</div>
        <ul class="list-group list-group-flush" v-if="auction.store">
          <li
            class="list-group-item"
            v-if="auction.store.federal_district"
          >{{ auction.store.federal_district.title }}</li>
          <li class="list-group-item" v-if="auction.store.region">{{ auction.store.region.title }}</li>
          <li class="list-group-item" v-if="auction.store.address">{{ auction.store.address }}</li>
          <li class="list-group-item">{{ __(auction.mode) }}</li>
          <li class="list-group-item" style="display:flex;flex-wrap:wrap;">
            <div
              v-for="(n, index) in imageList"
              :data-index="index"
              :key="index"
              style="padding:.5em .7em;"
            >
              <img :src="n.url" alt style="max-width:10em;cursor:pointer" @click="open($event)"/>
            </div>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header" v-if="auction.product">{{ auction.product.title }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="auction.contragent">{{ auction.contragent.fio }}</li>
          <li class="list-group-item" v-if="auction.contragent">{{ auction.contragent.phone }}</li>
          <li
            class="list-group-item"
            v-if="auction.multiplicity"
          >{{ __('Auction multiplicity') }}: {{ auction.multiplicity.title }}</li>
          <li class="list-group-item">{{ __('Auction step') }}: {{ auction.step }}₽</li>
          <li v-if="auction.tags.length" class="list-group-item">
            {{ __('Auction tags') }}:
            <ul>
              <li v-for="(tag, index) in auction.tags" :key="index">{{ tag.title }}</li>
            </ul>
          </li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __('Auction start') }}</div>
        <ul class="list-group list-group-flush">
          <li
            class="list-group-item"
            v-if="!!auction.start_at"
          >{{ auction.start_at | formatDateTime }}</li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __('During time') }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="$root.time">{{ $root.time | formatDateTime}}</li>
        </ul>
      </div>
      <br />
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">{{ __('Auction finish') }}</div>
        <ul class="list-group list-group-flush">
          <li
            class="list-group-item"
            v-if="!!auction.finish_at"
          >{{ auction.finish_at | formatDateTime }}</li>
        </ul>
      </div>
      <br />
    </div>
  </div>
</template>
<script>
import VuePureLightbox from "vue-pure-lightbox";
import fancyBox from "vue-fancybox";
export default {
  components: {
    VuePureLightbox,
  },
  props: {
    auction: {
      type: Object,
      default: {},
    },
  },
  data() {
    return {
      imageList: [],
    };
  },
  mounted() {
    let list = [];
    for (let img of this.auction.images) {
      this.imageList.push({
        url: img.path,
      });
    }
  },
  methods: {
    open(e) {
      fancyBox(e.target, this.imageList);
    },
  },
};
</script>