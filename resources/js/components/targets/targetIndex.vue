<template>
  <section>
    <div class="container-fluid">
      <div class="table-responsive table-hover" id="tergets">
        <div class="h2 text-center">{{ __('All Targets (Tenders)') }}</div>
        <br />
        <table class="table table-bordered" v-if="targets.length">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('Contragent') }}</th>
              <th>{{ __('Product') }}</th>
              <th>{{ __('Volume') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(target, index) in targets" :key="index">
              <td>{{ index + 1 }}</td>
              <td v-if="target.contragent && target.contragent.title">{{ target.contragent.title }}</td>
              <td v-else></td>
              <td>{{ target.product.title }}</td>
              <td>{{ target.volume }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  data: function() {
    return {
      targets: []
    };
  },
  mounted() {
    var app = this;
    let contragent_id = app.user.contragents[0].id;
    let action = "bid";
    axios
      .get(
        "/api/v1/targets/all/?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.targets = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load targets"));
      });
  },
  methods: {}
};
</script>