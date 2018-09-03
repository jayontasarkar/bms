<template>
	<span>
		<b-btn @click.prevent="show" :size="size" variant="primary">Payment</b-btn>
    	<b-modal ref="makePaymentModal"
             :title="title"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
        >
        	<div class="row mb-4">
        		<div class="col-md-12 text-center">
        			<strong>
        				<span class="badge badge-default">{{ purchase.vendor.name }}</span>
        			</strong>
        		</div>
        	</div>
    		<div class="row mb-4">
    			<div class="col-md-6">
    				<strong>Memo/PO: {{ purchase.memo }}</strong>
    			</div>
    			<div class="col-md-6 text-center">
    				<strong>Date: {{ formatPurchaseDate }}</strong>
    			</div>
    		</div>
    		<div class="row">
    			<h5 class="ml-3 mr-3 custom--header">Accounts</h5>
    			<div class="col-md-3 text-center">
    				<strong>Total Amount:</strong> <br>
    				{{ beautifyAmount(purchase.total_balance) }}/=
    			</div>
    			<div class="col-md-3 text-center">
    				<strong>Total Paid:</strong> <br>
    				{{ beautifyAmount(purchase.total_paid) }}/=
    			</div>
    			<div class="col-md-3 text-center">
    				<strong>Total Discount:</strong> <br>
    				{{ beautifyAmount(purchase.total_discount ? purchase.total_discount : '00.00') }}/=
    			</div>
    			<div class="col-md-3 text-center">
    				<strong>Amount To Pay:</strong> <br>
    				{{ beautifyAmount(amountToPay) }}/=
    			</div>
    		</div>
    		<hr>
    		<div class="row mt-4">
    			<div class="col-md-6">
    				<label for="amount">Purchase Payment Amount</label>
    				<input type="number" name="payment" v-model="amount" 
    				       class="form-control" step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
    				       min="1"
    				       :max="(purchase.total_balance - purchase.total_paid - purchase.total_discount)" 
    				       v-validate="'required|min:1|decimal:2'"
                           :class="{ 'is-invalid': errors.has('payment') || errorList.amount }"
    				>
    				<div class="invalid-feedback" v-if="errors.has('payment') || errorList.amount">
                        {{ errors.first('payment') || errorList.amount[0] }}
                    </div>
    			</div>
    			<div class="col-md-6">
    				<label for="transaction_date">Purchase Payment Date</label>
    				<input type="date" name="transaction_date" v-model="transaction_date" class="form-control">
    			</div>
    		</div>
    		<div class="row mt-3">
    			<div class="col-md-12">
    				<label for="comment">Comment/Additional Information</label>
    				<input type="text" class="form-control" v-model="comment">
    			</div>
    		</div>
    		<div slot="modal-footer" class="w-100">
    			<b-btn class="float-right" variant="primary" @click.prevent="submit()" :class="{ 'btn-loading': loading }">
		           Payment to Purchase Order
		        </b-btn>
		        <b-btn class="float-right mr-3" variant="danger" @click.prevent="hide">
		           Close
		        </b-btn>
		    </div>
    	</b-modal>
	</span>
</template>

<script>
	var moment = require('moment');
	export default {
		props: {
			title: {
				default: 'Have you transfered amount for this purchase order?'
			},
			purchase: {
				default: null
			},
			size: {
				default: 'sm'
			},
			url: {
				default: null
			}
		},
		data() {
			return {
				loading: false,
				amount: null,
				transaction_date: moment(new Date()).format('YYYY-MM-DD'),
				comment: '',
				errorList: {}
			}
		},
		computed: {
			amountToPay() {
				return (this.purchase.total_balance - this.purchase.total_discount - this.purchase.total_paid);
			},
			formatPurchaseDate() {
				return moment(this.purchase.purchase_date).format('LL');
			}
		},
		methods: {
			show() {
				this.clearInputs()
				this.$refs.makePaymentModal.show();
			},
			hide() {
				this.clearInputs()
				this.$refs.makePaymentModal.hide();
			},
			submit() {
				this.$validator.validate().then(result => {
	                if(result) {
	                	let data = {
	                		comment: this.comment,
	                		amount: this.amount,
	                		transaction_date: this.transaction_date
	                	};
	                	this.loading = true
						axios.post(this.url, data)
							.then(response => {
								this.loading = false
								location.reload()
							}) 
							.catch(error => {
								this.loading = false
								flash('Something went wrong while saving data', 'danger');
							})
					}
				})
			},
			clearInputs() {
				this.comment = ''
        		this.amount = null
        		this.transaction_date = moment(new Date()).format('YYYY-MM-DD')
        		this.$validator.reset()
        		this.errorList = {}
			}
		}
	}
</script>

<style scoped>
	.badge { font-size: 1.1em; }
	.custom--header { 
		border-bottom: 1px solid #eeeeee;
	    width: 100%;
	    padding-bottom: 4px;
	}
</style>