<template>
  <section>
    <div class="container-fluid">
      <div class="table-responsive" id="contragents">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>{{ __('Title') }}</th>
              <th>{{ __('TIN') }}</th>
              <th>{{ __('Rating') }}</th>
              <th width="100">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(contragent, index) in $root.contragents" :key="index">
              <td>{{ contragent.title }}</td>
              <td>{{ contragent.inn }}</td>
              <td>{{ contragent.rating }}</td>
              <td>
                <div class="btn-group" role="group">
                  <router-link
                    :to="{name: 'showContragent', params: {id: contragent.id}}"
                    class="btn btn-sm btn-primary"
                  >{{ __('Show') }}</router-link>
                  <button class="btn btn-sm btn-success" @click="$root.writeTo(contragent.id)"><i aria-hidden="true" class="mdi mdi-pencil"></i></button>
                </div>
              </td>
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
    return {};
  },
  mounted() {
    var app = this;
    axios
      .get("/api/v1/contragents")
      .then(function(resp) {
        app.$root.contragents = resp.data;
      })
      .catch(function(err) {
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load contragents"),
          type: "error",
          timer: 5000
        });
      });
  }
};
</script>