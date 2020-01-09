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
import contragentShow from "./components/contragents/contragentShow.vue";
import AuctionIndex from "./components/auctions/auctionIndex.vue";
import AuctionCreate from "./components/auctions/auctionCreate.vue";
import AuctionEdit from "./components/auctions/auctionEdit.vue";

const routes = [
    {
        path: "/personal/contragents",
        component: ContragentIndex ,
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
        component: AuctionIndex ,
        name: "auctionIndex"
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
    }
];

Vue.prototype.$user = {
    id: document.querySelector("meta[name='user-id']").getAttribute('content'),
    username: document.querySelector("meta[name='user-username']").getAttribute('content'),
    email: document.querySelector("meta[name='user-email']").getAttribute('content')
}

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({ router }).$mount("#app");
