<template>
    <span class="ml-auto pull-right">
    	<button type="button" class="btn btn-primary pull-right" @click.prevent="show">
			+ Add Vendor
		</button>
    	<b-modal ref="addVendorModal"
             title="Add new vendor"
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
                               v-model="opening_balance"
                               v-validate="'decimal:2'"
                               :class="{ 'is-invalid': errors.has('opening balance') || errorList.opening_balance }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('opening balance') || errorList.opening_balance">
                            {{ errors.first('opening balance') || errorList.opening_balance[0] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="hasOpeningBalance">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="memo">Memo/Opening Balance No.</label>
                        <input type="text" 
                               class="form-control" 
                               v-model="memo"
                               :class="{ 'is-invalid': errorList.memo }"
                        >
                        <div class="invalid-feedback" v-if="errorList.memo">
                            {{ errorList.memo[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Balance Till</label>
                        <input type="date" 
                               name="balance till"  
                               class="form-control" 
                               v-model="purchase_date"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('balance till') }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('balance till')">
                            {{ errors.first('balance till') }}
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
var moment = require('moment');

export default {
    data() {
		return {
            name: '',
			phone: '',
            address: '',
            opening_balance: 0,
            memo: null,
            purchase_date: moment(new Date()).format('YYYY-MM-DD'),
            errorList: {},
            hasOpeningBalance: false,
			loading: false
		}
	},
    watch: {
        opening_balance: function(newVal, oldVal) {
            if(newVal > 0) {
                this.hasOpeningBalance = true
            }else{
                this.memo = null
                this.hasOpeningBalance = false
            }
        }
    },
	methods: {
        show() {
			this.$refs.addVendorModal.show();
		},
		hide() {
			this.clearFields()
			this.$refs.addVendorModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						name: this.name,
                        phone: this.phone,
                        address: this.address,
                        total_balance: this.opening_balance,
                        memo: this.memo,
                        type: 1,
                        purchase_date: this.purchase_date
					};
                    if(this.hasOpeningBalance) { data.hasOpeningBalance = true }
                    this.loading = true;
					axios.post('/vendors', data)
						.then(response => {
							this.loading = false
							location.reload();
						})
						.catch(error => {
                            this.errorList = error.response.data.errors
							this.loading = false
							flash('Something wrong. Check your data','danger');
						})
                }
            });
		},
        clearFields() {
            this.name = ''
            this.phone = ''
            this.address = ''
            this.opening_balance = 0
            errorList: {}
        }
	}
}
</script>