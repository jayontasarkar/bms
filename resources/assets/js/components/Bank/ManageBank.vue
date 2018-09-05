<template>
	<div class="full-width">
    	<button type="button" class="btn btn-info btn-block" @click.prevent="show">
			+ Manage Bank
		</button>
    	<b-modal ref="manageBankModal"
             title="Manage Bank (Deposit/Withdraw)"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
    			<div class="col-md-6">
    				<div class="form-group">
    					<label for="bank">Select Your Bank</label>
    					<select name="bank" id="bank" v-model="bank" class="form-control"
    							v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('bank') }"
    					>
    						<option value="">Select</option>
    						<option v-for="(bank, index) in banks" :key="index" :value="bank.id">
    							{{ bank.name }} ({{ bank.account ? bank.account : 'No Account' }})
    						</option>
    					</select>
    					<div class="invalid-feedback" v-if="errors.has('bank')">
    						{{ errors.first('bank') }}
    					</div>
    				</div>
    			</div>
    			<div class="col-md-6">
    				<div class="form-group">
    					<label for="type">Transaction Type</label>
    					<select name="transaction type" id="type" v-model="type" class="form-control"
    							v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('transaction type') }"
    					>
    						<option value="">Select</option>
    						<option value="1">Deposit</option>
    						<option value="0">Withdraw</option>	
    					</select>
    					<div class="invalid-feedback" v-if="errors.has('expense date')">
    						{{ errors.first('transaction type') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-6">
    				<div class="form-group">
    					<label for="title">Amount</label>
    					<input type="number" 
    					       name="amount"  
    					       class="form-control" 
    					       v-model="amount"
    					       step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
    					       v-validate="'required|decimal:2|min:1'"
    						   :class="{ 'is-invalid': errors.has('amount') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('amount')">
    						{{ errors.first('amount') }}
    					</div>
    				</div>
    			</div>
    			<div class="col-md-6">
    				<div class="form-group">
    					<label for="transaction_date">Transaction Date</label>
    					<input type="date" 
    					       name="transaction date"  
    					       class="form-control" 
    					       v-model="transaction_date"
    					       v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('transaction date') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('transaction date')">
    						{{ errors.first('transaction date') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="comment">Note/Comment</label>
    					<textarea id="comment" rows="3" v-model="comment" class="form-control"></textarea>
    				</div>
    			</div>
    		</div>
    		<div slot="modal-footer" class="w-100">
    			<button type="button" 
    				    class="float-right btn btn-primary" 
    				    @click.prevent="submit"
    				    :class="{ 'btn-loading': loading }"
    		    >
		           Save Banking
		        </button>
		        <button type="button" class="float-right btn btn-danger mr-2" @click.prevent="hide">
		           Close
		        </button>
		    </div>
    	</b-modal>
    </div>
</template>

<script>
	var moment = require('moment');
	export default {
		props: ['banks', 'url'],
		data() {
			return {
				bank: '',
				amount: null,
				transaction_date: moment(new Date()).format('YYYY-MM-DD'),
				type: '',
				comment: '',
				loading: false
			}
		},
		methods: {
			submit() {
				this.$validator.validate().then(result => {
	                if(result) {
	                	let data = {
							bank: this.bank,
							amount: this.amount,
							transaction_date: this.transaction_date,
							type: this.type,
							comment: this.comment
						};
						this.loading = true;
						axios.post(this.url, data)
							.then(response => {
								this.loading = false
								location.reload();
							})
							.catch(error => {
								this.loading = false
								flash('Something went wrong. Plz try again later');
							})
	                }
	            });
			},
			show() {
				this.$refs.manageBankModal.show();
			},
			hide() {
				this.bank = ''
				this.amount = null
				this.transaction_date = moment(new Date()).format('YYYY-MM-DD')
				this.type = ''
				this.comment = ''
				this.$refs.manageBankModal.hide();
			}
		}	
	}
</script>