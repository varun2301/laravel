import Vue from 'vue';
import VueRouter from 'vue-router';
import url from './env.js';

window._ = require('lodash');
window.Vue = Vue;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

Vue.use(VueRouter);


Vue.prototype.globalUrl = url;

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}