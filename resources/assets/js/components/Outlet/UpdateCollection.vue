<template>
<div>  
  <div class="row">
    <div class="col-md-6">
      <label for="">Select district</label>  
      <select class="form-control" v-model="district">
        <option value="">Select</option>
        <option v-for="(district, index) in districts" :value="district.id" :key="index">
          {{ district.name }}
        </option>
      </select>
    </div>
    <div class="col-md-6">
      <label for="">Select thana</label>  
      <select class="form-control" v-model="thana">
        <option value="">Select</option>
        <option v-for="(thana, index) in thanas" :value="thana.id" :key="index">
          {{ thana.name }} ({{ thana.outlets.length }})
        </option>
      </select>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-md-6">
      <label for="">Select Outlet</label>  
      <select class="form-control" v-model="outlet" v-validate="'required'"
        :class="{'is-invalid': errors.has('outlet') || errorList.outlet_id}" name="outlet"
        >
        <option value="">Select</option>
        <option v-for="(outlet, index) in outlets" :value="outlet.id" :key="index">
          {{ outlet.name }}, {{ outlet.address }}
        </option>
      </select>
      <div class="invalid-feedback" v-if="errors.has('outlet') || errorList.outlet_id">
        {{ errors.first('outlet') || errorList.outlet_id[0] }}
      </div>
    </div>
    <div class="col-md-6">
      <label for="">Select vendor</label>  
      <select class="form-control" v-model="vendor" v-validate="'required'"
        :class="{'is-invalid': errors.has('vendor') }" name="vendor"
        >
        <option value="">Select</option>
        <option v-for="(vendor, index) in vendors" :value="vendor.id" :key="index">
          {{ vendor.name }}
        </option>
      </select>
      <div class="invalid-feedback" v-if="errors.has('vendor') ">
        {{ errors.first('vendor') }}
      </div>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-6">
      <label for="collection_date">Collection Date</label>
      <input type="date" name="collection date" v-model="transaction_date" class="form-control"
      :class="{'is-invalid': errors.has('collection date') }"  v-validate="'required'"
      >
      <div class="invalid-feedback" v-if="errors.has('collection date') ">
        {{ errors.first('collection date') }}
      </div>
    </div> 
    <div class="col-md-6">
        <label for="amount">Collection Amount</label>
        <input type="number" class="form-control"
            placeholder="Amount" v-model="amount" v-validate="'required'"
            :class="{ 'is-invalid': errors.has('amount') }"
            step="0.01" pattern="^\d+(?:\.\d{1,2})?$"
        >
    </div>
  </div>
  
  <div class="row mt-5 pr-3 text-right">
    <button type="button"
    class="float-right btn btn-primary ml-auto"
    @click.prevent="submit"
    :class="{ 'btn-loading': loading }"
    :disabled="amount <= 0"
    >
    Edit Collection
    </button>
  </div>
</div>  
</template>
<script>
  var moment = require('moment');
  export default {
    props: ['districts', 'url', 'vendors', 'transaction'],
    data() {
      return {
        district: this.transaction.transactionable.thana.district_id,
        thana: this.transaction.transactionable.thana_id,
        outlet: this.transaction.transactionable_id,
        vendor: this.transaction.vendor_id,
        thanas: [],
        outlets: [],
        amount: this.transaction.amount,
        transaction_date: moment(this.transaction.transaction_date).format('YYYY-MM-DD'),
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
      },
      thana: function(newValue, oldValue) {
        if( newValue != '' ) {
          let maped = this.thanas.filter(thana => thana.id == newValue)
          this.outlets = maped[0].outlets;
        }
      }
    },
    methods: {
      show() {
        this.$refs.collectionFromOutletModal.show();
      },
      hide() {
        this.$refs.collectionFromOutletModal.hide();
      },
      submit() {
        this.$validator.validate().then(result => {
          if(result) {
            let data = {
              transactionable_id: this.outlet,
              amount: this.amount,
              vendor_id: this.vendor,
              transaction_date: this.transaction_date
            };
            this.loading = true;
            axios.patch(this.url, data)
              .then(response => {
                location.reload();
                this.loading = false;
              }).catch(error => {
                this.loading = false;
                this.errorList = error.response.data.errors
              })
          }
        });
      }
    },
    mounted() {
      let district = this.districts.filter(district => district.id == this.transaction.transactionable.thana.district_id)
      this.thanas = district[0].thanas;
      let thanas = district[0].thanas.filter(thana => thana.id == this.transaction.transactionable.thana_id)
      this.outlets = thanas[0].outlets;
    }
  }
</script>