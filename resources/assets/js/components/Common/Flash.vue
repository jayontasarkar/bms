<template>
    <div class="alert alert-flash pt-3 pb-3 text-center"
         :class="'alert-'+level"
         role="alert"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },
        created() {
            if (this.message) {
                this.flash();
            }
            window.events.$on(
                'flash', data => this.flash(data)
            );
        },
        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }
                this.show = true;
                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 6000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 6px;
        z-index: 9999;
        bottom: 0px;
        padding-left: 75px;
        padding-right: 75px;
        font-size: 1.2em;
    }
</style>