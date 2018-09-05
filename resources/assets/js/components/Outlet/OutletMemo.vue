<template>
	<span>
    	<a href="#" class="btn btn-link" @click.prevent="show">
			{{ title ? title : sales.memo }}
		</a>
    	<b-modal ref="outletMemoModal"
             title="Sales Report for Sales Order "
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered size="lg"
             no-close-on-esc no-close-on-backdrop
        >
        	<div class="row">
        		<div class="col-md-12">
        			<h4>Product Sales Summary</h4>
        			<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
						<thead>
							<tr class="bg-gray-dark">
								<th class="w-1">No.</th>
								<th>Product Name</th>
								<th>Quantity</th>
								<th>Price Rate</th>
								<th>Total Amount</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="3"></td>
								<td><strong>Total Amount:</strong></td>
								<td><strong>{{ beautifyAmount(total) }}/=</strong></td>
							</tr>
							<tr v-if="sales.total_discount">
								<td colspan="3"></td>
								<td><strong>Total Discount:</strong></td>
								<td><strong>{{ beautifyAmount(sales.total_discount) }}/=</strong></td>
							</tr>
							<tr v-if="sales.total_discount">
								<td colspan="3"></td>
								<td><strong>Grand Total:</strong></td>
								<td><strong>{{ beautifyAmount(total - sales.total_discount) }}/=</strong></td>
							</tr>
						</tfoot>
						<tbody>
							<tr v-for="(record, index) in records" :key="index">
								<td>{{ index + 1 }}</td>
								<td>{{ record.product.code }} - {{ record.product.title }}</td>
								<td>{{ record.qty }} {{ record.unit.toUpperCase() }}</td>
								<td>{{ beautifyAmount(record.unit_price) }}/=</td>
								<td>{{ beautifyAmount(record.qty * record.unit_price) }}/=</td>
							</tr>
						</tbody>
					</table>
					<h4 class="mt-3" v-if="transactions.length > 0">Balance Collection Summary</h4>
					<table class="table card-table table-bordered table-vcenter text-nowrap" 
					       border="1" v-if="transactions.length > 0"
					>
						<tfoot>
							<tr>
								<td colspan="3"></td>
								<td><strong>Total Payment:</strong></td>
								<td><strong>{{ beautifyAmount(totalPayments) }}/=</strong></td>
							</tr>
						</tfoot>
						<tbody>
							<tr v-for="(transaction, index) in transactions" :key="index">
								<td colspan="2">
									{{ transaction.comment ? transaction.comment : 'AMOUNT RECEIVED/COLLECTED FROM OUTLET' }}
								</td>
								<td colspan="2" class="text-center">{{ beautifyDate(transaction.transaction_date) }}</td>
								<td>{{ beautifyAmount(transaction.amount) }}/=</td>
							</tr>
						</tbody>
					</table>
        		</div>
        	</div>
        	<div slot="modal-footer" class="w-100">
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
		props: {
			sales: {
				default: null
			}, 
			records: {
				default: null
			}, 
			transactions: {
				default: null
			},
			title: {
				default: false
			}
		},
		computed: {
			total() {
	    		let list = this.records.map(function(item){ return item.unit_price * item.qty });
	            return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
	    	},
	    	totalPayments() {
	    		let list = this.transactions.map(function(item){ return item.amount });
	            return list.length ? list.reduce((acc, curr) =>   acc + curr) : 0;
	    	}
		},
		methods: {
			show() {
				this.$refs.outletMemoModal.show();
			},
			hide() {
				this.$refs.outletMemoModal.hide();
			}
		}
	}
</script>