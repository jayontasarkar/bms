<template>
    <span>
    	<b-btn @click.prevent="show" variant="primary">Show Modal</b-btn>
    	<b-modal ref="confirmRemoveModal"
             title="Add new expense item"
             :header-bg-variant="primary"
             :header-text-variant="light"
        >
    		<div class="row"></div>
    		<div slot="modal-footer" class="w-100">
		        <p class="float-left">Modal Footer Content</p>
		        <b-btn size="sm" class="float-right" variant="primary" >
		           Close
		        </b-btn>
		    </div>
    	</b-modal>
    </span>
</template>

<script>
export default {
	props: [ 'url' ],
	methods: {
		show() {
			this.$refs.confirmRemoveModal.show();
		},
		submit() {
			axios.delete(this.url)
				.then(response => {
					location.reload();
				})
				.catch(error => {
					flash('Something went wrong. Plz try again later');
				})
		}
	},
	mounted() {
		flash('Successful');
	}
}
</script>