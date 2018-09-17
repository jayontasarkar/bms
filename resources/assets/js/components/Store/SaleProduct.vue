<template>
<span :class="className">
  <button type="button" class="btn btn-cyan" :class="btnClass" @click.prevent="show">
  + Sell product to outlets
  </button>
  <b-modal ref="saleProductModal"
  title="Sale product to outlet"
  :header-bg-variant="'primary'"
  :header-text-variant="'light'"
  centered
  size="lg"
  no-close-on-esc no-close-on-backdrop
  >
  <div class="row">
    <div class="col-md-4">
      <select class="form-control" v-model="district">
        <option value="">Select district</option>
        <option v-for="(district, index) in districts" :value="district.id" :key="index">
          {{ district.name }}
        </option>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-control" v-model="thana">
        <option value="">Select thana</option>
        <option v-for="(thana, index) in thanas" :value="thana.id" :key="index">
          {{ thana.name }} ({{ thana.outlets.length }})
        </option>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-control" v-model="outlet" v-validate="'required'"
        :class="{'is-invalid': errors.has('outlet') || errorList.outlet_id}" name="outlet"
        >
        <option value="">Select outlet</option>
        <option v-for="(outlet, index) in outlets" :value="outlet.id" :key="index">
          {{ outlet.name }}, {{ outlet.address }}
        </option>
      </select>
      <div class="invalid-feedback" v-if="errors.has('outlet') || errorList.outlet_id">
        {{ errors.first('outlet') || errorList.outlet_id[0] }}
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-md-3">
      <label for="vendor">Select vendor</label>
      <select class="form-control" v-model="vendor_id" v-validate="'required'"
        :class="{'is-invalid': errors.has('vendor') || errorList.vendor_id}" name="vendor"
        >
        <option value="">Select</option>
        <option v-for="(vendor, index) in vendors" :value="vendor.id" :key="index">
          {{ vendor.name }}
        </option>
      </select>
      <div class="invalid-feedback" v-if="errors.has('outlet') || errorList.vendor_id">
        {{ errors.first('vendor') || errorList.vendor_id[0] }}
      </div>
    </div>
    <div class="col-md-3">
      <label for="memo">Sales Order/Memo No.</label>
      <input type="text" name="sales order no" v-model="memo" class="form-control"
      :class="{'is-invalid': errors.has('sales order no') || errorList.memo}"  v-validate="'required'"
      >
      <div class="invalid-feedback" v-if="errors.has('sales order no') || errorList.memo">
        {{ errors.first('sales order no') || errorList.memo[0] }}
      </div>
    </div>
    <div class="col-md-3">
      <label for="memo">Sales Date</label>
      <input type="date" name="sales date" v-model="sales_date" class="form-control"
      :class="{'is-invalid': errors.has('sales date') || errorList.sales_date}"  v-validate="'required'"
      >
      <div class="invalid-feedback" v-if="errors.has('sales date') || errorList.sales_date">
        {{ errors.first('sales date') || errorList.sales_date[0] }}
      </div>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn btn-success mt-6 btn-block" @click="addProduct" :disabled="outlet == '' || vendor_id == ''">
      <i class="fe fe-plus mr-2"></i>Add product to sales order
      </button>
    </div>
  </div>
  <div class="row mt-5" v-if="productList.length">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table card-table table-vcenter">
          <thead>
            <tr class="bg-gray-dark">
              <th style="padding-left: 4px; padding-right: 4px;">S.L</th>
              <th>Purchased Product</th>
              <th>Quantity</th>
              <th>Unit Price</th>
              <th>Unit</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
          <tfoot class="bg-light-grey" v-if="total > 0">
          <tr>
            <td></td>
            <td>
              <input class="form-control" type="number" v-model="discount"
              placeholder="Discount Amount"
              step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
              >
            </td>
            <td></td>
            <td></td>
            <td><strong>Total:</strong></td>
            <td><strong>{{ beautifyAmount(total) }}/=</strong></td>
            <td></td>
          </tr>
          </tfoot>
          <tbody>
            <tr v-for="(list, index) in productList" :key="index">
              <td style="padding-left: 4px; padding-right: 4px;">
                {{ index + 1 }}
              </td>
              <td>
                <select class="form-control" v-model="list.product_id"
                  :name="'product' + index" v-validate="'required'"
                  :class="{'is-invalid': errors.has('product' + index)}"
                  >
                  <option value="">Select Product</option>
                  <option v-for="(product, index) in products" :value="product.id" :key="index">
                    {{ product.code }}-{{ product.title }} ({{ product.stock }})
                  </option>
                </select>
              </td>
              <td>
                <input type="number" style="width: 85px;" class="form-control"
                placeholder="Quantity" v-model="list.qty" :name="'qty' + index"
                :class="{'is-invalid': errors.has('qty' + index)}" v-validate="'required'"
                step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                >
              </td>
              <td>
                <input type="number" style="width: 90px;" class="form-control"
                placeholder="Unit Price" v-model="list.unit_price" v-validate="'required'"
                :name="'unit price' + index" :class="{'is-invalid': errors.has('unit price' + index)}"
                step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                >
              </td>
              <td>
                <select style="width: 80px;" class="form-control" v-model="list.unit"
                  :name="'unit' + index" v-validate="'required'"
                  :class="{'is-invalid': errors.has('unit' + index)}"
                  >
                  <option value="">Select</option>
                  <option value="piece">Piece</option>
                </select>
              </td>
              <td>
                {{ beautifyAmount(list.qty * list.unit_price) }}/=
              </td>
              <td style="padding-right: 0px;">
                <button class="remove-btn" @click.prevent="removeItem(list)"><strong>X</strong></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <div slot="modal-footer" class="w-100">
    <button type="button"
    class="float-right btn btn-primary"
    @click.prevent="submit"
    :class="{ 'btn-loading': loading }"
    :disabled="productList.length <= 0"
    >
    Sale Products in Outlet
    </button>
    <button type="button" class="float-right btn btn-danger mr-2" @click.prevent="hide">
    Close
    </button>
  </div>
  </b-modal>
