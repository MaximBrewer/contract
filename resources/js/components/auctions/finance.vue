<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <a
            href="javascript:void(0)"
            @click="createDeferred"
            class="btn btn-primary btn-lg"
          >{{ __('Add a deferred service for the supplier') }}</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
          <label class="control-label">{{ __('Creditor') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.contragents"
            v-model="filter.creditor"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
          <label class="control-label">{{ __('Supplier') }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.contragents"
            v-model="filter.supplier"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="deferTable">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('Creditor') }}</th>
            <th>{{ __('Supplier') }}</th>
            <th>{{ __('Description') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(defer, index) in defersList" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ defer.creditor.title }}</td>
            <td>{{ defer.supplier.title }}</td>
            <td>{{ defer.description }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal name="add_defer" height="auto" :adaptive="true" width="90%" :maxWidth="maxModalWidth">
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __('Add a deferred service for the supplier') }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('add_defer')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <v-select
            max-height="50px"
            :options="$root.contragents"
            label="title"
            v-model="form.supplier"
          >
            <div slot="no-options">{{ __('No Options Here!') }}</div>
          </v-select>
        </div>
        <div class="form-group">
          <textarea v-model="form.description" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          @click="$modal.hide('add_defer');addDefer()"
        >{{ __('Add') }}</button>
      </div>
    </modal>
  </section>
</template>
<script>
export default {
  components: {},
  props: ["action"],
  mounted() {
    let app = this;
    let loader = Vue.$loading.show();
    axios
      .get("/web/v1/defers")
      .then(function(res) {
        app.defers = res.data.data;
        app.filterList();
        loader.hide();
      })
      .catch(function(res) {
        loader.hide();
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load auctions"),
          type: "error",
          timer: 5000
        });
      });
  },
  data: function() {
    return {
      filter: {
        creditor: null,
        supplier: null
      },
      form: {
        description: null,
        supplier: null
      },
      maxModalWidth: 600,
      defers: [],
      defersList: []
    };
  },
  methods: {
    filterList() {
      let app = this;
      app.defersList = [];
      let cnt = 0;
      let f = app.filter;
      for (let v in app.defers) {
        ++cnt;
        let a = app.defers[v];
        if (
          (!f.creditor || f.creditor.id == a.creditor.id) &&
          (!f.supplier || f.supplier.id == a.supplier.id)
        )
          app.defersList.push(a);
      }
    },
    createDeferred() {
      this.$modal.show("add_defer");
      let app = this;
    },
    addDefer() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/defers", {
          supplier_id: app.form.supplier.id,
          description: app.form.description
        })
        .then(function(res) {
          app.defers = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function(res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to add defer"),
            type: "error",
            timer: 5000
          });
        });
    }
  }
};
</script>