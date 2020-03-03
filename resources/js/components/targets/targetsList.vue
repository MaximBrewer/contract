<template>
  <section>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Federal district') }}</label>
            <v-select
              label="title"
              :searchable="true"
              @input="filterRegionsTargets"
              :options="$root.federalDistricts"
              v-model="filter.federal_district"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Region') }}</label>
            <v-select
              label="title"
              :searchable="true"
              @input="filterTargets"
              :options="$root.regions"
              v-model="filter.region"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Product') }}</label>
            <v-select
              label="title"
              :searchable="true"
              @input="filterTargets"
              :options="$root.products"
              v-model="filter.product"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Contragent') }}</label>
            <v-select
              label="title"
              :searchable="true"
              @input="filterTargets"
              :options="$root.contragents"
              v-model="filter.contragent"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Multiplicity') }}</label>
            <v-select
              label="title"
              :searchable="true"
              @input="filterTargets"
              :options="$root.multiplicities"
              v-model="filter.multiplicity"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="form-group">
            <label class="control-label">{{ __('Sort by distance from store') }}</label>
            <v-select
              label="address"
              :searchable="false"
              @input="sorByDistance"
              :options="$root.stores"
              v-model="store"
            ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="table-responsive" id="tergets" v-if="targets.length">
            <div class="h2 text-center">{{ title }}</div>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Target') }}</th>
                  <th>{{ __('Store') }}</th>
                  <th>{{ __('Volume / Remain') }}</th>
                  <th>{{ __('Description') }}</th>
                  <th v-if="action == 'my'"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(target, index) in targetsList" :key="index">
                  <td width="1">{{ index + 1 }}</td>
                  <td>
                    <div>
                  <router-link :to="'/personal/contragents/show/' + target.contragent.id">{{ target.contragent.title }}</router-link></div>
                    <div class="text-nowrap">
                      <strong>{{ __('Rating') }}:</strong>
                      {{ target.contragent.rating }}
                    </div>
                    <div>{{ target.product.title }}</div>
                    <div>{{ target.multiplicity.title }}</div>
                  </td>
                  <td>
                    <div v-if="target.store">
                      <div>{{ target.store.federal_district.title }}</div>
                      <div>{{ target.store.region.title }}</div>
                      <div>{{ target.store.address }}</div>
                      <div>{{ target.store.coords }}</div>
                      <div
                        v-if="target.range != undefined && target.range != 10000 && target.store"
                      >{{ __('Range') }}: {{ target.range * 1 }} {{ __('km') }}</div>
                    </div>
                  </td>
                  <td>{{ target.volume }} / {{ target.volume - target.restof }}</td>
                  <td>{{ target.description }}</td>
                  <td width="1" v-if="action == 'my'">
                    <div class="btn-group btn-group-sm" role="group">
                      <a
                        href="javascript:void(0)"
                        class="btn btn-secondary"
                        @click="showPopup('targets', target.id, 'target', index)"
                      >
                        <i class="mdi mdi-eye" aria-hidden="true"></i>
                      </a>
                      <router-link
                        class="btn btn-primary"
                        :to="{name: 'editTarget', 'params': {'id': target.id}}"
                      >
                        <i class="mdi mdi-pencil" aria-hidden="true"></i>
                      </router-link>
                      <a
                        href="javascript:void(0)"
                        class="btn btn-danger"
                        @click="deleteTarget(target.id)"
                      >
                        <i class="mdi mdi-delete" aria-hidden="true"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <modal name="target" height="auto" :adaptive="true" width="90%" :maxWidth="maxModalWidth">
      <div class="modal-header" v-if="modal_target">
        <h5 class="modal-title" v-if="modal_target.product">
          <strong>{{ __('Target') }}:</strong>
          {{ modal_target.product.title }}
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('target')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" v-if="modal_target">
        <p v-if="modal_target.store && modal_target.store.federal_district">
          <strong>{{ __('Target federal district title') }}:</strong>
          {{ modal_target.store.federal_district.title }}
        </p>
        <p v-if="modal_target.store && modal_target.store.region">
          <strong>{{ __('Target region title') }}:</strong>
          {{ modal_target.store.region.title }}
        </p>
        <p v-if="modal_target.store">
          <strong>{{ __('Target store address') }}:</strong>
          {{ modal_target.store.address }}
        </p>
        <p v-if="modal_target.store">
          <strong>{{ __('Target store coords') }}:</strong>
          {{ modal_target.store.coords }}
        </p>
        <p>
          <strong>{{ __('Target volume') }}:</strong>
          {{ modal_target.volume }}
        </p>
      </div>
      <div class="modal-footer" v-if="modal_target">
        <router-link
          class="btn btn-primary"
          :to="{name: 'editTarget', 'params': {id: modal_target.id}}"
        >{{ __('Edit') }}</router-link>
        <button
          type="button"
          class="btn btn-danger"
          data-dismiss="modal"
          @click="$modal.hide('target');deleteTarget(modal_target.id, index)"
        >{{ __('Delete') }}</button>
      </div>
    </modal>
  </section>
