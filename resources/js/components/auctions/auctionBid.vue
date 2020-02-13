<template>
  <section>
    <div class="container-fluid">
      <div class="row justify-content-end">
        <div class="col-lg-6">
          <div class="table-responsive" id="tergets" v-if="targets.length">
            <div class="h2 text-center">{{ __('My Targets') }}</div>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Product') }}</th>
                  <th>{{ __('Volume') }}</th>
                  <th>{{ __('Remain') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(target, index) in targets" :key="index">
                  <td width="1">{{ index + 1 }}</td>
                  <td>{{ target.product.title }}</td>
                  <td>{{ target.volume }}</td>
                  <td>{{ target.volume - target.restof }}</td>
                  <td width="1">
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
      <div class="row">
        <div class="col-lg-12">
          <auctions-list :action="'bid'"></auctions-list>
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
import auctionsList from "./auctionsList";
export default {
  components: {
    auctionsList: auctionsList
  },
  data: function() {
    return {
      targets: [],
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
  },
  methods: {
    checkTargets() {
      let app = this;
      axios
        .get("/api/v1/targets/")
        .then(function(res) {
          app.targets = res.data;
        })
        .catch(function(err) {
          console.log(err);
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