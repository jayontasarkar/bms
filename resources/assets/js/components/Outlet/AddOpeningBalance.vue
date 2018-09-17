<template>
    <div class="full-width">
    	<button type="button" class="btn btn-primary btn-block" @click.prevent="show">
			+ Add Opening Balance
		</button>
    	<b-modal ref="addOpeningBalanceModal"
             title="Add opening balance to outlet"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
            <div class="row">
                <div class="col-md-12">
                    <label for="vendor">Select vendor</label>
                    <select name="vendor" id="vendor" v-model="vendor_id" class="form-control"
                            v-validate="'required'"
                            :class="{ 'is-invalid': errors.has('vendor') }"
                    >
                        <option v-for="(vendor, index) in vendorList" :key="index" :value="vendor.id">
                            {{ vendor.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.has('vendor')">
                        {{ errors.first('vendor') }}
                    </div>
                </div>
            </div>
        	<div class="row mt-3">
                <div class="col-md-6">
                    <label for="memo">Closing Date</label>
                    <input type="date" name="closing date" v-model="transaction_date" class="form-control"
                           :class="{'is-invalid': errors.has('closing date') }"  v-validate="'required'"
                    >
                    <div class="invalid-feedback" v-if="errors.has('closing date') ">
                        {{ errors.first('closing date') }}
                    </div>
                </div>
        		<div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Opening Balance</label>
                        <input type="number" 
                               name="opening balance"  
                               class="form-control" 
                               v-model="amount"
                               min="1"
                               v-validate="'decimal:2'"
                               :class="{ 'is-invalid': errors.has('opening balance') }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('opening balance') ">
                            {{ errors.first('opening balance') }}
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
		           Save opening balance
		        </button>
		        <button type="button" class="float-right btn btn-danger mr-2" @click.prevent="hide">
		           Close
		        </button>
		    </div>
    	</b-modal>
    </div>
</template>

<script>
var moment = require('moment')
export default {
    props: ['url', 'vendors'],
	data() {
		return {
            amount: null,
            vendorList: this.vendors,
            vendor_id: '',
            transaction_date: moment(new Date()).format('YYYY-MM-DD'),
            errorList: {},
			loading: false
		}
	},
    methods: {
        show() {
			this.$refs.addOpeningBalanceModal.show();
		},
		hide() {
			this.$refs.addOpeningBalanceModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						amount: this.amount,
                        vendor_id: this.vendor_id,
                        transaction_date: this.transaction_date,
                        type: 1
					};
                    this.loading = true;
					axios.post(this.url, data)
						.then(response => {
							this.loading = false
							location.reload();
						})
						.catch(error => {
                            this.errorList = error.response.data.errors
							this.loading = false
							flash('Something went wrong. Plz try again later');
						})
                }
            });
		}
	}
}
</script>