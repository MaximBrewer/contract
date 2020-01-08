/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

window.Vue.mixin(require('./trans'))


import VueRouter from "vue-router";



window.Vue.use(VueRouter);

import ContragentIndex from "./components/contragents/contragentIndex.vue";
import ContragentCreate from "./components/contragents/contragentCreate.vue";
import ContragentEdit from "./components/contragents/contragentEdit.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";

const routes = [
    {
        path: "/contragents",
        component: ContragentIndex ,
        name: "contragentIndex"
    },
    {
        path: "/contragents/create",
        component: ContragentCreate,
        name: "createContragent"
    },
    {
        path: "/contragents/edit/:id",
        component: ContragentEdit,
        name: "editContragent"
    },
    {
        path: "/auctions",
        component: AuctionIndex ,
        name: "auctionIndex"
    },
    {
        path: "/auctions/create",
        component: AuctionCreate,
        name: "createAuction"
    },
    {
        path: "/auctions/edit/:id",
        component: AuctionEdit,
        name: "editAuction"
    }
];

const router = new VueRouter({ routes });

const app = new Vue({ router }).$mount("#app");
