<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col-md-12 text-right">
        <div class="form-group">
          <a
            href="javascript:void(0)"
            @click="createDistributor"
            class="btn btn-primary btn-lg"
            >{{
              __(
                "Добавить посредника (создать предложение стать его покупателем)"
              )
            }}</a
          >
          <a
            href="javascript:void(0)"
            @click="createJoint"
            class="btn btn-primary btn-lg"
            >{{
              __(
                "Одобрить покупателя"
              )
            }}</a
          >
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-sm-6 col-12">
        <div class="form-group">
          <label class="control-label">{{ __("Посредник") }}</label>
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
          <label class="control-label">{{ __("Покупатель") }}</label>
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
    <div class="table-responsive" id="jointTable">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __("Посредник") }}</th>
            <th>{{ __("Покупатель") }}</th>
            <th>{{ __("Статус") }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(joint, index) in jointsList" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ joint.creditor.title }}</td>
            <td>{{ joint.supplier.title }}</td>
            <td>{{ joint.status }}</td>
            <td class="text-center">
              <a
                v-tooltip="__('Delete joint')"
                href="javascript:void(0)"
                class="btn btn-danger btn-sm"
                @click="deleteJoint(joint)"
              >
                <i class="mdi mdi-delete" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal
      name="modal_joint"
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
          @click="$modal.hide('modal_joint')"
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
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          @click="
            $modal.hide('modal_joint');
            auJoint();
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
      .get("/web/v1/joints")
      .then(function (res) {
        app.joints = res.data.data;
        app.filterList();
        loader.hide();
      })
      .catch(function (res) {
        loader.hide();
        app.$fire({
          title: app.__("Error!"),
          text: app.__("Failed to load joints"),
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
      joints: [],
      jointsList: [],
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
    auJoint() {
      if (this.form.id) this.updateJoint();
      else this.addJoint();
    },
    filterList() {
      let app = this;
      app.jointsList = [];
      let cnt = 0;
      let f = app.filter;
      for (let v in app.joints) {
        ++cnt;
        let a = app.joints[v];
        if (
          (!f.creditor || f.creditor.id == a.creditor.id) &&
          (!f.supplier || f.supplier.id == a.supplier.id)
        )
          app.jointsList.push(a);
      }
    },
    createJoint() {
      let app = this;
      app.form.id = null;
      app.form.type = "creditor";
      app.form.creditor = app.user.contragents[0].id;
      app.form.supplier = null;
      app.form.description = null;
      app.form.orbits = [];
      app.form.title =
        "Добавить посредника (создать предложение стать его покупателем)";
      this.$modal.show("modal_joint");
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
        "Одобрить покупателя";
      this.$modal.show("modal_joint");
    },
    editJoint(joint, index) {
      let app = this;
      app.form.id = joint.id;
      app.form.description = joint.description;
      app.form.orbits = joint.orbits;
      app.form.supplier = joint.supplier;
      this.$modal.show("modal_joint");
    },
    addJoint() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/joints", {
          creditor_id: app.form.creditor.id,
          supplier_id: app.form.supplier.id,
          description: app.form.description,
          orbits: app.form.orbits,
        })
        .then(function (res) {
          app.joints = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function (res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to add joint"),
            type: "error",
            timer: 5000,
          });
        });
    },
    deleteJoint(joint) {
      var app = this;
      if (joint)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios.delete("/web/v1/joints/" + joint.id).then(function (res) {
            app.joints = res.data.data;
            app.filterList();
            loader.hide();
            s;
          });
        });
    },
    updateJoint() {
      let app = this;
      let loader = Vue.$loading.show();
      axios
        .patch("/web/v1/joints/" + app.form.id, {
          supplier_id: app.form.supplier.id,
          description: app.form.description,
          orbits: app.form.orbits,
        })
        .then(function (res) {
          app.joints = res.data.data;
          app.filterList();
          loader.hide();
        })
        .catch(function (res) {
          loader.hide();
          app.$fire({
            title: app.__("Error!"),
            text: app.__("Failed to update joint"),
            type: "error",
            timer: 5000,
          });
        });
    },
  },
};
</script>