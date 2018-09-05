
require('./bootstrap');

window.Vue = require('vue');
const VueInputMask = require('vue-inputmask').default

import BootstrapVue from 'bootstrap-vue'
import VeeValidate from 'vee-validate';
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);
Vue.use(VeeValidate);
Vue.use(VueInputMask)

Vue.component('flash', require('./components/Common/Flash'));
Vue.component('remove-btn', require('./components/Common/RemoveBtn'));
Vue.component('payment', require('./components/Common/Payment'));
Vue.component('collection', require('./components/Common/Collection'));

// Expenses
Vue.component('create-expense', require('./components/Expense/CreateExpense'));
Vue.component('edit-expense', require('./components/Expense/EditExpense'));

// Users
Vue.component('create-user', require('./components/User/CreateUser'));

// Outlet
Vue.component('create-outlet', require('./components/Outlet/CreateOutlet'));
Vue.component('edit-outlet', require('./components/Outlet/EditOutlet'));
Vue.component('outlet-memo', require('./components/Outlet/OutletMemo'));

// Vendors
Vue.component('create-vendor', require('./components/Vendors/CreateVendor'));
Vue.component('edit-vendor', require('./components/Vendors/EditVendor'));
Vue.component('vendor-memo', require('./components/Vendors/VendorMemo'));

//Products
Vue.component('create-product', require('./components/Product/CreateProduct'));
Vue.component('edit-product', require('./components/Product/EditProduct'));

// Store
Vue.component('purchase-product', require('./components/Store/PurchaseProduct'));
Vue.component('sale-product', require('./components/Store/SaleProduct'));

// Bank
Vue.component('manage-bank', require('./components/Bank/ManageBank'));
Vue.component('edit-bank-transaction', require('./components/Bank/EditBankTransaction'));

const app = new Vue({
    el: '#app'
}).$mount();
