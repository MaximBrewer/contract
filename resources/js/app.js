/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
window.Vue = require("vue");
window.Vue.mixin(require("./trans"));
import VueRouter from "vue-router";
import moment from "moment";
import VueFlashMessage from "vue-flash-message";
import { VTooltip, VPopover, VClosePopover } from "v-tooltip";
Vue.directive("tooltip", VTooltip);
Vue.directive("close-popover", VClosePopover);
Vue.component("v-popover", VPopover);
import { Datetime } from "vue-datetime";
Vue.component("datetime", Datetime);
Vue.use(VueRouter);
Vue.use(VueFlashMessage);
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);
import VModal from "vue-js-modal";
Vue.use(VModal, { dynamic: true, injectModalsContainer: true });
import vSelect from "vue-select";
Vue.component("v-select", vSelect, {});
import StarRating from "vue-star-rating";
Vue.component("StarRating", StarRating, {});

import Vue from "vue";
// const VueInputMask = require('vue-inputmask').default
// Vue.use(VueInputMask)

import Chat from "vue-beautiful-chat";
Vue.use(Chat);

import Loading from "vue-loading-overlay";
Vue.use(
    Loading,
    {
        color: "red"
    },
    {
        // slots
    }
);

window.axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
};

import ContragentIndex from "./components/contragents/contragentIndex.vue";
import ContragentCreate from "./components/contragents/contragentCreate.vue";
import ContragentEdit from "./components/contragents/contragentEdit.vue";
import Company from "./components/contragents/company.vue";
import ContragentShow from "./components/contragents/contragentShow.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionArchive from "./components/auctions/auctionArchive.vue";
import AuctionMy from "./components/auctions/auctionMy.vue";
import MyResults from "./components/auctions/myResults.vue";
import AuctionBid from "./components/auctions/auctionBid.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";
import AuctionShow from "./components/auctions/auctionShow.vue";
import TargetCreate from "./components/targets/targetCreate.vue";
import TargetEdit from "./components/targets/targetEdit.vue";
import TargetIndex from "./components/targets/targetIndex.vue";
import ReviewsIndex from "./components/contragents/reviewsIndex.vue";
import AllAuctions from "./components/allAuctions.vue";
import DialoguesIndex from "./components/dialogues/dialoguesIndex.vue";
import DialoguesShow from "./components/dialogues/dialogueShow.vue";
import SendMessage from "./components/sendMessage.vue";
import SettlementsIndex from "./components/settlements/settlementsIndex.vue";
import Logistics from "./components/auctions/logistics.vue";
import LogisticCreate from "./components/auctions/logisticCreate.vue";
import LogisticEdit from "./components/auctions/logisticEdit.vue";
import Finance from "./components/auctions/finance.vue";

import DisputesIndex from "./components/disputes/disputesIndex.vue";

Vue.component("SendMessage", SendMessage, {});
Vue.component("AllAuctions", AllAuctions, {});

