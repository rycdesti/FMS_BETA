// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Datepicker from 'vuejs-datepicker'
import {en} from 'vuejs-datepicker/dist/locale'
import Notifications from 'vue-notification'
import Sweetalert from 'vue-sweetalert2'
import Vuelidate from 'vuelidate'
import Loading from './components/Loading'
import Select2 from './components/Select'
import App from './App'
import router from './router'
import store from './store'
import LoadScript from 'vue-plugin-load-script'

Vue.use(BootstrapVue);
Vue.use(Notifications);
Vue.use(Sweetalert);
Vue.use(Vuelidate);
Vue.use(LoadScript);

Vue.filter('state', (value, dirtyOnly = true) => {
    if (dirtyOnly) {
        if (!value.$dirty)
            return null
    }

    return !value.$invalid ? 'valid' : 'invalid'
});

Vue.component('vform', require('vform'));
Vue.component('b-loading', Loading);
Vue.component('b-select-2', Select2);
Vue.component('b-datepicker', {
    extends: Datepicker,
    props: {
        bootstrapStyling: {
            type: Boolean,
            default: true,
        },
        language: {
            type: Object,
            default: () => en,
        },
    },
});

// Vue.prototype.$swal = {
//     success: function (message) {
//         swal(
//             'Success!',
//             message,
//             'success')
//     },
//     error: function (message) {
//         swal(
//             'Whoops!',
//             message ? message : 'An error has occurred',
//             'error'
//         )
//     }
// };


export default new Vue({
    el: '#app',
    router: router,
    store: store,
    components: {App},
    template: '<App/>',
})

// Destroy listeners
Vue.prototype.$listener = {
    destroy: function (buttons, functions, root) {
        buttons.forEach(function (button) {
            $(document).off('click', button);
        });
        functions.forEach(function (func) {
            root.$off(func);
        });
    }
};

Vue.prototype.$country_state_city = {
    load: function(component) {
        component.$loadScript("https://geodata.solutions/includes/countrystatecity.js");
    },
    unload: function(component) {
        component.$unloadScript("https://geodata.solutions/includes/countrystatecity.js");
    }
};
