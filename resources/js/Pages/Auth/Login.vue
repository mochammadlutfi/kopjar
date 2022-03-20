<template>
    <div>
        
        <Head :title="title ? `${title} | ${ $page.props.settings.app_name }` : $page.props.settings.app_name">
            <slot />
        </Head>

        <!-- Page Content -->
        <div class="bg-white">
            <b-row class="justify-content-center mx-0">
                <div class="hero-static col-lg-6 col-xl-4">
                    <div class="content content-full overflow-hidden">
                        <!-- Header -->
                        <div class="py-30 px-5 text-center">
                            <a :href="route('dashboard')">
                                <img :src="asset('media/logo/logo_big.png')" width="300px">
                            </a>
                            <h2 class="h5 font-w700 mb-0 mt-30">Silakan masuk untuk melanjutkan</h2>
                        </div>
                        <!-- END Header -->


                        <!-- Sign In Form -->
                        <form @submit.prevent="submit">
                            <div class="block block-transparent">
                                <div class="block-content">
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="login-username">ID Anggota / Username</label>
                                            <input type="text" v-bind:class="{'form-control':true, 'is-invalid' : errors.username}" id="login-username" v-model="form.username" autofocus>
                                            <div v-if="errors.username" class="invalid-feedback">
                                                {{ errors.username[0] }}
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
    components :{
        Head,
    },
    data() {
        return {
            form: this.$inertia.form({
                username: '',
                password: '',
                remember: false
            }),
            loading : false,
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        }
    }
}

</script>

<style>

</style>
