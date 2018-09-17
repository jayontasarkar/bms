
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
Vue.component('update-outlet', require('./components/Outlet/UpdateOutlet'));
Vue.component('outlet-memo', require('./components/Outlet/OutletMemo'));
Vue.component('sale-outlet-product', require('./components/Outlet/SaleOutletProduct'));
Vue.component('add-opening-balance', require('./components/Outlet/AddOpeningBalance'));
Vue.component('collection-from-outlet', require('./components/Outlet/CollectionFromOutlet'));
Vue.component('update-opening-balance', require('./components/Outlet/UpdateOpeningBalance'));
Vue.component('update-transactions', require('./components/Outlet/UpdateTransactions'));

//Collections
Vue.component('dashboard-collection', require('./components/Outlet/DashboardCollection'));
Vue.component('update-collection', require('./components/Outlet/UpdateCollection'));

// ReadySale
Vue.component('ready-sale-memo', require('./components/ReadySale/ReadySaleMemo'));
Vue.component('update-ready-sales', require('./components/ReadySale/UpdateReadySales'));

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
Vue.component('ready-sale', require('./components/Store/ReadySale'));
Vue.component('update-sales', require('./components/Store/UpdateSales'));
Vue.component('update-purchases', require('./components/Store/UpdatePurchases'));

// Bank
Vue.component('manage-bank', require('./components/Bank/ManageBank'));
Vue.component('edit-bank-transaction', require('./components/Bank/EditBankTransaction'));

const app = new Vue({
    el: '#app'
}).$mount();
