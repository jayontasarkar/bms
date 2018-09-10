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
        		<div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Opening Balance</label>
                        <input type="number" 
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
        	</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Balance Till</label>
                        <input type="date" 
                               name="balance till"  
                               class="form-control" 
                               v-model="sales_date"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('balance till') }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('balance till')">
                            {{ errors.first('balance till') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="comment">Note/Comment</label>
                        <textarea id="comment" rows="2" class="form-control" v-model="comment"></textarea>
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
    props: ['url'],
	data() {
		return {
            opening_balance: null,
            memo: null,
            comment: null,
            sales_date: moment(new Date()).format('YYYY-MM-DD'),
            errorList: {},
			loading: false
		}
	},
    methods: {
        show() {
			this.$refs.addOpeningBalanceModal.show();
		},
		hide() {
			this.clearFields()
			this.$refs.addOpeningBalanceModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						total_balance: this.opening_balance,
                        memo: this.memo,
                        comment: this.comment,
                        sales_date: this.sales_date,
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
		},
        clearFields() {
            this.opening_balance = 0
            errorList: {}
        }
	}
}
</script>