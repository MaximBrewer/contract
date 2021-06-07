<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <a
            href="javascript:void(0)"
            @click="createDeferred"
            class="btn btn-primary btn-lg"
            >{{
              __(
                "Добавить поставщика (создать предложение стать его дистрибьютером)"
              )
            }}</a
          >
          <a
            href="javascript:void(0)"
            @click="createDistributor"
            class="btn btn-primary btn-lg"
            >{{
              __(
                "Одобрить дистрибьютера (он будет отвечать за совместные заказы)"
              )
            }}</a
          >
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __("Creditor") }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.contragents"
            v-model="filter.creditor"
          >
            <div slot="no-options">{{ __("No Options Here!") }}</div>
          </v-select>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __("Supplier") }}</label>
          <v-select
            label="title"
            :searchable="true"
            @input="filterList"
            :options="$root.contragents"
            v-model="filter.supplier"
          >
            <div slot="no-options">{{ __("No Options Here!") }}</div>
          </v-select>
        </div>
      </div>
    </div>
    <div class="table-responsive" id="deferTable">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __("Посредник") }}</th>
            <th>{{ __("Supplier") }}</th>
            <th>{{ __("Статус") }}</th>
            <th>{{ __("Description") }}</th>
            <th>{{ __("Сферы") }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(defer, index) in defersList" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ defer.creditor.title }}</td>
            <td>{{ defer.supplier.title }}</td>
            <td>{{ defer.status }}</td>
            <td>{{ defer.description }}</td>
            <td>
              <div v-if="defer.orbits">{{ defer.orbits.join(", ") }}</div>
            </td>
            <td class="text-center">
              <a
                v-tooltip="__('Edit defer')"
                href="javascript:void(0)"
                class="btn btn-primary btn-sm"
                v-if="defer.creditor.id == company.id"
                @click="editDefer(defer)"
              >
                <i class="mdi mdi-pencil" aria-hidden="true"></i>
              </a>
              <a
                v-tooltip="__('Delete defer')"
                href="javascript:void(0)"
                class="btn btn-danger btn-sm"
                @click="deleteDefer(defer)"
              >
                <i class="mdi mdi-delete" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal
      name="modal_defer"
      height="auto"
      :adaptive="true"
      width="90%"
      :maxWidth="maxModalWidth"
    >
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ form.title }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('modal_defer')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" v-if="form.type == 'creditor'">
          <v-select
            max-height="50px"
            :options="$root.contragents"
            label="title"
            v-model="form.supplier"
          >
            <div slot="no-options">{{ __("No Options Here!") }}</div>
          </v-select>
        </div>
        <div class="form-group" v-if="form.type == 'supplier'">
          <v-select
            max-height="50px"
            :options="$root.contragents"
            label="title"
            v-model="form.creditor"
          >
            <div slot="no-options">{{ __("No Options Here!") }}</div>
          </v-select>
        </div>
        <div class="form-group" v-if="form.type == 'creditor'">
          <v-select
            max-height="100px"
            :options="orbits"
            label="title"
            v-model="form.orbits"
            :multiple="true"
          >
            <div slot="no-options">{{ __("No Options Here!") }}</div>
          </v-select>
        </div>
        <div class="form-group" v-if="form.description !== false">
          <textarea v-model="form.description" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          @click="
            $modal.hide('modal_defer');
            auDefer();
          "
        >
          {{ form.id ? __("Update") : __("Add") }}
        </button>
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
      .then(function (res) {
        app.defers = res.data.data;
        app.filterList();
        loader.hide();
      })
      .catch(function (res) {
        loader.hide();
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load defers"),
          type: "error",
          timer: 5000,
        });
      });
  },
  data: function () {
    return {
      filter: {
        creditor: null,
        supplier: null,
      },
      form: {
        id: null,
        type: null,
        description: null,
        creditor: null,
        supplier: null,
        title: null,
        orbits: [],
      },
      maxModalWidth: 600,
      defers: [],
      defersList: [],
      orbits: [
        { value: "purchases", title: "совместные закупки" },
        { value: "delivery", title: "совместная доставка" },
        { value: "granting", title: "предоставление отсрочки" },
        { value: "warehouse", title: "предоставление склада" },
        { value: "otherwise", title: "иное" },
      ],
    };
  },
  methods: {
    auDefer() {
      if (this.form.id) this.updateDefer();
      else this.addDefer();
    },
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
      let app = this;
      app.form.id = null;
      app.form.type = "creditor";
      app.form.creditor = app.user.contragents[0].id;
      app.form.supplier = null;
      app.form.description = null;
      app.form.orbits = [];
      app.form.title =
        "Добавить поставщика (создать предложение стать его дистрибьютером)";
      this.$modal.show("modal_defer");
    },
    createDistributor() {
      let app = this;
      app.form.id = null;
      app.form.type = "supplier";
      app.form.creditor = null;
      app.form.supplier = app.user.contragents[0].id;
      app.form.description = false;
      app.form.orbits = [];
      app.form.title =
        "Одобрить дистрибьютера (он будет отвечать за совместные заказы)";
      this.$modal.show("modal_defer");
    },
    editDefer(defer, index) {
      let app = this;
      app.form.id = defer.id;
      app.form.description = defer.description;
      app.form.orbits = defer.orbits;
      app.form.supplier = defer.supplier;
      this.$modal.show("modal_defer");
    },
    addDefer() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/defers", {
          creditor_id: app.form.creditor.id,
          supplier_id: app.form.supplier.id,
          description: app.form.description,
          orbits: app.form.orbits,
        })
        .then(function (res) {
          app.defers = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function (res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to add defer"),
            type: "error",
            timer: 5000,
          });
        });
    },
    deleteDefer(defer) {
      var app = this;
      if (defer)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios.delete("/web/v1/defers/" + defer.id).then(function (res) {
            app.defers = res.data.data;
            app.filterList();
            loader.hide();
            s;
          });
        });
    },
    updateDefer() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .patch("/web/v1/defers/" + app.form.id, {
          supplier_id: app.form.supplier.id,
          description: app.form.description,
          orbits: app.form.orbits,
        })
        .then(function (res) {
          app.defers = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function (res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to update defer"),
            type: "error",
            timer: 5000,
          });
        });
    },
  },
};
</script>