</span>
</template>
<script>
  var moment = require('moment');
  export default {
    props: ['vendors', 'districts', 'url', 'className', 'btnClass'],
    data() {
      return {
        district: '',
        thana: '',
        outlet: '',
        vendor_id: '',
        products: [],
        thanas: [],
        outlets: [],
        memo: '',
        discount: null,
        sales_date: moment(new Date()).format('YYYY-MM-DD'),
        productList: [],
        errorList: {},
        loading: false
      }
    },
    computed: {
    total() {
      let list = this.productList.map(function(item){ return item.unit_price * item.qty });
  return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
    }
  },
    watch: {
      district: function(newValue, oldValue) {
        if( newValue != '' ) {
        let maped = this.districts.filter(district => district.id == newValue)
        this.thanas = maped[0].thanas;
        }
      },
      thana: function(newValue, oldValue) {
        if( newValue != '' ) {
          let maped = this.thanas.filter(thana => thana.id == newValue)
          this.outlets = maped[0].outlets;
        }
      },
      vendor_id: function(newValue, oldValue) {
        if( newValue != '' ) {
          this.products = this.vendors.filter(vendor => vendor.id == newValue)[0].products;
        }
      }
    },
    methods: {
      show() {
        this.clearFields()
        this.$refs.saleProductModal.show();
      },
      hide() {
        this.clearFields()
        this.$refs.saleProductModal.hide();
      },
      clearFields() {
        this.memo = ''
        this.outlet = ''
        this.discount = null
        this.sales_date = moment(new Date()).format('YYYY-MM-DD')
        this.productList = []
        this.$validator.reset()
        this.loading = false
      },
      submit() {
        this.$validator.validate().then(result => {
          if(result) {
            let data = {
              memo: this.memo,
              outlet_id: this.outlet,
              vendor_id: this.vendor_id,
              total_discount: this.discount,
              sales_date: this.sales_date,
              sales: this.productList
            };
            this.loading = true;
            axios.post(this.url, data)
              .then(response => {
                location.reload();
                this.loading = false;
              }).catch(error => {
                this.loading = false;
                this.errorList = error.response.data.errors
              })
          }
        });
      },
      addProduct() {
        this.productList.push({ product_id: '', unit_price: null, qty: null, unit: 'Pieces' })
      },
      removeItem(item) {
        this.productList.splice(this.productList.indexOf(item), 1);
      }
    }
  }
</script>
<style scoped>
  .remove-btn {
    cursor: pointer;
    padding: 0px;
    background: transparent;
    color: darkred;
    border: none;
  }
</style>