<template>
	<div>
		<div class="form-group">
			<div class="row justify-content-start">
				<div class="col-md-3 pt-2">
					<h4>Purchase Order No:</h4>
				</div>
				<div class="col-md-3">
					<input type="text" v-model="memo" class="form-control"
							v-validate="'required'" :name="'Memo No.'"
							:class="{ 'is-invalid': errors.has('Memo No.') || errorList.memo }"
					>
				</div>
				<div class="col-md-6 pt-2" v-if="errors.has('Memo No.') || errorList.memo">
					<p style="color: darkred;">
						{{ errors.first('Memo No.') || errorList.memo[0] }}
					</p>
				</div>
			</div>
		</div>
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
                <tr v-for="(list, index) in purchases" :key="index">
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
            </tbody>
        </table>
        <div class="row">
        	<div class="col-md-5">
        		<div class="pl-6 form-group">
        			<label for="">Discount Amount</label>
        			<input type="text" class="form-control" v-model="total_discount" step="0.01" pattern="^\d+(?:\.\d{1,2})?$">
        		</div>
        	</div>
        	<div class="col-md-4">
        		<div class="form-group">
        			<label for="">Sales Date</label>
        			<input type="date" class="form-control" v-model="purchase_date">
        		</div>
        	</div>
        	<div class="col-md-3">
        		<div class="form-group mt-6 text-center">
		        	<button class="btn btn-info" :class="{ 'btn-loading': loading }" type="button" @click.prevent="submit">Update Purchase</button>
		        </div>
        	</div>
        </div>
	</div>
</template>
<script>
    var moment = require('moment')
	export default {
		props: ['info', 'products', 'url', 'discount', 'salesDate', 'orderMemo'],
		data() {
			return {
				memo: this.orderMemo,
				purchases: [],
				productLists: [],
				total_discount: this.discount,
				purchase_date: moment(this.salesDate).format('YYYY-MM-DD'),
				loading: false,
				errorList: {}
			}
		},
		methods: {
			addItem() {
				this.purchases.push({ product_id: '', unit_price: null, qty: null, unit: 'Pieces' })
			},
			removeItem(item) {
				this.purchases.splice(this.purchases.indexOf(item), 1);
			},
			submit() {
				this.$validator.validate().then(result => {
                	if(result) {
                		let data = {
                			memo: this.memo,
                			purchases: this.purchases,
                			total_discount: this.total_discount,
                			purchase_date: this.purchase_date
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
			this.purchases = this.info
			this.productLists = this.products
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