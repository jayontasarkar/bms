<template>
	<div>
		<table class="table card-table table-vcenter">
		    <thead>
		        <tr class="bg-gray-dark">
		            <th style="padding-left: 4px; padding-right: 4px;">S.L</th>
		            <th>Comment</th>
		            <th>Transaction Date</th>
		            <th>Transaction Amount</th>
		            <th>&nbsp;</th>
		        </tr>
		    </thead>
            <tbody>
                <tr v-for="(list, index) in transactions" :key="index">
                  <td style="padding-left: 4px; padding-right: 4px;">
                  	{{ index + 1 }}
                  </td>
                  <td>
      			 	<input type="text" class="form-control" v-model="list.comment">	
                  </td>
                  <td>
                  	<input type="date"  :value="formatDate(list.transaction_date)" class="form-control">
                  </td>
                  <td>
							<input type="number" style="width: 90px;" class="form-control" 
							       placeholder="Amount" v-model="list.amount" v-validate="'required'"
							       :name="'amount' + index" :class="{'is-invalid': errors.has('amount' + index)}"
								   step="0.01" pattern="^\d+(?:\.\d{1,2})?$" 	
							>
                  </td>
                  <td style="padding-right: 0px;">
                  	<button class="remove-btn" @click.prevent="removeItem(list)"><strong>X</strong></button>
                  </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group mt-4 text-center">
        	<button class="btn btn-info" :class="{ 'btn-loading': loading }" type="button" @click.prevent="submit">Save transactions</button>
        </div>
	</div>
</template>
<script>
    var moment = require('moment')
	export default {
		props: ['info', 'url'],
		data() {
			return {
				transactions: [],
				loading: false
			}
		},
		methods: {
			formatDate(date) {
				return moment(date).format('YYYY-MM-DD')
	    	},
			removeItem(item) {
				this.transactions.splice(this.transactions.indexOf(item), 1);
			},
			submit() {
				this.$validator.validate().then(result => {
                	if(result) {
                		let data = {
                			transactions: this.transactions
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
			this.transactions = this.info
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