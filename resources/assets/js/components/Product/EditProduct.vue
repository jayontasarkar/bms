<template>
    <span>
    	<button type="button" class="btn btn-info btn-xs" @click.prevent="show">
            <i class="fe fe-edit"></i>
        </button>
    	<b-modal ref="editProductModal"
             title="Edit product listing"
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
		           Update Product
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
	props: [ 'vendors', 'url', 'product' ],
	data() {
		return {
			title: this.product.title,
            code: this.product.code,
            vendor_id: this.product.vendor_id,
            errorList: {},
			loading: false
		}
	},
	methods: {
		show() {
			this.$refs.editProductModal.show();
		},
		hide() {
			this.title = '';
			this.$refs.editProductModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						title: this.title,
                        code: this.code,
                        vendor_id: this.vendor_id
					};
					this.loading = true;
					axios.patch(this.url, data)
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