const app = new Vue({
    router: new VueRouter({
        mode: "history",
        beforeRouteUpdate(to, from, next) {},
        routes: [
            {
                path: "/personal",
                redirect: "/personal/auctions"
            },
            {
                path: "/personal/contragents",
                component: ContragentIndex,
                name: "contragentIndex"
            },
            {
                path: "/personal/contragents/reviews",
                component: ReviewsIndex,
                name: "reviews"
            },
            {
                path: "/personal/contragents/create",
                component: ContragentCreate,
                name: "createContragent"
            },
            {
                path: "/personal/contragents/show/:id",
                component: ContragentShow,
                name: "showContragent"
            },
            {
                path: "/personal/contragents/edit/:id",
                component: ContragentEdit,
                name: "editContragent"
            },
            {
                path: "/personal/auctions",
                component: AuctionIndex,
                name: "auctionIndex"
            },
            {
                path: "/personal/auctions/archive",
                component: AuctionArchive,
                name: "auctionArchive"
            },
            {
                path: "/personal/auctions/my",
                component: AuctionMy,
                name: "auctionMy"
            },
            {
                path: "/personal/auctions/bid",
                component: AuctionBid,
                name: "auctionBid"
            },
            {
                path: "/personal/dialogues",
                component: DialoguesIndex,
                name: "dialoguesIndex"
            },
            {
                path: "/personal/dialogue/show/:id",
                component: DialoguesShow,
                name: "showDialogue"
            },
            {
                path: "/personal/company",
                component: Company,
                name: "company"
            },
            {
                path: "/personal/auctions/results",
                component: MyResults,
                name: "myResults"
            },
            {
                path: "/personal/auctions/show/:id",
                component: AuctionShow,
                name: "showAuction"
            },
            {
                path: "/personal/auctions/create",
                component: AuctionCreate,
                name: "createAuction"
            },
            {
                path: "/personal/auctions/edit/:id",
                component: AuctionEdit,
                name: "editAuction"
            },
            {
                path: "/personal/targets",
                component: TargetIndex,
                name: "indexTarget"
            },
            {
                path: "/personal/targets/create",
                component: TargetCreate,
                name: "createTarget"
            },
            {
                path: "/personal/targets/edit/:id",
                component: TargetEdit,
                name: "editTarget"
            },
            {
                path: "/personal/settlements",
                component: SettlementsIndex,
                name: "settlementsIndex"
            },

            {
                path: "/personal/disputes",
                component: DisputesIndex,
                name: "disputesIndex"
            },
            {
                path: "/personal/disputes/:id",
                component: DisputesIndex,
                name: "disputesIndex"
            },

            {
                path: "/personal/logistics",
                component: Logistics,
                name: "logistics"
            },
            {
                path: "/personal/logistics/create",
                component: LogisticCreate,
                name: "createLogistic"
            },
            {
                path: "/personal/logistics/edit/:id",
                component: LogisticEdit,
                name: "editLogistic"
            },
            {
                path: "/personal/finance",
                component: Finance,
                name: "finance"
            }
        ]
    }),
    created() {
        let app = this;
        app.confirmedOptions = [
            { id: 2, title: app.__("Confirmed") },
            { id: 1, title: app.__("Not confirmed") }
        ];
        app.getFederalDistricts();
        app.getMultiplicities();
        app.getProducts();
        app.getRegions(app.fd);
        app.getTypes();
        app.getTags();
        app.getPurposes();
        app.getCapacities();
        app.getAllStores();
        axios
            .get("/auth")
            .then(res => {
                if (!!res.data.user) {
                    Vue.prototype.user = res.data.user;
                    Vue.prototype.company = res.data.user.contragents[0];
                    app.getMyStores();
                    app.listenForBroadcast();
                }
                app.$mount("#app");
            })
            .catch(err => {
                if (
                    err.response &&
                    err.response.data &&
                    err.response.data.errors
                ) {
                }
            });
    },
    data: {
        time: window.document.querySelector('meta[name="server-time"]').content,
        auction: {},
        contragents: [],
        contragent: {},
        errors: {},
        fd: false,
        purposes: [],
        capacities: [],
        federalDistricts: [],
        types: [],
        regions: [],
        products: [],
        miltiplicities: [],
        allStores: [],
        stores: [],
        target: {},
        filter: {
            federal_district: null,
            product: null,
            multiplicity: null,
            region: null,
            confirmed: 0
        },
        confirmedOptions: []
    },
    methods: {
        writeTo(contragent_id) {
            let app = this;
            axios
                .get("/web/v1/dialogues/check/" + contragent_id)
                .then(function(res) {
                    app.$router.push(
                        "/personal/dialogue/show/" + res.data.data.id
                    );
                });
        },
        getFederalDistricts() {
            let app = this;
            axios.get("/federalDistricts").then(function(res) {
                app.federalDistricts = res.data;
            });
        },

        getRegions(fd) {
            axios.get("/regions?federal_district_id=" + fd).then(function(res) {
                app.regions = res.data;
            });
        },
        getPurposes() {
            let app = this;
            axios.get("/purposes").then(function(res) {
                app.purposes = res.data;
            });
        },

        getCapacities(fd) {
            axios.get("/capacities").then(function(res) {
                app.capacities = res.data;
            });
        },
        getTags() {
            let app = this;
            axios.get("/tags").then(function(res) {
                app.tags = res.data;
            });
        },
        getProducts() {
            let app = this;
            axios.get("/products").then(function(res) {
                app.products = res.data;
            });
        },
        getTypes() {
            let app = this;
            axios.get("/types").then(function(res) {
                app.types = res.data;
            });
        },
        getAllStores() {
            let app = this;
            axios.get("/storesAll").then(function(res) {
                app.allStores = res.data.data;
            });
        },
        getMyStores() {
            let app = this;
            axios.get("/stores").then(function(res) {
                app.stores = res.data;
            });
        },
        getMultiplicities() {
            let app = this;
            axios.get("/multiplicities").then(function(res) {
                app.multiplicities = res.data;
            });
        },
        listenForBroadcast() {
            var that = this;
            Echo.channel("private-dialog." + app.company.id).listen(
                "Dialog",
                function(e) {
                    that.$emit("gotDialog", e.phrase);
                    console.log(e);
                    that.flash(
                        e.phrase.contragent.title + "<br>" + e.phrase.text,
                        "success"
                    );
                }
            );
            Echo.channel("every-minute").listen("PerMinute", function(e) {
                app.time = e.time;
                e.started.forEach(auction =>
                    that.flash(
                        that.__("Auction #") + auction.id + that.__(" started"),
                        "success"
                    )
                );
                e.finished.forEach(auction =>
                    that.flash(
                        that.__("Auction #") +
                            auction.id +
                            that.__(" finished"),
                        "primary"
                    )
                );
            });
            Echo.channel("message-pushed").listen("MessagePushed", function(e) {
                that.$emit("gotAuction", e.auction);
            });
            Echo.channel("dispute").listen("Dispute", function(e) {
                that.$emit("gotDispute", e.dispute);
            });
            Echo.channel("line").listen("Line", function(e) {
                that.$emit("gotLine", e.line);
            });
        }
    }
});

window.Vue.filter("formatDate", function(value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("DD.MM.YYYY");
    }
    return "";
});

window.Vue.filter("formatDateTime", function(value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("DD.MM.YYYY HH:mm");
    }
    return "";
});

window.Vue.filter("formatChatTime", function(value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("HH:mm:ss");
    }
    return "";
});

window.Vue.filter("formatMoney", function(value) {
    return new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB"
    }).format(value);
});
