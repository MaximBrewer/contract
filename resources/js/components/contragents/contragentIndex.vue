<template>
  <section>
    <div class="container-fluid">
      <div class="table-responsive" id="contragents">
        <div class="form-group">
          <input class="form-control" v-model="search" :placeholder="__('Search')"/>
        </div>
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
            <tr v-for="(contragent, index) in contragentList" :key="index">
              <td>{{ contragent.title }}</td>
              <td>{{ contragent.inn }}</td>
              <td>{{ contragent.rating }}</td>
              <td>
                <div class="btn-group" role="group">
                  <router-link
                    :to="{name: 'showContragent', params: {id: contragent.id}}"
                    class="btn btn-sm btn-primary"
                  >{{ __('Show') }}</router-link>
                  <button class="btn btn-sm btn-success" @click="$root.writeTo(contragent.id)">
                    <i aria-hidden="true" class="mdi mdi-pencil"></i>
                  </button>
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
      contragentList: [],
      search: ""
    };
  },
  watch: {
    search: function(newSearch, oldSearch) {
      let app = this;
      if (!newSearch) app.contragentList = app.$root.contragents;
      else {
        app.contragentList = [];
        for (let i in app.$root.contragents) {
          let c = app.$root.contragents[i];
          if (!!c.title) {
            var reg = new RegExp(newSearch, "gi");
            // console.log(newSearch, c);
            if (reg.test(c.title)) app.contragentList.push(c);
          }
        }
      }
    }
  },
  mounted() {
    var app = this;
    axios
      .get("/web/v1/contragents")
      .then(function(resp) {
        app.$root.contragents = resp.data;
        app.contragentList = app.$root.contragents;
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