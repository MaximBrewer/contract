<template>
  <div>
    <div class="table-responsive" id="auctions">
      <div class="h2 text-center">{{ title }}</div>
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __("Auction") }}</th>
            <th>{{ __("Intervals") }}</th>
            <th>{{ __("Description") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(auction, index) in auctions" :key="index">
            <td>
              <div>
                <strong>{{ auction.id }}</strong>
              </div>
              <div>
                <a
                  v-tooltip="__('Copy the auction')"
                  v-if="company.id == auction.contragent.id"
                  href="javascript:void(0)"
                  class="btn btn-primary"
                  style="margin-bottom: 0.3rem"
                  @click="copyAuction(auction.id)"
                >
                  <i class="mdi mdi-content-copy" aria-hidden="true"></i>
                </a>
              </div>
              <div>
                <a
                  v-tooltip="__('Take part in the auction')"
                  v-if="!auction.bidder && company.id != auction.contragent.id"
                  href="javascript:void(0)"
                  class="btn btn-success"
                  style="margin-bottom: 0.3rem"
                  @click="bidAuction(auction)"
                >
                  <i class="mdi mdi-account-plus" aria-hidden="true"></i>
                </a>
              </div>
              <div>
                <a
                  v-tooltip="__('Unsubscribe from the auction')"
                  v-if="auction.bidder"
                  href="javascript:void(0)"
                  class="btn btn-danger"
                  style="margin-bottom: 0.3rem"
                  @click="unbidAuction(auction)"
                >
                  <i class="mdi mdi-account-remove" aria-hidden="true"></i>
                </a>
              </div>
              <div>
                <a
                  v-tooltip="__('Go to auction page')"
                  :href="'/personal/auctions/show/' + auction.id"
                  class="btn btn-secondary"
                  style="margin-bottom: 0.3rem"
                  target="_blank"
                >
                  <i class="mdi mdi-eye" aria-hidden="true"></i>
                </a>
              </div>
              <div>
                <router-link
                  v-if="company.id == auction.contragent.id"
                  v-tooltip="__('Edit auction')"
                  :to="{ name: 'editAuction', params: { id: auction.id } }"
                  class="btn btn-primary"
                  style="margin-bottom: 0.3rem"
                >
                  <i class="mdi mdi-pencil" aria-hidden="true"></i>
                </router-link>
              </div>
            </td>
            <td>
              <div v-if="auction.contragent" class="text-nowrap">
                <strong v-if="false">{{ __("Contragent") }}:</strong>
                <div class="font-weight-bold">
                  <a
                    target="_blank"
                    style="font-size: 130%"
                    :href="
                      '/personal/contragents/show/' + auction.contragent.id
                    "
                    >{{ auction.contragent.title }}</a
                  >
                </div>
              </div>
              <div v-if="auction.contragent" class="text-nowrap">
                <strong>{{ __("Rating") }}:</strong>
                {{ auction.contragent.rating }}
              </div>
              <div class="text-nowrap">
                <div class="font-weight-bold">
                  <a
                    href="javascript:;"
                    style="font-size: 130%"
                    @click="$modal.show(auction.mode + '_modal')"
                    >{{ __(auction.mode) }}</a
                  >
                </div>
              </div>
              <div v-if="auction.product" class="text-nowrap">
                <strong>{{ __("Product") }}:</strong>
                <span style="font-weight:bold;font-size:125%">{{ auction.product.title }}</span>
              </div>
              <div v-if="auction.multiplicity" class="text-nowrap">
                <strong>{{ __("Multiplicity") }}:</strong>
                <span>{{ auction.multiplicity.title }}</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("Auction start") }}:</strong>
                <span>{{ auction.start_at | formatDateTime }}</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("Auction finish") }}:</strong>
                <span>{{ auction.finish_at | formatDateTime }}</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("New can bet?") }}:</strong>
                <span>{{ __(auction.can_bet) }}</span>
              </div>
              <div
                class="text-nowrap"
                v-if="auction.start_price && action == 'bid'"
              >
                <strong>{{ __("Auction minimal bet") }}:</strong>
                <span>{{ auction.min_bet }}₽</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("Auction step") }}:</strong>
                <span>{{ auction.step }}₽</span>
              </div>
              <div class="text-nowrap">
                <strong>{{ __("Autosale") }}:</strong>
                <span>{{ __(auction.autosale) }}</span>
              </div>
              <!-- <div
                v-if="auction.store && auction.store.federal_district"
                class="text-nowrap"
              >
                <strong>{{ __("Auction store federal district") }}:</strong>
                <span>{{ auction.store.federal_district.title }}</span>
              </div>
              <div
                v-if="auction.store && auction.store.region"
                class="text-nowrap"
              >
                <strong>{{ __("Auction store region") }}:</strong>
                <span>{{ auction.store.region.title }}</span>
              </div> -->
              <div v-if="auction.store">
                <strong>{{ __("Auction store address") }}:</strong>
                <span>{{ auction.store.address }}</span>
              </div>
              <div v-if="auction.store && false" class="text-nowrap">
                <strong>{{ __("Auction store coords") }}:</strong>
                <br />
                <span>{{ auction.store.coords }}</span>
              </div>
              <div
                class="text-nowrap"
                v-if="
                  auction.range != undefined && auction.range != 10000 && store
                "
              >
                <strong>{{ __("Range") }}:</strong>
                <span>{{ auction.range * 1 }} {{ __("km") }}</span>
              </div>
              <div class="text-nowrap" v-if="action != 'archive'">
                <strong>Подтвержден:</strong>
                <span>{{ auction.confirmed ? __("Да") : __("Нет") }}</span>
              </div>
            </td>
            <td>
              <ul class="unstyled">
                <li v-for="(interval, idx) in auction.intervals" :key="idx">
                  <div class="text-nowrap">
                    <strong>{{ __("Start Price") }}:</strong>
                    <span>{{ interval.start_price }}</span>
                  </div>
                  <div class="text-nowrap">
                    <strong>{{ __("Volume") }}:</strong>
                    <span>{{ interval.volume }}</span>
                  </div>
                  <div class="text-nowrap">
                    <strong>{{ __("From") }}:</strong>
                    <span>{{ interval.from | formatDateTime }}</span>
                    <strong>{{ __("To") }}:</strong>
                    <span>{{ interval.to | formatDateTime }}</span>
                  </div>
                </li>
              </ul>
            </td>
            <td>
              <span>{{ auction.comment }}</span>
              <span v-if="auction.ncomment"><br/><br/>{{ auction.ncomment }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <modal
      name="price2day_modal"
      height="auto"
      :adaptive="true"
      width="90%"
      :maxWidth="maxModalWidth"
    >
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __("Торги на понижение") }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('price2day_modal')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{
          __(
            "В данном режиме торгов предприятие предлагает Максимальную цену на свою продукцию, по которой готово продать объем прямо сейчас. Внутри торгов у вас будет возможность предложить другую, желаемую Вами цену на данную продукцию. После переговоров с Вами цена для Вас может быть индивидуально скорректирована по решению компании. Не забудьте добавить себя в торги по данной продукции, нажав на значок зеленого человечка - он станет красным."
          )
        }}
      </div>
    </modal>
    <modal
      name="future_modal"
      height="auto"
      :adaptive="true"
      width="90%"
      :maxWidth="maxModalWidth"
    >
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __("Торги на повышение") }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('future_modal')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{
          __(
            "В данных торгах предприятие указывает минимальную цену. Предприятие-продавец не может влиять на цену, цену определяют предприятия-покупатели, тот кто дает максимальную цену тот и станет покупателем продукции."
          )
        }}
      </div>
    </modal>
    <modal
      name="callApp_modal"
      height="auto"
      :adaptive="true"
      width="90%"
      :maxWidth="maxModalWidth"
    >
      <div class="modal-header">
        <h5 class="modal-title">
          <strong>{{ __("Сбор заявок") }}</strong>
        </h5>
        <button
          type="button"
          class="close"
          @click="$modal.hide('callApp_modal')"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{
          __(
            "В данных торгах указан объем производства предприятия в среднем в неделю и предоставлена возможность забронировать любой объем, возможность поставить любую цену. Данные торги представлены в информационных целях, для поиска поставщиков; бронирования продукции в условиях отсутствия ее на складе предприятий-продавцов."
          )
        }}
      </div>
    </modal>
  </div>
