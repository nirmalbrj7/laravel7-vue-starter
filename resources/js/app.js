/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';

//Gate Authorization
import Gate from './gate';
Vue.prototype.$gate = new Gate(window.user);


//V form
import { Form, HasError, AlertError } from 'vform'
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);


//vue laravel pagination
Vue.component('pagination', require('laravel-vue-pagination'));



window.Form = Form;
import VueRouter from 'vue-router'
Vue.use(VueRouter);

//Progress bar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
});

//Sweet Aleart
import Swal from 'sweetalert2'
window.Swal = Swal;
const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
});
window.toast = toast;



//Routes
let routes = [
    { path: '/dashboard', component: require('./components/backend/Dashboard.vue').default },
    { path: '/profile', component: require('./components/backend/Profile.vue').default },
    { path: '/users', component: require('./components/backend/Users.vue').default },
    { path: '/developer', component: require('./components/backend/Developer.vue').default },
    { path: '*', component: require('./components/backend/NotFound.vue').default },
];

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
});

Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('customDate', function (created) {
    return moment(created).format('MMMM Do YYYY');
});


//fire an update on componet update
window.Fire = new Vue();




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


//passport
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
Vue.component(
    'not-found',
    require('./components/backend/NotFound.vue').default
);




// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('Dashboard', require('./components/backend/Dashboard.vue').default);
//Vue.component('Profile', require('./components/backend/Profile.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data:{
        search: ''
    },
    methods:{
        searchit: _.debounce(() =>{
            Fire.$emit('searching')
        },1000)
    }

});
