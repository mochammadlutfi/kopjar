<template>
    <BaseLayout title="Tambah Staff">
        <div class="content">
            <form v-on:submit.prevent="submit">
                <div class="content-heading pt-0 mb-3">
                    {{ title }}
                    <div class="float-right">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="si si-paper-plane mr-1"></i> Simpan
                    </button>
                    </div>
                </div>
                <div class="block">
                    <div class="block-content">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="field-anggota_id">Anggota</label>
                                    <anggota-select @done="(value) => anggotaSelected = value" :error="errors.anggota_id" :data="editMode ? anggotaSelected : null"/>
                                    <div class="invalid-feedback" id="error-anggota_id">Invalid feedback</div>
                                </div>

                                <div class="form-group">
                                    <label for="field-username">Username</label>
                                    <input type="text" class="form-control" id="field-username" name="username" autocomplete="username">
                                    <div class="invalid-feedback" id="error-username">Invalid feedback</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="field-password">Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control" id="field-password" name="password" autocomplete="new-password">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="javaScript:void(0);"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback" id="error-password"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="field-role">Jabatan</label>
                                    <v-select label="nama" :filterable="false" :options="roles" v-bind:class="{'is-invalid' : error }" v-model="form.role">
                                        <template slot="option" slot-scope="option">
                                            <div class="d-center">
                                                {{ option.name }}
                                                </div>
                                            </template>
                                            <template slot="selected-option" slot-scope="option">
                                            <div class="selected d-center">
                                                {{ option.name }}
                                            </div>
                                        </template>
                                    </v-select>
                                    <div class="invalid-feedback" id="error-role"></div>
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
import BaseLayout from '@/Layouts/Authenticated.vue';
import AnggotaSelect from '@/components/Form/AnggotaSelect.vue';
import _ from 'lodash';
import vSelect from 'vue-select';

export default {
    components: {
        BaseLayout,
        AnggotaSelect,
        vSelect
    },
    props :{
        roles : Array,
        errors : Object,
        editMode : Boolean,
    },
    data(){
        return {
            AnggotaSelect : null,
            form : {
                id : null,
                name : null,
                username : null,
                password : null,
                role : null,
            },
            title : 'Tambah Staff Baru',
        }
    },
    methods : {
        submit: function () {
            let form = this.$inertia.form(this.form)
            let url = this.editMode ? this.route("settings.staff.update") : this.route(
                "settings.staff.store");
            form.post(url, {
                preserveScroll: true,
                onSuccess: () => {
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: `Data Berhasil Disimpan!`,
                    });
                    this.reset();
                },
            });
        },
    }
}
</script>
