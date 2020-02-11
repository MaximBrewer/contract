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
import {
    VTooltip,
    VPopover,
    VClosePopover
} from "v-tooltip";
Vue.directive("tooltip", VTooltip);
Vue.directive("close-popover", VClosePopover);
Vue.component("v-popover", VPopover);
import {
    Datetime
} from 'vue-datetime'
Vue.component("datetime", Datetime);
Vue.use(VueRouter);
Vue.use(VueFlashMessage);
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);
import VModal from "vue-js-modal";
Vue.use(VModal);
import swicthCheckboxCanbet from "./components/swicthCheckboxCanbet.vue";
import swicthCheckboxObserve from "./components/swicthCheckboxObserve.vue";
Vue.component("switch-checkbox-canbet", swicthCheckboxCanbet, {});
Vue.component("switch-checkbox-observe", swicthCheckboxObserve, {});
import vSelect from "vue-select";
Vue.component("v-select", vSelect, {});
import StarRating from "vue-star-rating";
Vue.component("StarRating", StarRating, {});

import Vue from 'vue'
// const VueInputMask = require('vue-inputmask').default
// Vue.use(VueInputMask)

import Chat from "vue-beautiful-chat";
Vue.use(Chat);

import Loading from "vue-loading-overlay";
Vue.use(
    Loading, {
    // props
    color: "red"
}, {
    // slots
}
);

import ContragentIndex from "./components/contragents/contragentIndex.vue";
import ContragentCreate from "./components/contragents/contragentCreate.vue";
import ContragentEdit from "./components/contragents/contragentEdit.vue";
import Company from "./components/contragents/company.vue";
import contragentShow from "./components/contragents/contragentShow.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionArchive from "./components/auctions/auctionArchive.vue";
import AuctionMy from "./components/auctions/auctionMy.vue";
import AuctionBid from "./components/auctions/auctionBid.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";
import AuctionShow from "./components/auctions/auctionShow.vue";
import TargetCreate from "./components/targets/targetCreate.vue";
import TargetEdit from "./components/targets/targetEdit.vue";
import TargetIndex from "./components/targets/targetIndex.vue";
import ReviewsIndex from "./components/contragents/reviewsIndex.vue";


import AllAuctions from "./components/allAuctions.vue";
Vue.component("AllAuctions", AllAuctions, {});

const app = new Vue({
    router: new VueRouter({
        mode: "history",
        beforeRouteUpdate(to, from, next) {
            // react to route changes...
            // don't forget to call next()
        },
        routes: [{
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
            component: contragentShow,
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
            path: "/personal/auctions/create",
            component: AuctionCreate,
            name: "createAuction"
        },
        {
            path: "/personal/auctions/show/:id",
            component: AuctionShow,
            name: "showAuction"
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
            path: "/personal/company",
            component: Company,
            name: "company"
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
        }
        ]
    }),
    created() {
        this.listenForBroadcast();
    },
    data: {
        time: window.document.querySelector('meta[name="server-time"]').content,
        auction: {}
    },
    methods: {
        getFederalDistricts(app) {
            axios
                .get(
                    "/federalDistricts?csrf_token=" +
                    window.csrf_token
                )
                .then(function (resp) {
                    app.federalDistricts = resp.data;
                });
        },
        getRegions(app, fd) {
            axios
                .get(
                    "/regions?csrf_token=" +
                    window.csrf_token +
                    "&federal_district_id=" +
                    fd
                )
                .then(function (resp) {
                    app.regions = resp.data;
                });
        },
        getTypes(app) {
            axios
                .get(
                    "/types?csrf_token=" +
                    window.csrf_token
                )
                .then(function (resp) {
                    app.types = resp.data;
                });
        },
        getMyStores(app) {
            axios
                .get(
                    "/stores?csrf_token=" +
                    window.csrf_token +
                    "&api_token=" +
                    window.api_token
                )
                .then(function (resp) {
                    app.stores = resp.data;
                });
        },
        getMultiplicities(app) {
            axios
                .get(
                    "/multiplicities?csrf_token=" +
                    window.csrf_token
                )
                .then(function (resp) {
                    app.multiplicities = resp.data;
                });
        },
        getProducts(app) {
            axios
                .get(
                    "/products?csrf_token=" +
                    window.csrf_token
                )
                .then(function (resp) {
                    app.products = resp.data;
                });
        },
        listenForBroadcast() {
            var that = this;
            Echo.channel("every-minute").listen("PerMinute", function (e) {
                console.log(e);
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
            Echo.channel("message-pushed").listen("MessagePushed", function (e) {
                console.log(e);
                that.$emit("gotAuction", e.auction);
            });
        }
    }
})


window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

axios
    .get(
        "/auth"
    )
    .then(res => {
        if (!!res.data.user) {
            window.user = res.data.user;
            window.api_token = res.data.api_token;
            Vue.prototype.user = window.user;
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.api_token;
        }
        app.$mount("#app");
    })
    .catch(err => {
        if (err.response && err.response.data && err.response.data.errors) { }
    });



window.Vue.filter("formatDate", function (value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("DD.MM.YYYY");
    }
    return "";
});

window.Vue.filter("formatDateTime", function (value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("DD.MM.YYYY HH:mm");
    }
    return "";
});

window.Vue.filter("formatChatTime", function (value) {
    if (value) {
        return moment(String(value))
            .utcOffset("+03:00")
            .format("HH:mm:ss");
    }
    return "";
});
