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
    axios
      .get("/api/v1/targets/all/")
      .then(function(res) {
        app.targets = res.data;
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Failed to load targets!"),
          type: "error",
          timer: 5000
        });
      });
  },
  methods: {}
};
</script>