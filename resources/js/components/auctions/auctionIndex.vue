<template>
  <section>
    <router-link :to="{name: 'createAuction'}" class="btn btn-primary">Create new auction</router-link><br><br>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Contragent</th>
            <th>Product</th>
            <th>Multiplicity</th>
            <th>Federal district</th>
            <th>Region</th>
            <th>Address</th>
            <th>Coords</th>
            <th>Start</th>
            <th>Finish</th>
            <th width="100">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="auction, index in auctions">
            <td>{{ auction.contragent.title }}</td>
            <td>{{ auction.product.title }}</td>
            <td>{{ auction.multiplicity.title }}</td>
            <td>{{ auction.store.federal_district.title }}</td>
            <td>{{ auction.store.region.title }}</td>
            <td>{{ auction.store.address }}</td>
            <td>{{ auction.store.coords }}</td>
            <td>{{ auction.start_at }}</td>
            <td>{{ auction.finish_at }}</td>
            <td>
              <div class="btn-group" role="group">
              <router-link
                :to="{name: 'editAuction', params: {id: auction.id}}"
                class="btn btn-sm btn-primary"
              >Edit</router-link>
              <a
                href="#"
                class="btn btn-sm btn-danger"
                v-on:click="deleteEntry(auction.id, index)"
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
      auctions: []
    };
  },
  mounted() {
    var app = this;
    axios
      .get("/api/v1/auctions")
      .then(function(resp) {
        app.auctions = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Не удалось загрузить аукционы");
      });
  },
  methods: {
    deleteEntry(id, index) {
      if (confirm("Вы действительно хотите удалить аукцион?")) {
        var app = this;
        axios
          .delete("/api/v1/auctions/" + id)
          .then(function(resp) {
            app.auctions.splice(index, 1);
          })
          .catch(function(resp) {
            alert("Не удалось удалить аукцион");
          });
      }
    }
  }
};
</script>