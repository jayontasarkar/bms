<template>
	<span>
		<button type="button" class="btn btn-icon btn-danger" :class="className" @click.prevent="submit">
			<i class="fe fe-trash"></i> {{ btnText }}
		</button>
	</span>
</template>

<script>
import swal from 'sweetalert2'

export default {
	props: {
		url: { default: '' },
		className: {
			default: 'btn-xs'
		},
		title: {
			default: 'Are you sure to remove this item?'
		},
		btnText: {
			default: ''
		},
		redirectPath: {
			default: false
		}
	},
	methods: {
		submit() {
			swal({
			  title: this.title,
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
						if(this.redirectPath) {
							window.location.href=this.redirectPath
						}else{
							location.reload();
						}
					})
			  }
			})
		}
	}
}
</script>