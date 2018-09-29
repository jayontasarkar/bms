<template>
	<span>
		<button class="btn btn-xs btn-info" @click.prevent="show">
			<i class="fa fa-edit"></i> Edit
		</button>
    	<b-modal ref="vendorPaymentEditModal"
             title="Edit Vendor Payments "
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered size="sm"
             no-close-on-esc no-close-on-backdrop
        >
        	<div class="row">
        		<div class="col-md-12">
        			<label for="comment">Note/Comment</label>
        			<textarea name="comment" id="comment" class="form-control" rows="3" v-model="comment"></textarea>
        		</div>
        	</div>
        	<div class="row mt-2">
        		<div class="col-md-12">
        			<label for="amount">Payment Amount</label>
        			<input type="number" id="amount" v-model="amount" class="form-control" pattern="^\d+(?:\.\d{1,2})?$">
        		</div>
        	</div>
        	<div class="row mt-2">
        		<div class="col-md-12">
        			<label for="transaction_date">Payment Date</label>
        			<input type="date" id="transaction_date" v-model="transaction_date" class="form-control">
        		</div>
        	</div>
        	<div slot="modal-footer" class="w-100">
        		<button type="button" class="float-right btn btn-info" @click.prevent="submit" :class="{ 'btn-loading': loading }">
		           Vendor Payment
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
		props: ['url', 'transaction'],
		data() {
			return {
				loading: false,
				comment: this.transaction.comment,
				amount: this.transaction.amount,
				transaction_date: moment(this.transaction.transaction_date).format('YYYY-MM-DD')
			}
		},
		methods: {
			show() {
				this.$refs.vendorPaymentEditModal.show();
			},
			hide() {
				this.$refs.vendorPaymentEditModal.hide();
			},
			submit() {
				this.loading = true;
				axios.patch(this.url, {
					comment: this.comment,
					amount: this.amount,
					transaction_date: this.transaction_date
				}).then(response => {
					location.reload();
					this.loading = false;
				}).catch(error => {
					this.loading = false;
					flash('Something went wrong. try again.');
				})
			}
		}
	}
</script>