<template>
  <section id="paymentForm" class="container">
    <div class="py-3">
      <h1>
        Баланс
        <span
          class="badge"
          v-bind:class="{ 'badge-danger': user.contragents[0].balance < 0, 'badge-success': user.contragents[0].balance > 0, 'badge-secondary': user.contragents[0].balance ==0 }"
        >{{ user.contragents[0].balance | formatMoney }}</span>
      </h1>
    </div>
    <form method="POST">
      <div class="form-group">
        <input
          style="max-width:200px"
          step=".01"
          name="balance"
          type="number"
          data-type="number"
          v-model="form.balance"
          class="form-control text-right"
          v-bind:class="{ 'is-invalid': errors.balance }"
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
          :disabled="!(form.balance * 1) || form.balance > 15000"
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
          :disabled="!(form.balance * 1)"
        />
        <label class="form-check-label" for="invoiceForm">Счет</label>
      </div>
      <button
        @click="sendForm"
        class="btn btn-primary"
      >{{ form.method == 'invoice' ? 'Сформировать счет' : 'Оплатить'}}</button>
    </form>
    <div class="table-responsive my-3" id="settlements" v-if="settlements.length">
      <div class="h2 text-center">Взаиморасчеты</div>
      <table class="table table-bordered">
        <thead>
          <tr class="text-center">
            <th class="align-middle">#</th>
            <th class="align-middle">{{ __('Date & Time') }}</th>
            <th class="align-middle">{{ __('Platform rewards') }}</th>
            <th class="align-middle">{{ __('The amount sent') }}</th>
            <th class="align-middle">{{ __('Auction') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(settlement, index) in settlements" :key="index">
            <td class="text-center">{{ settlement.id }}</td>
            <td class="text-center">{{ settlement.datetime }}</td>
            <td class="text-right"><span v-if="settlement.type == 'credit'">{{ settlement.balance | formatMoney }}</span></td>
            <td class="text-right"><span v-if="settlement.type == 'debit'">{{ settlement.balance | formatMoney }}</span></td>
            <td class="text-right">{{ settlement.auction_id }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
export default {
  data: function() {
    return { errors: {}, form: {}, settlements: [] };
  },
  created: function() {
    var app = this;
    axios
      .get("/api/v1/settlements")
      .then(function(res) {
        app.settlements = res.data.data;
      })
      .catch(function(err) {
        app.errors = {};
        loader.hide();
      });
  },
  methods: {
    sendForm() {
      event.preventDefault();
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/api/v1/settlements", {
          balance: app.form.balance,
          method: app.form.method
        })
        .then(function(res) {
          app.errors = {};
          var form = document.createElement("form");
          form.setAttribute("action", res.data.url);
          form.setAttribute("method", "POST");
          form.setAttribute("target", "_blank");
          for (var f in res.data.hiddens) {
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", f);
            input.setAttribute("value", res.data.hiddens[f]);
            form.appendChild(input);
          }
          var input = document.createElement("input");
          input.setAttribute("type", "hidden");
          input.setAttribute("name", "_token");
          input.setAttribute(
            "value",
            $('meta[name="csrf-token"]').attr("content")
          );
          form.appendChild(input);
          window.document.body.appendChild(form);
          setTimeout(function() {
            form.submit();
          }, 200);
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