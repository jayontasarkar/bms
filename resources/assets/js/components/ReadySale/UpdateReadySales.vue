<template>
	<div>
		<div class="row">
			<div class="col-md-6">
				<label for="vendor">Select vendor</label>
				<select name="vendor" id="" class="form-control" v-model="vendor_id" v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('vendor') }"
    			>
					<option value="">Select</option>
					<option v-for="(vendor, index) in vendors" :value="vendor.id" :key="index">
						{{ vendor.name }}
					</option>
				</select>
				<div class="invalid-feedback" v-if="errors.has('title')">
					{{ errors.first('vendor') }}
				</div>
			</div>
			<div class="col-md-6">
				<label for="vendor">Memo/Order No.</label>
				<input type="text" name="memo" v-model="memo" class="form-control" v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('memo') || errorList.memo }">
    			<div class="invalid-feedback" v-if="errors.has('memo') || errorList.memo">
					{{ errors.first('memo') || errorList.memo[0] }}
				</div>			   
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-md-12">
				<label for="vendor">Ready Sale Details</label>
				<input type="text" name="ready sale details" v-model="ready_sale_details" 
				       class="form-control" v-validate="'required'"
    				   :class="{ 'is-invalid': errors.has('ready sale details') }"
    		    >
				<div class="invalid-feedback" v-if="errors.has('ready sale details') ">
					{{ errors.first('ready sale details') }}
				</div>
			</div>
		</div>
		<div class="table-responsive mt-5">
			<table class="table card-table table-vcenter">
			    <thead>
			        <tr class="bg-gray-dark">
			            <th style="padding-left: 4px; padding-right: 4px;">S.L</th>
			            <th>Product List</th>
			            <th>Unit Price</th>
			            <th>Quantity</th>
			            <th>Unit</th>
			            <th><a href="#" class="btn btn-outline-success btn-sm"@click.prevent="addItem">ADD</a></th>
			        </tr>
			    </thead>
	            <tbody>
	                <tr v-for="(list, index) in records" v-if="info.records.length" :key="index">
	                  <td style="padding-left: 4px; padding-right: 4px;">
	                  	{{ index + 1 }}
	                  </td>
	                  <td>
	      			 	<select v-model="list.product_id" class="form-control">
	      			 		<option v-for="(product, index) in productLists" :value="product.id" :key="index">
	      			 			{{ product.code }} - {{ product.title }} ({{ product.stock }})
	      			 		</option>
	      			 	</select>
	                  </td>
	                  <td>
	                  	<input type="number" style="width: 90px;" class="form-control" 
								       placeholder="Unit Price" v-model="list.unit_price" v-validate="'required'"
								       :name="'unit price' + index" :class="{'is-invalid': errors.has('unit price' + index)}"
									   step="0.01" pattern="^\d+(?:\.\d{1,2})?$" 	
								>
	                  </td>
	                  <td>
								<input type="number" style="width: 90px;" class="form-control" 
								       placeholder="Qty" v-model="list.qty" v-validate="'required'"
								       :name="'quantity' + index" :class="{'is-invalid': errors.has('quantity' + index)}"
									   step="0.01" pattern="^\d+(?:\.\d{1,2})?$" 	
								>
	                  </td>
	                  <td>
	                  	{{ list.unit.toUpperCase() }}
	                  </td>
	                  <td style="padding-right: 0px; align-right">
	                  	<button class="remove-btn" @click.prevent="removeItem(list)"><strong>X</strong></button>
	                  </td>
	                </tr>
	                <tr v-if="total > 0">
	                	<td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
	                	<td colspan="3">
	                		<strong>{{ beautifyAmount(total) }}/=</strong>
	                	</td>
	                </tr>
	            </tbody>
	        </table>
		</div>
        <div class="row">
        	<div class="col-md-4">
        		<div class="pl-6 form-group">
        			<label for="">Discount Amount</label>
        			<input type="text" class="form-control" v-model="total_discount" step="0.01" pattern="^\d+(?:\.\d{1,2})?$">
        		</div>
        	</div>
        	<div class="col-md-4"> 
        		<div class="form-group">
        			<label for="">Ready Date</label>
        			<input type="date" class="form-control" v-model="ready_sale_date">
        		</div>       	
        	</div>
        	<div class="col-md-4">
        		<div class="form-group mt-6 text-right">
		        	<button class="btn btn-info" :class="{ 'btn-loading': loading }" type="button" @click.prevent="submit">Update Sales</button>
		        </div>
        	</div>
        </div>
	</div>
</template>
<script>
    var moment = require('moment')
	export default {
		props: ['info', 'products', 'url', 'vendors'],
		data() {
			return {
				memo: this.info.memo,
				ready_sale_details: this.info.ready_sale_details,
				vendor_id: this.info.vendor_id,
				records: this.info.records,
				productLists: [],
				total_discount: this.info.total_discount,
				ready_sale_date: moment(this.info.ready_sale_date).format('YYYY-MM-DD'),
				loading: false,
				errorList: {}
			}
		},
		computed: {
			total() {
				let list = this.records.map(function(item){ return item.unit_price * item.qty });
  				return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
			}
		},
		methods: {
			addItem() {
				this.records.push({ product_id: '', unit_price: null, qty: null, unit: 'Pieces' })
			},
			removeItem(item) {
				this.records.splice(this.records.indexOf(item), 1);
			},
			submit() {
				this.$validator.validate().then(result => {
                	if(result) {
                		let data = {
                			memo: this.memo,
                			vendor_id: this.vendor_id,
                			ready_sale_details: this.ready_sale_details,
                			records: this.records,
                			amount: this.total,
                			total_discount: this.total_discount,
                			ready_sale_date: this.ready_sale_date
                		};
                		this.loading = true;
	                	axios.patch(this.url, data)
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
		},
		mounted() {
			let filtered = this.products.filter(item => item.vendor_id == this.info.vendor_id);
			this.productLists = filtered;
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