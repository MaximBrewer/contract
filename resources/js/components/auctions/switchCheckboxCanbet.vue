<template>
  <div
    class="checkbox-toggle"
    role="checkbox"
    @click.stop="toggle"
    tabindex="0"
    :aria-checked="toggled"
  >
    <div class="checkbox-slide" :class="classes">
      <div class="checkbox-switch" :class="classes"></div>
    </div>
  </div>
</template>
<script>
export default {
  watch: {
    value: function(val) {
      this.toggled = !!val;
    }
  },
  computed: {
    classes() {
      return {
        checked: this.toggled,
        unchecked: !this.toggled,
        disabled: this.disabled
      };
    }
  },
  data() {
    return {
      toggled: !!this.value
    };
  },
  methods: {
    toggleBidder(req) {
      let app = this;
      axios
        .post("/web/v1/auctions/bidder/toggle", req)
        .then(function(resp) {
          // bet.delete();
        })
        .catch(function(errors) {
          app.toggled = !app.toggled;
          app.$emit("input", app.toggled * 1);
          app.$fire({
            title: app.__("Error!"),
            text: errors.response.data.message,
            type: "error",
            timer: 5000
          });
        });
    },
    toggle(e) {
      if (this.disabled || e.keyCode === 9) {
        // not if disabled or tab is pressed
        e.stop();
      }
      var app = this;
      this.toggled = !this.toggled;
      this.$emit("input", this.toggled * 1);
      this.toggleBidder({
        can_bet: !!app.toggled,
        observer: app.bidder.observer,
        contragent_id: app.bidder.id,
        auction_id: app.auction.id
      });
    }
  },
  props: {
    auction: {
      type: Object,
      default: {}
    },
    bidder: {
      type: Object,
      default: {}
    },
    value: {
      type: Number,
      default: 0
    }
  }
};
</script>
