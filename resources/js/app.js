/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import "vue-loading-overlay/dist/vue-loading.css";
import "vue-datetime/dist/vue-datetime.css";
import "vue-flash-message/dist/vue-flash-message.min.css";

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
import { Datetime } from 'vue-datetime'
Vue.component("datetime", Datetime);
Vue.use(VueRouter);
Vue.use(VueFlashMessage);
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);
import VModal from "vue-js-modal";
Vue.use(VModal);
import swicthCheckbox from "./components/swicthCheckbox.vue";
Vue.component("switch-checkbox", swicthCheckbox, {});
import vSelect from "vue-select";
Vue.component("v-select", vSelect, {});
import StarRating from "vue-star-rating";
Vue.component("StarRating", StarRating, {});

import Loading from "vue-loading-overlay";
Vue.use(
    Loading,
    {
        // props
        color: "red"
    },
    {
        // slots
    }
);


import ContragentIndex from "./components/contragents/contragentIndex.vue";
import ContragentCreate from "./components/contragents/contragentCreate.vue";
import ContragentEdit from "./components/contragents/contragentEdit.vue";
import Company from "./components/contragents/company.vue";
import contragentShow from "./components/contragents/contragentShow.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionMy from "./components/auctions/auctionMy.vue";
import AuctionBid from "./components/auctions/auctionBid.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";
import AuctionShow from "./components/auctions/auctionShow.vue";
import TargetCreate from "./components/targets/targetCreate.vue";
import TargetEdit from "./components/targets/targetEdit.vue";
import TargetIndex from "./components/targets/targetIndex.vue";
import ReviewsIndex from "./components/contragents/reviewsIndex.vue";

Vue.prototype.user = window.user;

const app = new Vue({
    router: new VueRouter({
        mode: "history",
        beforeRouteUpdate(to, from, next) {
            // react to route changes...
            // don't forget to call next()
        },
        routes: [
            { path: "/personal", redirect: "/personal/auctions" },
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
                    "/api/v1/federalDistricts?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token
                )
                .then(function(resp) {
                    app.federalDistricts = resp.data;
                });
        },
        getRegions(app, fd) {
            axios
                .get(
                    "/api/v1/regions?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token,
                    fd
                )
                .then(function(resp) {
                    app.regions = resp.data;
                });
        },
        getTypes(app) {
            axios
                .get(
                    "/api/v1/types?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token
                )
                .then(function(resp) {
                    app.types = resp.data;
                });
        },
        getMyStores() {
            axios
                .get(
                    "/api/v1/stores?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token
                )
                .then(function(resp) {
                    app.stores = resp.data;
                });
        },
        getMultiplicities(app) {
            axios
                .get(
                    "/api/v1/multiplicities?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token
                )
                .then(function(resp) {
                    app.multiplicities = resp.data;
                });
        },
        getProducts(app) {
            axios
                .get(
                    "/api/v1/types?csrf_token=" +
                        window.csrf_token +
                        "&api_token=" +
                        window.api_token
                )
                .then(function(resp) {
                    app.products = resp.data;
                });
        },
        listenForBroadcast() {
            var that = this;
            // Echo.join("survey." + survey_id)
            //     .here(users => {
            //         console.log(users);
            //         this.users_viewing = users;
            //         this.$forceUpdate();
            //     })
            //     .joining(user => {
            //         //   if (this.checkIfUserAlreadyViewingSurvey(user)) {
            //         //     this.users_viewing.push(user);
            //         //     this.$forceUpdate();
            //         //   }
            //     })
            //     .leaving(user => {
            //         //this.removeViewingUser(user);
            //         this.$forceUpdate();
            //     });
            // Echo.channel(
            //     "cross_contractru_database_presence-survey." + survey_id
            // ).listen("MessagePushed", function(e) {
            //     console.log(e);
            // });
            Echo.channel("every-minute").listen("PerMinute", function(e) {
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
            Echo.channel("message-pushed").listen("MessagePushed", function(e) {
                console.log(e);
                that.$emit("gotAuction", e.auction);
            });
        }
    }
}).$mount("#app");

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
