/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
window.Vue.mixin(require("./trans"));

import VueRouter from "vue-router";
import VueFlashMessage from "vue-flash-message";

require("vue-flash-message/dist/vue-flash-message.min.css");

window.Vue.use(VueRouter);
window.Vue.use(VueFlashMessage);

import ContragentIndex from "./components/contragents/contragentIndex.vue";
import ContragentCreate from "./components/contragents/contragentCreate.vue";
import ContragentEdit from "./components/contragents/contragentEdit.vue";
import Company from "./components/contragents/Company.vue";
import contragentShow from "./components/contragents/contragentShow.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionMy from "./components/auctions/auctionMy.vue";
import AuctionBid from "./components/auctions/auctionBid.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";
import AuctionShow from "./components/auctions/auctionShow.vue";

const routes = [
    { path: "/personal", redirect: "/personal/auctions" },
    {
        path: "/personal/contragents",
        component: ContragentIndex,
        name: "contragentIndex"
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
        name: "auctionMy",
        props: {
            action: "my"
        }
    },
    {
        path: "/personal/auctions/bid",
        component: AuctionBid,
        name: "auctionBid",
        props: {
            action: "bid"
        }
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
    }
];

if (
    !!window.user &&
    !!window.user.contragents &&
    !!window.user.contragents[0]
) {
    routes.push({
        path: "/personal/company",
        component: Company,
        name: "company"
    });
}

Vue.prototype.user = window.user;

const router = new VueRouter({
    mode: "history",
    routes: routes
});

const app = new Vue({ router }).$mount("#app");
