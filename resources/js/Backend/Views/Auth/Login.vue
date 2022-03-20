<template>
    <div>
        <!-- Page Content -->
        <div class="bg-white">
            <b-row class="justify-content-center mx-0">
                <div class="hero-static col-lg-6 col-xl-4">
                    <div class="content content-full overflow-hidden">
                        <!-- Header -->
                        <div class="py-30 text-center">
                            <a class="link-effect font-w700" :href="route('dashboard')">
                                <i class="si si-fire"></i>
                                <span class="font-size-xl text-primary-dark">code</span><span
                                    class="font-size-xl">base</span>
                            </a>
                            <h1 class="h4 font-w700 mt-30 mb-10">Welcome to Your Dashboard</h1>
                            <h2 class="h5 font-w400 text-muted mb-0">It’s a great day today!</h2>
                        </div>
                        <!-- END Header -->


                        <!-- Sign In Form -->
                        <form @submit.prevent="submit">
                            <div class="block block-transparent">
                                <div class="block-content w-75">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-username">Email / Username</label>
                                            <input type="text" v-bind:class="{'form-control':true, 'is-invalid' : errors.email}" id="login-username" v-model="form.email" autofocus>
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-password">Password</label>
                                            <input type="password" v-bind:class="{'form-control':true, 'is-invalid' : errors.password}" id="login-password" v-model="form.password">
                                            <div v-if="errors.password" class="invalid-feedback">
                                                {{ errors.password[0] }}
                                            </div>
                                        </div>
                                    </div>
                                    <b-row class="form-group">
                                        <b-col md="12" xl="12">
                                            <b-button type="submit" variant="alt-primary" block>
                                               Login
                                            </b-button>
                                        </b-col>
                                    </b-row>
                                </div>
                            </div>
                        </form>
                        <!-- END Sign In Form -->
                    </div>
                </div>
            </b-row>
        </div>

        <!-- END Page Content -->
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue';
export default {
    props: {
        canResetPassword: Boolean,
        status: String,
        errors: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            }),
            loading : false,
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('admin.login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}

</script>

<style>

</style>
