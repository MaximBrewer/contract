<template>
  <section>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <div class="table-responsive" id="tergets" v-if="targets.length">
            <div class="h2 text-center">{{ __('My Targets') }}</div>
            <table class="table table-hover table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>{{ __('Product') }}</th>
                  <th>{{ __('Volume') }}</th>
                  <th>{{ __('Remain') }}</th>
                  <th></th>
                  <!-- <th>{{ __('Remain') }}</th>
              <th>{{ __('Multiplicity') }}</th>
              <th>{{ __('Federal district') }}</th>
              <th>{{ __('Region') }}</th>
                  <th>{{ __('Address') }}</th>-->
                </tr>
              </thead>
              <tbody>
                <tr v-for="target, index in targets">
                  <td width="1">{{ index + 1 }}</td>
                  <td>{{ target.product.title }}</td>
                  <td>{{ target.volume }}</td>
                  <td>{{ target.remain }}</td>
                  <!-- <td>{{ target.renain }}</td>
              <td
                v-if="target.multiplicity && target.multiplicity.title"
              >{{ target.multiplicity.title }}</td>
              <td v-else></td>
              <td
                v-if="target.store && target.store.federal_district && target.store.federal_district.title"
              >{{ target.store.federal_district.title }}</td>
              <td v-else></td>
              <td
                v-if="target.store && target.store.region && target.store.region.title"
              >{{ target.store.region.title }}</td>
              <td v-else></td>
              <td v-if="target.store && target.store.address">{{ target.store.address }}</td>
                  <td v-else></td>-->
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
        <div class="col-lg-6">
          <div class="table-responsive" id="auctions" v-if="auctions.length">
            <div class="h2 text-center">{{ __('Auctions Bidder') }}</div>
            <table class="table table-hover table-bordered table-striped">
              <thead class="thead-dark">
                <tr>
                  <th></th>
                  <th>{{ __('Contragent') }}</th>
                  <th>{{ __('Product') }}</th>
                  <th>{{ __('Start Price') }}</th>
                  <th>{{ __('Volume') }}</th>
                  <th>{{ __('Multiplicity') }}</th>
                  <th>{{ __('Federal district') }}</th>
                  <th>{{ __('Region') }}</th>
                  <th>{{ __('Address') }}</th>
                  <th>{{ __('Start') }}</th>
                  <th>{{ __('Finish') }}</th>
                  <th>{{ __('Comment') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="auction, index in auctions">
                  <td>
                    <button
                      v-if="!auction.bidder && user.contragents[0].id != auction.contragent.id"
                      v-on:click="bidAuction(auction.id)"
                      class="btn btn-danger"
                    >{{ __('Bid') }}</button>
                    <button
                      v-if="auction.bidder"
                      v-on:click="unbidAuction(auction.id)"
                      class="btn btn-danger"
                    >{{ __('Unbid') }}</button>
                  </td>
                  <td>
                    <router-link
                      :to="{name: 'showAuction', 'params': {'id': auction.id}}"
                      class="dropdown-item"
                    >{{ auction.contragent.title }}</router-link>
                  </td>
                  <td>{{ auction.product.title }}</td>
                  <td>{{ auction.start_price }} ₽</td>
                  <td>{{ auction.volume }}</td>
                  <td>{{ auction.multiplicity.title }}</td>
                  <td>{{ auction.store.federal_district.title }}</td>
                  <td>{{ auction.store.region.title }}</td>
                  <td>{{ auction.store.address }}</td>
                  <td>{{ formatDate(auction.start_at) }}</td>
                  <td>{{ formatDate(auction.finish_at) }}</td>
                  <td>{{ auction.comment }}</td>
                  <td>
                    <a
                      href="javascript:void(0)"
                      class="btn btn-secondary"
                      @click="showPopup('auctions', auction.id, 'auction', index)"
                    >
                      <i class="mdi mdi-lens" aria-hidden="true"></i>
                    </a>
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
  data: function() {
    return {
      auctions: [],
      targets: [],
      modal_target: null,
      modal_auction: null,
      maxModalWidth: 600
    };
  },
  mounted() {
    var app = this;
    let contragent_id = app.user.contragents[0].id;
    let action = "bid";
    axios
      .get(
        "/api/v1/auctions/" +
          action +
          "?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.auctions = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load auctions"));
      });
    axios
      .get(
        "/api/v1/targets/?csrf_token=" +
          window.csrf_token +
          "&api_token=" +
          window.api_token
      )
      .then(function(resp) {
        app.targets = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert(app.__("Failed to load targets"));
      });
  },
  methods: {
    showPopup(controller, id, template, index) {
      var app = this;
      axios
        .get(
          "/api/v1/" +
            controller +
            "/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.modal_target = resp.data;
          app.modal_target.index = index;
          app.$modal.show("target");
        });
    },
    deleteTarget(id, index) {
      var app = this;
      this.$confirm(this.__("Are you sure?")).then(() => {
        axios
          .delete(
            "/api/v1/targets/" +
              id +
              "?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token
          )
          .then(function(resp) {
            if (resp.data) app.targets.splice(index, 1);
            else
              app.$fire({
                title: app.__("Failed to delete target"),
                type: "error",
                timer: 3000
              });
          });
      });
    },
    bidAuction(id) {
      var app = this;
      axios
        .get(
          "/api/v1/auctions/bid/bid/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.auctions = resp.data;
        })
        .catch(function(resp) {
          alert(app.__("Failed to bid auction"));
        });
    },
    unbidAuction(id) {
      var app = this;
      axios
        .get(
          "/api/v1/auctions/bid/unbid/" +
            id +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.auctions = resp.data;
        })
        .catch(function(resp) {
          alert(app.__("Failed to bid auction"));
        });
    },
    formatDate(indate) {
      let date = new Date(indate);
      return date.toLocaleString();
    }
    // deleteEntry(id, index) {
    //   var app = this;
    //   if (confirm(app.__("Are you sure you want to delete the auction?"))) {
    //     axios
    //       .delete("/api/v1/auctions/" + id)
    //       .then(function(resp) {
    //         app.auctions.splice(index, 1);
    //       })
    //       .catch(function(resp) {
    //         alert(app.__("Failed to delete auction"));
    //       });
    //   }
    // }
  }
};
</script>