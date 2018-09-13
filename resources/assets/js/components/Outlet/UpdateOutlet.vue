<template>
    <div>
		<div class="row">
			<div class="col-md-6">
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
		</div>
		<div class="row">
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Address</label>
                    <textarea name="address" class="form-control" rows="1" v-model="address"></textarea>
                </div>
            </div>
        </div>

        <div class="row mt-2">
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

        <div class="row mt-5" v-if="transactions.length">
            <legend class="ml-3">Opening Balances</legend>
            <div class="col-md-12">
                <table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
                    <thead>
                        <tr class="bg-gray-dark">
                            <th>Vendor Name</th>
                            <th>Closing Date</th>
                            <th>Amount</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(balance, index) in opening_balances" :key="index">
                            <td>
                                {{ balance.vendor.name }}
                            </td>
                            <td>
                                {{ beautifyDate(balance.transaction_date) }}
                            </td>
                            <td style="width: 140px;">
                                <input type="text" v-model="balance.amount" class="form-control">
                            </td>
                            <td class="text-center">
                                <button class="remove-btn" @click.prevent="removeItem(balance)"><strong>X</strong></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-7">
			<div class="col-md-12 text-center">
                <a href="#" onClick="history.go(-1); return false;" class="btn btn-gray ml-auto mr-2">
                    <i class="fe fe-corner-down-left mr-1"></i> Back
                </a>
                <button type="button" 
                    class="btn btn-primary" 
                    @click.prevent="submit"
                    :class="{ 'btn-loading': loading }"
                >
                   Save Outlet
                </button>         
            </div>
	    </div>
    </div>
</template>

<script>
export default {
    props: ['districts', 'outlet', 'url', 'vendors', 'transactions'],
	data() {
		return {
            district: this.outlet.thana.district_id,
            thana: this.outlet.thana_id,
            thanas: this.outlet.thana.district.thanas,
            name: this.outlet.name,
			proprietor: this.outlet.proprietor,
			phone: this.outlet.phone,
            address: this.outlet.address,
            thana: this.outlet.thana_id,
            opening_balances: this.transactions,
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
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						name: this.name,
                        proprietor: this.proprietor,
                        phone: this.phone,
                        address: this.address,
                        thana_id: this.thana,
                        opening_balances: this.opening_balances
					};
                    this.loading = true;
					axios.patch(this.url, data)
						.then(response => {
							this.loading = false
							window.location.href="/outlets"
						})
						.catch(error => {
							this.loading = false
							flash('Something went wrong. Plz try again later', 'danger');
						})
                }
            });
		},
        removeItem(item) {
            this.opening_balances.splice(this.opening_balances.indexOf(item), 1);
        }
	}
}
</script>

<style scoped>
    .remove-btn {
        cursor: pointer;
        padding: 0px;
        background: transparent;
        color: darkred;
        font-weight: bold;
        border: none;
    }
</style>