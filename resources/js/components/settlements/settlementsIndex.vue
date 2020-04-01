<template>
  <section id="paymentForm" class="container">
    <div class="p-3">
      <h1>
        Баланс
        <span class="badge badge-secondary">{{ user.balance }}</span>
      </h1>
    </div>
    <form method="POST">
      <div class="form-group">
        <input
          step=".01"
          name="sum"
          type="number"
          data-type="number"
          v-model="form.sum"
          class="form-control"
          v-bind:class="{ 'is-invalid': errors.sum }"
        />
      </div>
      <div class="form-check form-group">
        <input
          class="form-check-input"
          id="yandexForm"
          name="yandexForm"
          type="radio"
          value="yandex"
          v-model="form.method"
          :disabled="!(form.sum * 1) || form.sum > 15000"
        />
        <label class="form-check-label" for="yandexForm">Яндекс.Деньги</label>
      </div>
      <div class="form-check form-group">
        <input
          class="form-check-input"
          id="invoiceForm"
          name="invoiceForm"
          type="radio"
          value="invoice"
          v-model="form.method"
          :disabled="!(form.sum * 1)"
        />
        <label class="form-check-label" for="invoiceForm">Счет</label>
      </div>
      <input type="hidden" name="receiver" value="410011406191126" />
      <input type="hidden" name="label" value="$order_id" />
      <input type="hidden" name="quickpay-form" value="donate" />
      <input type="hidden" name="targets" value="транзакция {order_id}" />
      <input type="hidden" name="paymentType" value="AC" />
      <button
        @click="sendForm"
        class="btn btn-primary"
      >{{ form.method == 'invoice' ? 'Сформировать счет' : 'Оплатить'}}</button>
    </form>
  </section>
</template>
<script>
export default {
  data: function() {
    return { errors: {}, form: {} };
  },
  methods: {
    sendForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();

      const data = new FormData();
      data.append("multiplicity_id", app.auction.multiplicity.id);
      data.append("product_id", app.auction.product.id);
      data.append("store_id", app.auction.store.id);
      data.append("start_at", app.auction.start_at);
      data.append("finish_at", app.auction.finish_at);
      data.append("comment", !!app.auction.comment ? app.auction.comment : "");
      data.append("volume", !!app.auction.volume ? app.auction.volume : "");
      data.append(
        "start_price",
        !!app.auction.start_price ? app.auction.start_price : ""
      );
      data.append("step", !!app.auction.step ? app.auction.step : "");

      axios
        .post("/api/v1/auctions/" + app.auction.id, data)
        .then(function(res) {
          app.$router.replace("/personal/auctions/show/" + res.data.id);
          app.errors = {};
          loader.hide();
        })
        .catch(function(err) {
          app.errors = {};
          if (!!err.response.data.errors) app.errors = err.response.data.errors;
          loader.hide();
        });
    }
  }
};
</script>