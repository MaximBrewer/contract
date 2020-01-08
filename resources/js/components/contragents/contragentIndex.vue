<template>
  <section>
    <router-link :to="{name: 'createContragent'}" class="btn btn-primary">{{ __('Create new contragent') }}</router-link><br><br>
    <div class="table-responsive">
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
                :to="{name: 'editContragent', params: {id: contragent.id}}"
                class="btn btn-sm btn-primary"
              >{{ __('Edit') }}</router-link>
              <a
                href="#"
                class="btn btn-sm btn-danger"
                v-on:click="deleteEntry(contragent.id, index)"
              >{{ __('Delete') }}</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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
      .get("/api/v1/contragents")
      .then(function(resp) {
        app.contragents = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert( __('Failed to load contragents') );
      });
  },
  methods: {
    deleteEntry(id, index) {
      if (confirm( __('Are you sure you want to delete the contragent?') )) {
        var app = this;
        axios
          .delete("/api/v1/contragents/" + id)
          .then(function(resp) {
            app.contragents.splice(index, 1);
          })
          .catch(function(resp) {
            alert( __('Не удалось удалить контрагента') );
          });
      }
    }
  }
};
</script>