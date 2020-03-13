<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __("Send Mail") }}</div>
        <div class="card-body">
          <div class="form-group">
            <textarea class="form-control mh-200p" v-model="message"></textarea>
          </div>
          <div class="form-group">
            <div class="form-check">
              <input
                class="form-check-input"
                checked
                type="radio"
                name="whom"
                v-model="whom"
                id="toCanBet"
                value="2"
              />
              <label
                class="form-check-label"
                for="toCanBet"
              >{{ __('send to those who can bet') }}</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="whom"
                v-model="whom"
                id="toObservers"
                value="1"
              />
              <label
                class="form-check-label"
                for="toObservers"
              >{{ __('send to those who observe') }}</label>
            </div>
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="whom"
                v-model="whom"
                id="toAll"
                value="0"
              />
              <label
                class="form-check-label"
                for="toAll"
              >{{ __('send to all') }}</label>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" @click="sendMail" class="btn btn-primary mb-2">{{ __('Send') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["auction"],
  methods: {
    sendMail() {
      let app = this;
      let loader = Vue.$loading.show();
      let data = {
        message: this.message,
        whom: this.whom,
        auction: this.auction.id
      };
      axios
        .post("/api/v1/sendmail", data)
        .then(function(resp) {
          app.$fire({
            title: app.__("Your message has been sent!"),
            type: "success",
            timer: 5000
          });
          loader.hide();
        })
        .catch(function(err) {
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Something went wrong"),
            type: "error",
            timer: 5000
          });
          loader.hide();
        });
    }
  },
  data: function() {
    return {
      whom: 2,
      message:
        this.__("Hi, I`m ") +
        this.user.name +
        this.__(", a representative of the company ") +
        this.company.title +
        this.__(", phone ") +
        this.company.phone +
        this.__(", notify that the allocation (auction) of the ") +
        this.auction.product.title +
        this.__(" will start from ") +
        this.$options.filters.formatDateTime(this.auction.start_at) +
        this.__(" and end on ") +
        this.$options.filters.formatDateTime(this.auction.finish_at) +
        this.__(", will be played volume ") +
        this.auction.volume +
        this.__(" in multiplicity ") +
        this.auction.multiplicity.title +
        "\n" +
        this.__("More detailed description: ") +
        this.auction.comment
    };
  }
};
</script>