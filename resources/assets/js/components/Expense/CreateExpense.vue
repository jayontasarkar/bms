<template>
    <div class="full-width">
    	<button type="button" class="btn btn-primary btn-block" @click.prevent="show">
			+ Add Expense
		</button>
    	<b-modal ref="addExpenseModal"
             title="Add new expense item"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Expense Title</label>
    					<input type="text" 
    						   name="title"  
    						   class="form-control" 
    						   v-model="title"
    						   v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('title') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('title')">
    						{{ errors.first('title') }}
    					</div>
    				</div>
    			</div>
    		</div>
            <div class="row">
                <div class="col-md-12">
                    <label for="vendor">Select Vendor</label>
                    <select v-model="vendor_id" id="" class="form-control">
                        <option value="">Select</option>
                        <option v-for="vendor in vendors" :value="vendor.id">
                            {{ vendor.name }}
                        </option>
                    </select>
                </div>
            </div>
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Expense Date</label>
    					<input type="date" 
    					       name="expense date"  
    					       class="form-control" 
    					       v-model="expense_date"
    					       v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('expense date') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('expense date')">
    						{{ errors.first('expense date') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Expense Amount</label>
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
    		</div>
    		<div slot="modal-footer" class="w-100">
    			<button type="button" 
    				    class="float-right btn btn-primary" 
    				    @click.prevent="submit"
    				    :class="{ 'btn-loading': loading }"
    		    >
		           Save Expense
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
	props: [ 'url', 'vendors' ],
	data() {
		return {
			title: '',
            vendor_id: '',
			expense_date: moment(new Date()).format('YYYY-MM-DD'),
			amount: null,
			loading: false
		}
	},
	methods: {
		show() {
			this.$refs.addExpenseModal.show();
		},
		hide() {
			this.title = '',
			this.expense_date = moment(new Date()).format('YYYY-MM-DD'),
			this.amount = 0
			this.$refs.addExpenseModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						title: this.title,
                        vendor_id: this.vendor_id,
						expense_date: this.expense_date,
						amount: this.amount
					};
					this.loading = true;
					axios.post('/expenses', data)
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
		}
	}
}
</script>