</template>
<script>
export default {
  props: ["action", "auctions", "store"],
  data: function () {
    return {
      title: "",
      maxModalWidth: 600,
    };
  },
  mounted() {
    switch (this.action) {
      case "my":
        this.title = this.__("My auctions");
        break;
      case "all":
        this.title = this.__("All auctions");
        break;
      case "bid":
        this.title = this.__("Bidder");
        break;
    }
  },
  methods: {
    bidAuction(auction) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/auctions/bid/" + auction.id)
        .then(function (res) {
          let bidder = res.data.data.bidder;
          auction.bidder = res.data.data.bidder;
          loader.hide();
        })
        .catch(function (err) {
          // console.log(err);
          app.$fire({
            title: app.__("Failed to bid auction"),
            type: "error",
            timer: 2000,
          });
          loader.hide();
        });
    },
    unbidAuction(auction) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .get("/web/v1/auctions/unbid/" + auction.id)
        .then(function (res) {
          let bidder = res.data.data.bidder;
          auction.bidder = res.data.data.bidder;
          loader.hide();
        })
        .catch(function (err) {
          app.$fire({
            title: app.__("Failed to unbid auction"),
            type: "error",
            timer: 2000,
          });
          loader.hide();
        });
    },
    copyAuction(id) {
      var app = this;
      let loader = Vue.$loading.show();
      axios
        .post("/web/v1/auction/copy", { id: id })
        .then(function (res) {
          loader.hide();
          app.$router.push("/personal/auctions/edit/" + res.data.id);
        })
        .catch(function (err) {
          app.$fire({
            title: app.__("Error!"),
            text: err.response ? err.response.data.message : "",
            type: "error",
            timer: 2000,
          });
          loader.hide();
        });
    },
  },
};
</script>