</template>
<script>
export default {
  props: ["action"],
  data: function() {
    return {
      targets: [],
      title: "",
      targetsList: [],
      filter: {},
      store: null,
      modal_target: null,
      modal_auction: null,
      maxModalWidth: 600
    };
  },
  mounted() {
    this.$root.$on("gotAuction", function(auction) {
      this.checkTargets();
    });
    this.checkTargets();
    switch (this.action) {
      case "my":
        this.title = this.__("My Targets");
        break;
      case "all":
        this.title = this.__("All targets (tenders)");
        break;
    }
  },
  methods: {
    filterRegionsTargets() {
      this.$root.getRegions(this.filter.federal_district.id);
      this.filterTargets();
    },
    filterTargets() {
      let app = this;
      app.targetsList = [];
      let cnt = 0;
      let f = app.filter;
      for (let v in app.targets) {
        ++cnt;
        let a = app.targets[v];
        if (
          (!f.federal_district ||
            f.federal_district.id == a.store.federal_district.id) &&
          (!f.region || f.region.id == a.store.region.id) &&
          (!f.product || f.product.id == a.product.id) &&
          (!f.multiplicity || f.multiplicity.id == a.multiplicity.id) &&
          (!f.contragent || f.contragent.id == a.contragent.id)
        )
          app.targetsList.push(a);
      }
      if (app.targets.length == cnt) app.sorByDistance();
    },
    sorByDistance() {
      let app = this;
      let store = app.store;
      if (!store || !store.coords) return false;
      let coords = store.coords.split(",");
      if (coords.length < 2) return true;
      let cnt = 0;
      for (let i in app.targetsList) {
        ++cnt;
        let as = app.targetsList[i].store.coords.split(",");
        app.targetsList[i].range = 10000;
        if (as.length < 2) continue;
        app.targetsList[i].range =
          Math.round(
            app.getDistanceFromLatLonInKm(
              coords[0].trim(),
              coords[1].trim(),
              as[0].trim(),
              as[1].trim()
            ) * 100
          ) / 100;
      }
      if (cnt == app.targetsList.length) {
        app.targetsList.sort(function(a, b) {
          return a.range - b.range;
        });
      }
    },
    getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
      var R = 6371; // Radius of the earth in km
      var dLat = this.deg2rad(lat2 - lat1); // deg2rad below
      var dLon = this.deg2rad(lon2 - lon1);
      var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(this.deg2rad(lat1)) *
          Math.cos(this.deg2rad(lat2)) *
          Math.sin(dLon / 2) *
          Math.sin(dLon / 2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      var d = R * c; // Distance in km
      return d;
    },
    deg2rad(deg) {
      return deg * (Math.PI / 180);
    },
    checkTargets() {
      let app = this;
      let loader = Vue.$loading.show();
      let url = "/api/v1/targets";
      if (app.action == "all") url = "/api/v1/targets/all";
      axios
        .get(url)
        .then(function(res) {
          app.targets = res.data;
          app.targetsList = res.data;
          loader.hide();
        })
        .catch(function(err) {
          loader.hide();
        });
    },
    showPopup(controller, id, template, index) {
      var app = this;
      axios.get("/api/v1/" + controller + "/" + id).then(function(resp) {
        app.modal_target = resp.data;
        app.modal_target.index = index;
        app.$modal.show("target");
      });
    },
    deleteTarget(id, index) {
      var app = this;
      this.$confirm(this.__("Are you sure?")).then(() => {
        axios.delete("/api/v1/targets/" + id).then(function(resp) {
          if (resp.data) app.targets.splice(index, 1);
          else
            app.$fire({
              title: app.__("Failed to delete target"),
              type: "error",
              timer: 3000
            });
        });
      });
    }
  }
};
</script>