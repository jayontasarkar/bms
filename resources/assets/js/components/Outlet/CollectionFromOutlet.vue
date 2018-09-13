<template>
    <div class="full-width">
    	<button type="button" class="btn btn-success btn-block" @click.prevent="show">
			+ Collection from Outlet
		</button>
    	<b-modal ref="addCollectionFromOutletModal"
             title="Add collection from outlet"
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
                    <label for="memo">Collection Date</label>
                    <input type="date" name="collection date" v-model="transaction_date" class="form-control"
                           :class="{'is-invalid': errors.has('collection date') }"  v-validate="'required'"
                    >
                    <div class="invalid-feedback" v-if="errors.has('collection date') ">
                        {{ errors.first('collection date') }}
                    </div>
                </div>
        		<div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Collection Amount</label>
                        <input type="number" 
                               name="collection amount"  
                               class="form-control" 
                               v-model="amount"
                               min="1"
                               v-validate="'decimal:2'"
                               :class="{ 'is-invalid': errors.has('collection amount') }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('collection amount') ">
                            {{ errors.first('collection amount') }}
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
		           Save Collection
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
			this.$refs.addCollectionFromOutletModal.show();
		},
		hide() {
			this.$refs.addCollectionFromOutletModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						amount: this.amount,
                        vendor_id: this.vendor_id,
                        transaction_date: this.transaction_date,
                        type: 0
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