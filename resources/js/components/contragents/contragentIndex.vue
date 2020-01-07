<template>
  <section>
    <router-link :to="{name: 'createContragent'}" class="btn btn-primary">Create new contragent</router-link><br><br>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Inn</th>
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
              >Edit</router-link>
              <a
                href="#"
                class="btn btn-sm btn-danger"
                v-on:click="deleteEntry(contragent.id, index)"
              >Delete</a>
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
        alert("Не удалось загрузить компании");
      });
  },
  methods: {
    deleteEntry(id, index) {
      if (confirm("Вы действительно хотите удалить компанию?")) {
        var app = this;
        axios
          .delete("/api/v1/contragents/" + id)
          .then(function(resp) {
            app.contragents.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Не удалось удалить компанию");
          });
      }
    }
  }
};
</script>