/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
 
window.Vue = require('vue');
import VueRouter from 'vue-router';
 
window.Vue.use(VueRouter);
 
import ContragentsIndex from './components/contragents/contragentsIndex.vue';
import ContragentsCreate from './components/contragents/contragentsCreate.vue';
import ContragentsEdit from './components/contragents/contragentsEdit.vue';
 
const routes = [
 {
    path: '/',
    components: {
        contragentsIndex: ContragentsIndex
    }  
 },
 {path: '/api/v1/contragents/create', component: ContragentsCreate, name: 'createContragent'},
 {path: '/api/v1/contragents/edit/:id', component: ContragentsEdit, name: 'editContragent'},
]
 
const router = new VueRouter({ routes })
 
const app = new Vue({ router }).$mount('#app');
