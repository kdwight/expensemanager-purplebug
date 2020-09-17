require('./bootstrap');
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)

Vue.component('Users', require('./components/Users.vue').default);
Vue.component('Roles', require('./components/Roles.vue').default);
Vue.component('Categories', require('./components/Categories.vue').default);
Vue.component('Expenses', require('./components/Expenses.vue').default);
Vue.component('TotalExpensesChart', require('./components/TotalExpensesChart.vue').default);

let authUser = (document.querySelector("meta[name='auth']").getAttribute('content'))
Vue.prototype.$auth = authUser ? JSON.parse(authUser) : '';

window.fetchData = function () {
    window.events.$emit('fetchData');
};

const app = new Vue({
    el: '#app',

    data() {
        return {
            roles: [],
            categories: [],
        };
    },

    async created() {
        if (this.$auth) {
            if (this.$auth.role_id == 1) {
                await axios.get("/roles/list").then(({ data }) => {
                    this.roles = data;
                });
            }

            await axios.get("/categories/list").then(({ data }) => {
                this.categories = data;
            });
        }
    }
});
