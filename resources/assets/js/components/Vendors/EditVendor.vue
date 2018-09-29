<template>
    <span>
    	<button type="button" class="btn btn-info btn-xs" @click.prevent="show">
            <i class="fe fe-edit"></i>
        </button>
    	<b-modal ref="editVendorModal"
             title="Edit vendor"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Vendor/Company Name</label>
    					<input type="text" 
    						   name="vendor name"  
    						   class="form-control" 
    						   v-model="name"
    						   v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('vendor name') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('vendor name')">
    						{{ errors.first('vendor name') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Address</label>
                        <textarea name="address" class="form-control" rows="1" v-model="address"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Phone No.</label>
                        <input type="text" 
                               class="form-control" 
                               v-model="phone"
                               :class="{ 'is-invalid': errorList.phone }"
                        >
                        <div class="invalid-feedback" v-if="errorList.phone">
                            {{ errorList.phone[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Opening Balance</label>
                        <input type="text" 
                               name="opening balance"  
                               class="form-control" 
                               v-model="amount"
                               v-validate="'decimal:2'"
                               :class="{ 'is-invalid': errors.has('opening balance') || errorList.amount }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('opening balance') || errorList.opening_balance">
                            {{ errors.first('opening balance') || errorList.opening_balance[0] }}
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
		           Save Vendor
		        </button>
		        <button type="button" class="float-right btn btn-danger mr-2" @click.prevent="hide">
		           Close
		        </button>
		    </div>
    	</b-modal>
    </span>
</template>

<script>
export default {
    props: ['url', 'vendor', 'openingBalance'],
    data() {
		return {
            name: this.vendor.name,
			phone: this.vendor.phone,
            address: this.vendor.address,
            amount: 0,
            errorList: {},
			loading: false
		}
	},
	methods: {
        show() {
			this.$refs.editVendorModal.show();
		},
		hide() {
			this.$refs.editVendorModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						name: this.name,
                        phone: this.phone,
                        address: this.address,
                        amount: this.amount
					};
                    this.loading = true;
					axios.put(this.url, data)
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
	},
    mounted() {
        if(this.openingBalance != null) {
            this.amount = this.openingBalance.amount;
        }
    }
}
</script>