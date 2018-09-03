<template>
	<span class="float-right ml-auto">
    	<button type="button" class="btn btn-primary" @click.prevent="show">
			+ Purchase Product from Vendor
		</button>
    	<b-modal ref="storeProductModal"
             title="Purchase product into store from Vendor"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             size="lg"
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
                <div class="col-md-6">
                    <label for="vendor">Select Vendor</label>
                    <select name="vendor" v-model="vendor_id" class="form-control"
                            v-validate="'required'" :class="{'is-invalid': errors.has('vendor') || errorList.vendor_id }"
                    >
                        <option value="">Select vendor</option>
                        <option v-for="(vendor, index) in vendorList" :key="index" :value="vendor.id">
                            {{ vendor.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.has('vendor') || errorList.vendor_id">
                        {{ errors.first('vendor') || errorList.vendor_id[0] }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="memo">Invoice/Purchase Order No.</label>
                    <input type="text" class="form-control" id="memo" v-model="memo"  name="invoice" 
						   v-validate="'required'" :class="{'is-invalid': errors.has('invoice') || errorList.memo }"
                    >
                    <div class="invalid-feedback" v-if="errors.has('invoice') || errorList.memo">
                        {{ errors.first('invoice') || errorList.memo[0] }}
                    </div>
                </div>
            </div>
            <div class="row mt-3">
            	<div class="col-md-6">
                    <label for="purchase_date">Purchase Receive Date</label>
                    <input type="date" class="form-control" id="purchase_date" v-model="purchase_date"  name="purchase date" 
						   v-validate="'required'" :class="{'is-invalid': errors.has('purchase date')}"
                    >
                    <div class="invalid-feedback" v-if="errors.has('purchase date')">
                        {{ errors.first('purchase date') }}
                    </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                	<button type="button" class="btn btn-success mt-5" @click="addProduct" :disabled="vendor_id == ''">
                		<i class="fe fe-plus mr-2"></i>Add Purchase Item
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
								<option v-for="product in productsByVendor" :value="product.id">{{ product.title }}</option>
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
    				    :disabled="this.productList.length <= 0"
    		    >
		           Purchase Products
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
		props: ['vendors', 'products', 'url'],
		data() {
			return {
				vendor_id: '',
				vendorList: this.vendors,
				purchase_date: moment(new Date()).format('YYYY-MM-DD'),
				memo: '',
				productsByVendor: [],
				productList: [],
				errorList: {},
				discount: null,
				loading: false
			}
		},
		watch: {
	        vendor_id: function(newValue, oldValue) {
	            if( newValue != '' ) {
	                let maped = this.products.filter(product => product.vendor_id == newValue)
	                this.productsByVendor = maped;
	            }
	        }
	    },
	    computed: {
	    	total() {
	    		let list = this.productList.map(function(item){ return item.unit_price * item.qty });
	            return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
	    	}
	    },
		methods: {
			show() {
				this.$refs.storeProductModal.show();
			},
			hide() {
				this.vendor_id = ''
				this.vendorList = this.vendors
				this.purchase_date = moment(new Date()).format('YYYY-MM-DD')
				this.memo = ''
				this.productsByVendor = []
				this.productList = []
				this.$validator.reset()
				this.$refs.storeProductModal.hide();
			},
			submit() {
				this.$validator.validate().then(result => {
	                if(result) {
	                	let data = {
	                		vendor_id: this.vendor_id,
	                		memo: this.memo,
	                		total_balance: this.total,
	                		total_discount: parseFloat(this.discount),
	                		purchase_date: this.purchase_date,
	                		purchases: this.productList
	                	}
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