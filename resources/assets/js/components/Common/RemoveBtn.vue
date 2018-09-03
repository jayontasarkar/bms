<template>
	<span>
		<button type="button" class="btn btn-icon btn-primary btn-danger btn-xs" @click.prevent="submit">
			<i class="fe fe-trash"></i>
		</button>
	</span>
</template>

<script>
import swal from 'sweetalert2'

export default {
	props: [ 'url' ],
	methods: {
		submit() {
			swal({
			  title: 'Are you sure to remove this item?',
			  type: 'error',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes',
			  reverseButtons: true
			}).then((result) => {
			  if (result.value) {
			  	axios.delete(this.url)
					.then(response => {
						location.reload();
					})
			  }
			})
		}
	}
}
</script>