<template>
    <div>
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
                    <label for="discount">Discount (if any)</label>
                    <input type="number" 
                           name="discount"  
                           class="form-control" 
                           v-model="discount"
                           v-validate="'nullable|decimal:2'"
                           :class="{ 'is-invalid': errors.has('discount') || errorList.discount }"
                    >
                    <div class="invalid-feedback" v-if="errors.has('discount') || errorList.discount">
                        {{ errors.first('discount') || errorList.discount[0] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="comment">Note/Comment</label>
                    <textarea id="comment" rows="2" class="form-control" v-model="comment"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
			<div class="col-md-12">
                <button type="button" 
                        class="float-right btn btn-primary" 
                        @click.prevent="submit"
                        :class="{ 'btn-loading': loading }"
                >
                   Update opening balance
                </button>         
            </div>
	    </div>
    </div>
</template>

<script>
var moment = require('moment')
export default {
    props: ['info', 'url', 'type'],
	data() {
		return {
            opening_balance: this.info.total_balance,
            memo: this.info.memo,
            comment: this.info.comment,
            discount: this.info.discount,
            sales_date: moment(this.type == 'purchase' ? this.info.purchase_date : this.info.sales_date).format('YYYY-MM-DD'),
            errorList: {},
			loading: false
		}
	},
    methods: {
        submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						total_balance: this.opening_balance,
                        memo: this.memo,
                        comment: this.comment,
                        type: 1
					};
                    if(this.type == 'sales') {
                        data.sales_date = this.sales_date
                    }else{
                        data.purchase_date = this.sales_date
                    }
                    this.loading = true;
					axios.patch(this.url, data)
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