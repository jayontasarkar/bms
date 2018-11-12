<template>
    <div class="full-width">
    	<button type="button" class="btn btn-primary btn-block" @click.prevent="show">
			+ Add New Product
		</button>
    	<b-modal ref="addProductModal"
             title="Add new product listing"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Product Code</label>
                        <input type="text"
                               name="code"
                               class="form-control"
                               v-model="code"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errorList.code }"
                        >
                        <div class="invalid-feedback" v-if="errorList.code">
                            {{ errorList.code[0] }}
                        </div>
                    </div>
                </div>
            </div>
    		<div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Product Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               v-model="title"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('title') || errorList.title }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('title') || errorList.title ">
                            {{ errors.first('title') || errorList.title[0] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="quantity">Product Stock</label>
    					<input type="number" class="form-control"
                               placeholder="Quantity" v-model="qty" name="quantity"
                               min="1"
                               :class="{'is-invalid': errors.has('quantity')}" v-validate="'required'"
                               step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        >
    					<div class="invalid-feedback" v-if="errors.has('quantity') || errorList.title ">
    						{{ errors.first('quantity') }}
    					</div>
    				</div>
    			</div>
    		</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="quantity">Unit Price (Per Peice)</label>
                        <input type="number" class="form-control"
                               placeholder="Stock Price" v-model="unit_price" name="unit price"
                               min="0"
                               :class="{'is-invalid': errors.has('unit price')}" v-validate="'required'"
                               step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
                        >
                        <div class="invalid-feedback" v-if="errors.has('unit price') || errorList.unit_price ">
                            {{ errors.first('unit price') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Vendor Name</label>
                        <select class="form-control" name="vendor name"
                                v-model="vendor_id" v-validate="'required'"
                                :class="{ 'is-invalid': errors.has('vendor name') }"
                        >
                            <option value="">Select Vendor</option>
                            <option v-for="(vendor, index) in vendors" :key="index" :value="vendor.id">
                                {{ vendor.name }}
                            </option>
                        </select>
                        <div class="invalid-feedback" v-if="errors.has('vendor name')">
                            {{ errors.first('vendor name') }}
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
		           Save Product
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
	props: [ 'vendors' ],
	data() {
		return {
			title: '',
            code: '',
            vendor_id: '',
            qty: null,
            unit_price: 0,
            errorList: {},
			loading: false
		}
	},
	methods: {
		show() {
			this.$refs.addProductModal.show();
		},
		hide() {
			this.title = '';
            this.code = '';
            this.qty = null;
            this.vendor_id = '';
            this.unit_price = 0;
			this.$refs.addProductModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						title: this.title,
                        code: this.code,
                        vendor_id: this.vendor_id,
                        unit_price: this.unit_price,
                        stock: this.qty
					};
					this.loading = true;
					axios.post('/products', data)
						.then(response => {
							this.loading = false
							location.reload();
						})
						.catch(error => {
							this.loading = false;
                            this.errorList = error.response.data.errors;
							flash('Something went wrong.', 'danger');
						})
                }
            });
		}
	}
}
</script>