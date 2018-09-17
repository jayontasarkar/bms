<template>
	<div>
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
                <tr v-for="(list, index) in sales" v-if="info.records.length" :key="index">
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
        <div class="row">
        	<div class="col-md-4">
        		<div class="pl-6 form-group">
        			<label for="">Discount Amount</label>
        			<input type="text" class="form-control" v-model="total_discount" step="0.01" pattern="^\d+(?:\.\d{1,2})?$">
        		</div>
        	</div>
        	<div class="col-md-4"> 
        		<div class="form-group">
        			<label for="">Sales Date</label>
        			<input type="date" class="form-control" v-model="sales_date">
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
		props: ['info', 'products', 'url', 'discount'],
		data() {
			return {
				sales: this.info.records,
				productLists: [],
				total_discount: this.discount,
				sales_date: moment(this.info.sales_date).format('YYYY-MM-DD'),
				loading: false
			}
		},
		computed: {
			total() {
				let list = this.sales.map(function(item){ return item.unit_price * item.qty });
  				return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
			}
		},
		methods: {
			addItem() {
				this.sales.push({ product_id: '', unit_price: null, qty: null, unit: 'Pieces' })
			},
			removeItem(item) {
				this.sales.splice(this.sales.indexOf(item), 1);
			},
			submit() {
				this.$validator.validate().then(result => {
                	if(result) {
                		let data = {
                			sales: this.sales,
                			total_discount: this.total_discount,
                			sales_date: this.sales_date
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