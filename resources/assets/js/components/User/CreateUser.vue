<template>
    <span class="ml-auto pull-right">
    	<button type="button" class="btn btn-primary ml-auto" @click.prevent="show">
			+ Add User
		</button>
    	<b-modal ref="addUserModal"
             title="Add new management user"
             :header-bg-variant="'primary'"
             :header-text-variant="'light'"
             centered
             no-close-on-esc no-close-on-backdrop
        >
    		<div class="row">
    			<div class="col-md-12">
    				<div class="form-group">
    					<label for="title">Full Name</label>
    					<input type="text" 
    						   name="name"  
    						   class="form-control" 
    						   v-model="name"
    						   v-validate="'required'"
    						   :class="{ 'is-invalid': errors.has('name') }"
    					>
    					<div class="invalid-feedback" v-if="errors.has('name')">
    						{{ errors.first('name') }}
    					</div>
    				</div>
    			</div>
    		</div>
    		<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Email Address</label>
                        <input type="email" 
                               name="email"  
                               class="form-control" 
                               v-model="email"
                               v-validate="'email'"
                               :class="{ 'is-invalid': errors.has('email') || errorList.email }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('email') || errorList.email">
                            {{ errors.first('email') || errorList.email[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Phone/Mobile No.</label>
                        <input type="text" 
                               name="phone"  
                               class="form-control" 
                               v-model="phone"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('phone') || errorList.phone }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('phone') || errorList.phone">
                            {{ errors.first('phone') || errorList.phone[0] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">Username</label>
                        <input type="text" 
                               name="username"  
                               class="form-control" 
                               v-model="username"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('username') || errorList.username }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('username') || errorList.username">
                            {{ errors.first('username') || errorList.username[0] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Password</label>
                        <input type="password" 
                               name="password"  
                               class="form-control" 
                               v-model="password"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('password') || errorList.password }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('password') || errorList.password">
                            {{ errors.first('password') || errorList.password[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Confirm Password</label>
                        <input type="password" 
                               name="confirm password"  
                               class="form-control" 
                               v-model="password_confirmation"
                               v-validate="'required'"
                               :class="{ 'is-invalid': errors.has('confirm password') || errorList.password_confirmation }"
                        >
                        <div class="invalid-feedback" v-if="errors.has('confirm password') || errorList.password_confirmation">
                            {{ errors.first('confirm password') || errorList.password_confirmation[0] }}
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
		           Save User
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
	props: [ 'url' ],
	data() {
		return {
			name: '',
            email: '',
            phone: '',
			username: '',
            password: '',
			password_confirmation: '',
			loading: false,
            errorList: {}
		}
	},
	methods: {
		show() {
			this.$refs.addUserModal.show();
		},
		hide() {
            this.name = ''
            this.email = ''
            this.phone = ''
            this.username = ''
            this.password = ''
			this.password_confirmation = ''
			this.$refs.addUserModal.hide();
		},
		submit() {
			this.$validator.validate().then(result => {
                if(result) {
                	let data = {
						name: this.name,
                        email: this.email,
                        phone: this.phone,
                        username: this.username,
                        password: this.password,
                        password_confirmation: this.password_confirmation
					};
					this.loading = true;
					axios.post('/users', data)
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