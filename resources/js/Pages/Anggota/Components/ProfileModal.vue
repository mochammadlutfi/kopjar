<template>
    <div>
        <button type="button" class="btn btn-secondary btn-sm px-20 mr-5" @click="showDetail()">
            <i class="si si-user mr-5"></i>Profile
        </button>
        <b-modal v-model="showModal" size="lg" no-close-on-backdrop rounded body-class="p-0" content-class="rounded" hide-footer hide-header>
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Profil {{ data.nama }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="closeModal">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                        <div class="col-lg-3">
                            <div class="text-center">
                                <img :src="asset(data.avatar)" class="img-avatar img-avatar128">
                            </div>
                            
                            <div class="form-group row my-3 text-center">
                                <label class="col-6 col-lg-12 mb-0 font-size-18 font-size-14-down-lg ">Status</label>
                                <div class="col-6 col-lg-12">
                                    <div class="form-control-plaintext py-0 font-size-18 font-size-14-down-lg">
                                        <span class="badge badge-success" v-if="data.status == 1">Aktif</span>
                                        <span class="badge badge-danger" v-else>Keluar</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row my-3 text-center">
                                <label class="col-6 col-lg-12 mb-0 font-size-18 font-size-14-down-lg ">Tanggal Bergabung</label>
                                <div class="col-6 col-lg-12">
                                    <div class="form-control-plaintext py-0 font-size-18 font-size-14-down-lg">
                                        {{ format_date(data.tgl_gabung) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div class="font-size-18 font-weight-bold">
                                            Informasi Dasar
                                        </div>
                                        <a class="btn btn-sm btn-secondary" :href="route('anggota.edit', {id : data.anggota_id})">
                                            <i class="si si-note mr-1"></i> Ubah 
                                        </a>
                                    </div>
                                    <hr class="border-bottom my-1">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">ID Anggota</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.anggota_id }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Nama Lengkap</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.nama }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Jenis Kelamin</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: 
                                        <template v-if="data.jk == 'L'">Laki-Laki</template>
                                        <template v-else>Perempuan</template>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Tempat / Tanggal Lahir</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.tmp_lahir }} / {{ format_date(data.tgl_lahir) }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Status Pernikahan</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.status_pernikahan }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">No Handphone</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: +62{{ data.no_hp }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Email</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.email }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Pendidikan Terakhir</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.pendidikan }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Pekerjaan</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.pekerjaan }}</div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-lg-5 mb-0">Ibu Kandung</label>
                                <div class="col-lg-7">
                                    <div class="form-control-plaintext text-left py-0">: {{ data.nama_ibu }}</div>
                                </div>
                            </div>
                            <div class="my-3">
                                <div class="mb-3 row">
                                    <div class="col-12">
                                        <div class="font-size-18 font-weight-bold">
                                            Informasi Alamat
                                        </div>
                                        <hr class="border-bottom my-1">
                                    </div>
                                </div>
                                <div class="form-group mb-1" v-for="(alamat, index) in data.alamat" :key="index">
                                    <label class="font-size-18 font-size-14-down-lg">
                                        Alamat <sub class="text-danger" v-if="alamat.domisili == 1">*Domisili</sub>
                                        <sub class="text-danger" v-else>*KTP</sub>
                                    </label>
                                    <div class="form-control-plaintext text-left py-0 font-size-18 font-size-14-down-lg">
                                        {{ alamat.alamat}} <br>
                                        {{ alamat.daerah }} - {{ alamat.pos }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue';
import BaseLayout from '@/Layouts/Authenticated.vue';
import moment from 'moment';
import _ from 'lodash';
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'

export default {
    components: {
        BaseLayout,
        Link,
    },
    data(){
        return {
            loading : false,
            selected : null,
            search : this.route().params.search == '' ? '' : this.route().params.search,
            showModal : false
        }
    },
    watch: {
        search: function (value) {
            if(value.length > 4){
                this.doSearch()
            }
        },
    },
    props: ['show', 'data'],
    methods :{
        format_date(value){
            if (value) {
            moment.locale('id');
            return moment(String(value)).format('DD MMMM YYYY')
            }
        },
        showDetail: function(){
            this.showModal = true;
        },
        doSearch : _.throttle(function(){
            let params = {};

            if(this.search){
                let search = {
                    search : this.search
                }
                params = Object.assign(params, search)
            }
            this.$inertia.get(this.route('simpanan.wajib.tunggakan', params))
        }, 200),
        closeModal(){
            this.selected = null;
            this.showModal = false;
        },
    }
}
</script>
