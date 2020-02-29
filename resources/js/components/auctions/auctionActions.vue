<template>
  <div class="row">
    <div class="col-md-4" v-if="!auction.finished">
      <div class="card text-center">
        <div class="btn-group" role="group" aria-label="Basic example">
          <a
            v-tooltip="__('Confirm auction')"
            href="javascript:void(0)"
            v-if="auction.contragent.id == company.id && !auction.confirmed"
            class="btn btn-primary"
            @click="confirm(auction.id)"
          >
            <i class="mdi mdi-check-circle" aria-hidden="true"></i>
          </a>
          <a
            v-tooltip="__('Add bidder')"
            href="javascript:void(0)"
            class="btn btn-success"
            @click="showPopupAddBidder(auction.id)"
          >
            <i class="mdi mdi-account-plus" aria-hidden="true"></i>
          </a>
          <router-link
            v-tooltip="__('Edit auction')"
            :to="{name: 'editAuction', 'params': {'id': auction.id}}"
            v-if="auction.contragent.id == company.id"
            class="btn btn-dark"
          >
            <i class="mdi mdi-pencil" aria-hidden="true"></i>
          </router-link>
          <a
            v-tooltip="__('Delete auction')"
            href="javascript:void(0)"
            class="btn btn-danger"
            v-if="auction.contragent.id == company.id && !auction.confirmed"
            @click="delAuction(auction.id)"
          >
            <i class="mdi mdi-delete" aria-hidden="true"></i>
          </a>
          <modal
            name="add_bidder"
            height="auto"
            :adaptive="true"
            width="90%"
            :maxWidth="maxModalWidth"
          >
            <div class="modal-header">
              <h5 class="modal-title">
                <strong>{{ __('Auction bidder add') }}</strong>
              </h5>
              <button
                type="button"
                class="close"
                @click="$modal.hide('add_bidder')"
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
                  :options="bidders"
                  label="title"
                  :multiple="true"
                  v-model="add_bidders"
                ><div slot="no-options">{{ __('No Options Here!') }}</div></v-select>
                <br />
                <br />
                <br />
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-success"
                data-dismiss="modal"
                @click="$modal.hide('target');addBidder()"
              >{{ __('Add bidder') }}</button>
            </div>
          </modal>
        </div>
      </div>
    </div>
    <div v-bind:class="{'col-md-8': !auction.finished, 'col-md-12': auction.finished}">
      <div class="card">
        <div class="card-header">{{ __('Auction comment') }}</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-if="auction.comment">{{ auction.comment }}</li>
        </ul>
      </div>
      <br />
    </div>
  </div>
</template>
<script>
export default {
  props: {
    auction: {
      type: Object,
      default: {}
    }
  },
  data: function() {
    return {
      bidders: [],
      add_bidders: [],
      maxModalWidth: 600
    };
  },
  mounted() {
    var app = this;
    axios.get("/api/v1/contragents").then(function(res) {
      app.bidders = res.data;
    });
  },
  methods: {
    addBidder() {
      var app = this;
      if (app.auction && app.add_bidders.length)
        axios
          .post("/api/v1/auctions/add_bidder", {
            auction: app.auction.id,
            bidders: app.add_bidders
          })
          .then(function(res) {
            // app.auction = res.data;
            app.$modal.hide("add_bidder");
          });
    },
    delAuction() {
      var app = this;
      if (app.auction)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios
            .get("/api/v1/auction/delete/" + app.auction.id)
            .then(function(res) {
              app.$router.replace("/personal/auctions");
            });
        });
    },
    confirm() {
      var app = this;
      if (app.auction)
        app.$confirm(app.__("Are you sure?")).then(() => {
          axios
            .get("/api/v1/auction/confirm/" + app.auction.id)
            .then(function(res) {
              // app.auction = res.data;
            });
        });
    },
    fetchBidders(search, loading) {
      var app = this;
      loading(true);
      axios.get("/api/v1/contragents?search=" + search).then(function(res) {
        app.bidders = res.data;
        loading(false);
      });
    },
    showPopupAddBidder() {
      this.$modal.show("add_bidder");
    }
  }
};
</script>