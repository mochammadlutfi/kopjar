<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Profile
            </div>
            <form @submit.prevent="updateProfile" method="POST" enctype="multipart/form-data">
                <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-user-circle mr-5 text-muted"></i> User Profile
                        </h3>
                        <button type="submit" class="btn btn-alt-primary btn-sm">
                            <i class="si si-paper-plane"></i>
                            Update
                        </button>
                    </div> 
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-lg-3">
                                <p class="text-muted">
                                    Your account’s vital info. Your username will be publicly visible.
                                </p>
                            </div>
                            <div class="col-lg-7 offset-lg-1">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="profile-settings-name">Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                            id="profile-settings-name" name="profile-settings-name"
                                            placeholder="Enter your name.." v-model="profileForm.name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="profile-settings-email">Email Address</label>
                                        <input type="email" class="form-control form-control-lg"
                                            id="profile-settings-email" name="profile-settings-email"
                                            placeholder="Enter your email.." v-model="profileForm.email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </BaseLayout>
</template>

<script>
    import BaseLayout from '@/Layouts/Authenticated.vue'
    import {
        Link
    } from '@inertiajs/inertia-vue';

    export default {
        name: "UserProfile",
        components: {
            BaseLayout,
            Link,
        },
        props: {
            user : Object,
            errors: Object
        },
        data() {
            return {
                profileForm: this.$inertia.form({
                    name: this.user.name,
                    email: this.user.email,
                }),
            }
        },
        methods : {
            updateProfile() {
                this.profileForm.post(route('user.profile.update'), {
                    preserveScroll: true,
                });
            },
        }

    }

</script>
