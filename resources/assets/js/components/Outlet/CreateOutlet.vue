<template>
    <div class="full-width">
    	<button type="button" class="btn btn-primary btn-block" @click.prevent="show">
			+ Add Outlet
		</button>
    	<b-modal ref="addOutletModal"
             title="Add new outlet"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Outlet/Business Name</label>
    					<input type="text" 
    						   name="outlet name"  
    						   class="form-control" 
    						   v-model="name"
    						   v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('outlet name') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('outlet name')">
    						{{ errors.first('outlet name') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Proprietor/Owner Name</label>
                        <input type="text" 
                               name="proprietor name"  
                               class="form-control" 
                               v-model="proprietor"
                        >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Phone/Mobile No.</label>
                        <input type="text" 
                               name="phone/mobile"  
                               class="form-control" 
                               v-model="phone"
                               :class="{ 'is-invalid': errorList.phone }"
                        >
                        <div class="invalid-feedback" v-if="errorList.phone">
                            {{ errorList.phone[0] }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Address</label>
                        <textarea name="address" class="form-control" rows="1" v-model="address"></textarea>
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

            <div class="row">
                <div class="col-md-6">
                    <label for="district">District</label>
                    <select name="district" v-model="district" class="form-control"
                            v-validate="'required'" :class="{'is-invalid': errors.has('district')}"
                    >
                        <option value="">Select district</option>
                        <option v-for="(district, index) in districts" :key="index" :value="district.id">
                            {{ district.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.has('district')">
                        {{ errors.first('district') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="thana">Thana/Upozilla</label>
                    <select name="thana" v-model="thana" class="form-control" 
                            v-validate="'required'" :class="{'is-invalid': errors.has('thana')}"
                    >
                        <option value="">Select thana</option>
                        <option v-for="(thana, index) in thanas" :key="index" :value="thana.id">
                            {{ thana.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.has('thana')">
                        {{ errors.first('thana') }}
                    </div>
                </div>
            </div>

            <div slot="modal-footer" class="w-100">
    			<button type="button" 
    				    class="float-right btn btn-primary" 
    				    @click.prevent="submit"
    				    :class="{ 'btn-loading': loading }"
    		    >
		           Save Outlet
		        </button>
		        <button type="button" class="float-right btn btn-danger mr-2" @click.prevent="hide">
		           Close
		        </button>
		    </div>
    	</b-modal>
    </div>
</template>

<script>
export default {
    props: ['districts'],
	data() {
		return {
            district: '',
            thana: '',
            thanas: [],
            name: '',
			proprietor: '',
			phone: '',
            address: '',
            thana: null,
            opening_balance: 0,
            errorList: {},
			loading: false
		}
	},
    watch: {
        district: function(newValue, oldValue) {
            if( newValue != '' ) {
                let maped = this.districts.filter(district => district.id == newValue)
                this.thanas = maped[0].thanas;
            }
        }
    },
	methods: {
        show() {
			this.$refs.addOutletModal.show();
		},
		hide() {
			this.clearFields()
			this.$refs.addOutletModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						name: this.name,
                        proprietor: this.proprietor,
                        phone: this.phone,
                        address: this.address,
                        thana_id: this.thana,
                        opening_balance: this.opening_balance
					};
                    this.loading = true;
					axios.post('/outlets', data)
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
        clearFields() {
            this.name = ''
            this.proprietor = ''
            this.phone = ''
            this.address = ''
            this.thana = null
            this.opening_balance = 0
            errorList: {}
        }
	}
}
</script>