<template>
  <section>
    <div class="container-fluid">
      <div class="table-responsive" id="contragents">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>{{ __('Title') }}</th>
              <th>{{ __('TIN') }}</th>
              <th width="100">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="contragent, index in contragents">
              <td>{{ contragent.title }}</td>
              <td>{{ contragent.inn }}</td>
              <td>
                <div class="btn-group" role="group">
                  <router-link
                    :to="{name: 'showContragent', params: {id: contragent.id}}"
                    class="btn btn-sm btn-primary"
                  >{{ __('Show') }}</router-link>
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
    return {
      contragents: []
    };
  },
  mounted() {
    var app = this;
    axios
      .get(
        "/api/v1/contragents?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.contragents = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load contragents"));
      });
  }
};
</script>