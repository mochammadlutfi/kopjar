<template>
    <BaseLayout>
        <div class="content">
          <h2 class="content-heading">Tambah Anggota Baru</h2>
          
            <div class="block block-shadow block-bordered block-rounded">
                <div class="block-content p-0">
                    <!-- Step Tabs -->
                    <ul class="nav nav-tabs nav-tabs-alt nav-fill nav-tabs-block">
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{'active' : current_step == 1}" href="">1. Data Pribadi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  v-bind:class="{'active' : current_step == 2}" href="">2. Data Alamat</a>
                        </li>
                    </ul>
                    <!-- END Step Tabs -->
                </div>
                <!-- Steps Content -->
                <div class="block-content block-content-full" v-if="current_step == 1">
                    <div class="row">
                        <div class="col-lg-3">
                            <!-- <div class="text-center">
                                <img id="img_preview" src="{{ asset('media/placeholder/avatar.jpg') }}" class="img-fluid w-75">

                                <div class="btn btn-secondary w-75 mt-3">
                                    <input type="hidden" id="featured_img" name="featured_img" value="">
                                    <input type="file" class="file-upload" id="file-upload" accept="image/*">
                                    <i class="fa fa-folder-open mr-1"></i>
                                    Pilih Foto
                                </div>
                            </div> -->
                        </div>
                        <div class="col-lg-9">
                            <!-- <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >ID Anggota</label>
                                <div class="col-lg-8">
                                    <input type="text" id="field-anggota_id"  v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.anggota_id.$error}" v-model="v$.step1.anggota_id.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.anggota_id.$error">{{ v$.step1.anggota_id.$errors[0].$message }}</div>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >Nomor KTP</label>
                                <div class="col-lg-8">
                                    <input type="text" id="field-no_ktp" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.no_ktp.$error}" v-model="v$.step1.no_ktp.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.no_ktp.$error">{{ v$.step1.no_ktp.$errors[0].$message }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >Nama Lengkap</label>
                                <div class="col-lg-8">
                                    <input type="text" id="field-nama" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.nama.$error}" v-model="v$.step1.nama.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.nama.$error">{{ v$.step1.nama.$errors[0].$message }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Jenis Kelamin</label>
                                <div class="col-lg-8">
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="jk" id="jk1" value="L" v-model="step1.jk">
                                        <label class="custom-control-label" for="jk1">Laki-Laki</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="jk" id="jk2" value="P" v-model="step1.jk">
                                        <label class="custom-control-label" for="jk2">Perempuan</label>
                                    </div>
                                    <div class="text-danger" id="error-jk"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Tempat / Tanggal Lahir</label>
                                <div class="col-lg-4">
                                    <input type="text" id="field-tmp_lahir" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.tmp_lahir.$error}" v-model="v$.step1.tmp_lahir.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.tmp_lahir.$error">{{ v$.step1.tmp_lahir.$errors[0].$message }}</div>
                                </div>
                                <div class="col-lg-4">
                                    <flat-pickr v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.tgl_lahir.$error}" v-model="v$.step1.tgl_lahir.$model"></flat-pickr>
                                    <div class="invalid-feedback" v-if="v$.step1.tgl_lahir.$error">{{ v$.step1.tgl_lahir.$errors[0].$message }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status Perkawinan</label>
                                <div class="col-lg-8">
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" id="status_pernikahan1" value="Lajang" v-model="step1.perkawinan">
                                        <label class="custom-control-label" for="status_pernikahan1">Lajang</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" id="status_pernikahan2" value="Menikah" v-model="step1.perkawinan">
                                        <label class="custom-control-label" for="status_pernikahan2">Menikah</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" id="status_pernikahan3" value="Duda/Janda" v-model="step1.perkawinan">
                                        <label class="custom-control-label" for="status_pernikahan3">Duda/Janda</label>
                                    </div>
                                    <div class="text-danger" id="error-status_pernikahan"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >No. Handphone</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                +62
                                            </span>
                                        </div>
                                        <input type="text" id="field-no_hp" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.no_hp.$error}" v-model="v$.step1.no_hp.$model">
                                        <div class="invalid-feedback" v-if="v$.step1.no_hp.$error">{{ v$.step1.no_hp.$errors[0].$message }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >No. Telepon <sub class="text-danger">*Optional</sub></label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                +62
                                            </span>
                                        </div>
                                        <input type="text" id="field-telp" class="form-control phone" v-model="step1.no_telp">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >Alamat Email<sub class="text-danger">*Optional</sub></label>
                                <div class="col-lg-8">
                                    <input type="email" id="email" class="form-control" v-model="step1.email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Status</label>
                                <div class="col-lg-8">
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" id="pegawai" value="1" v-model="step1.tipe">
                                        <label class="custom-control-label" for="pegawai">Pegawai</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" id="non_pegawai" value="0" v-model="step1.tipe">
                                        <label class="custom-control-label" for="non_pegawai">Non Pegawai</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" v-if="step1.tipe == 1">
                                <label class="col-lg-4 col-form-label">NIP</label>
                                <div class="col-lg-8">
                                    <input type="email" id="email" class="form-control" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.nip.$error}" v-model="v$.step1.nip.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.nip.$error">{{ v$.step1.nip.$errors[0].$message }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Golongan</label>
                                <div class="col-lg-8">
                                    <select id="field-pekerjaan" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.golongan.$error}" v-model="v$.step1.golongan.$model">
                                        <option value="1">
                                            <template v-if="step1.tipe == 1">I</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_1)) }}</template>
                                        </option>
                                        <option value="2">
                                            <template v-if="step1.tipe == 1">II</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_2)) }}</template>
                                        </option>
                                        <option value="3">
                                            <template v-if="step1.tipe == 1">III</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_3)) }}</template>
                                        </option>
                                        <option value="4">
                                            <template v-if="step1.tipe == 1">IV</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_4)) }}</template>
                                        </option>
                                        <option value="5">
                                            <template v-if="step1.tipe == 1">V</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_5)) }}</template>
                                        </option>
                                        <option value="6">
                                            <template v-if="step1.tipe == 1">VI</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_6)) }}</template>
                                        </option>
                                        <option value="7">
                                            <template v-if="step1.tipe == 1">VII</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_7)) }}</template>
                                        </option>
                                        <option value="8">
                                            <template v-if="step1.tipe == 1">VIII</template>
                                            <template v-else>{{ currency(parseFloat($page.props.settings.simpanan_wajib_amount_8)) }}</template>
                                        </option>
                                    </select>
                                    <div class="invalid-feedback" v-if="v$.step1.golongan.$error">{{ v$.step1.golongan.$errors[0].$message }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Pendidikan Terakhir</label>
                                <div class="col-lg-8">
                                    <select id="field-pendidikan" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.pendidikan.$error}" v-model="v$.step1.pendidikan.$model">
                                        <option value="">Pilih</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA/Sederajat">SMA/Sederajat</option>
                                        <option value="Akademi/Diploma">Akademi/Diploma</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                        <div class="invalid-feedback" v-if="v$.step1.pendidikan.$error">{{ v$.step1.pendidikan.$errors[0].$message }}</div>
                                </div>
                            </div>

                            <div class="form-group row" v-if="step1.tipe == 0">
                                <label class="col-lg-4 col-form-label">Pekerjaan</label>
                                <div class="col-lg-8">
                                    <select id="field-pekerjaan" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.pekerjaan.$error}" v-model="v$.step1.pekerjaan.$model">
                                        <option value="">Pilih</option>
                                        <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                                        <option value="Pensiunan">Pensiunan</option>
                                        <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                        <option value="Pegawai Negeri">Pegawai Negeri</option>
                                        <option value="Guru">Guru</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="TNI/Polisi">TNI/Polisi</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                        <div class="invalid-feedback" v-if="v$.step1.pekerjaan.$error">{{ v$.step1.pekerjaan.$errors[0].$message }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" >Nama Ibu Kandung</label>
                                <div class="col-lg-8">
                                    <input type="text" id="field-nama_ibu" v-bind:class="{'form-control':true, 'is-invalid' : v$.step1.nama_ibu.$error}" v-model="v$.step1.nama_ibu.$model">
                                    <div class="invalid-feedback" v-if="v$.step1.nama_ibu.$error">{{ v$.step1.nama_ibu.$errors[0].$message }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Steps Content -->

                
                <div class="block-content block-content-full" v-if="current_step == 2">
                   
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="font-size-18 font-weight-bold">
                                        Alamat Identitas
                                    </div>
                                    <span class="text-danger">*Diisi sesuai dengan data KTP</span>
                                    <hr class="border-bottom my-1"/>
                                </div>
                            </div>
                            <input type="hidden" name="domisili" value="1">
                            <div class="form-group">
                                <label class="col-form-label">Alamat Lengkap</label>
                                <textarea id="field-alamat" v-bind:class="{'form-control':true, 'is-invalid' : v$.step2.alamat.$error}" v-model="v$.step2.alamat.$model"></textarea>
                                <div class="invalid-feedback" v-if="v$.step2.alamat.$error">{{ v$.step2.alamat.$errors[0].$message }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="field-wilayah">Wilayah</label>
                                        <v-select label="name" :filterable="false" :options="options" @search="onSearch" v-bind:class="{'p-0' : true, 'is-invalid' : v$.step2.wilayah_id.$error}" v-model="wil_selected">
                                            <template slot="no-options">
                                                Cari Kelurahan
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-center">
                                                    {{ option.text }}
                                                    </div>
                                                </template>
                                                <template slot="selected-option" slot-scope="option">
                                                <div class="selected d-center">
                                                    {{ option.text }}
                                                </div>
                                            </template>
                                        </v-select>
                                        <div class="invalid-feedback" v-if="v$.step2.wilayah_id.$error">{{ v$.step2.wilayah_id.$errors[0].$message }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="field-kode_pos">Kode POS</label>
                                        <input type="text" id="field-kode_pos" v-bind:class="{'form-control':true, 'is-invalid' : v$.step2.kode_pos.$error}" v-model="v$.step2.kode_pos.$model">
                                        <div class="invalid-feedback" v-if="v$.step2.kode_pos.$error">{{ v$.step2.kode_pos.$errors[0].$message }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-3x">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="font-size-18 font-weight-bold">
                                        Alamat Domisili
                                    </div>
                                    <span class="text-danger">*Diisi apabila alamat domisili tidak sesuai dengan identitas</span>
                                    <hr class="border-bottom my-1"/>
                                </div>
                            </div>
                            <input type="hidden" name="domisili2" value="0">
                            <div class="form-group">
                                <label class="col-form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="field-alamat2" v-model="step2.alamat2" rows="3"></textarea>
                                <div class="invalid-feedback" id="error-alamat2">Invalid feedback</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="field-wilayah_id2">Wilayah</label>
                                        <v-select label="text" :filterable="false" :options="wilayah2" @search="onSearch2" class="p-0" v-model="wil_selected2">
                                            <template slot="no-options">
                                                Cari Kelurahan
                                            </template>
                                            <template slot="wil2" slot-scope="wil2">
                                                <div class="d-center">
                                                    {{ wil2.text }}
                                                    </div>
                                                </template>
                                                <template slot="selected-option" slot-scope="wil2">
                                                <div class="selected d-center">
                                                    {{ wil2.text }}
                                                </div>
                                            </template>
                                        </v-select>
                                        <div class="invalid-feedback" id="error-wilayah_id2">Invalid feedback</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="field-kode_pos2">Kode POS</label>
                                        <input type="text" id="field-kode_pos2" v-model="step2.kode_pos2" class="form-control">
                                        <div class="invalid-feedback" id="error-kode_pos2">Invalid feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Steps Navigation -->
                <div class="block-content block-content-sm block-content-full bg-body-light">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-alt-secondary" @click="onBack" v-if="current_step > step.first">
                                <i class="fa fa-angle-left mr-5"></i>
                                Kembali
                            </button>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-alt-secondary" @click="onNext" v-if="current_step < step.last">
                                Selanjutnya 
                                <i class="fa fa-angle-right ml-5"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-noborder" @click="onNext" v-if="current_step === step.last">
                                Simpan 
                                <i class="si si-paper-plane ml-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, helpers, requiredIf , between, email, decimal, integer, url, sameAs } from '@vuelidate/validators'
import flatPickr from 'vue-flatpickr-component';
import vSelect from 'vue-select';
import _ from 'lodash';
import moment from 'moment';
import CurrencyInput  from '@/components/Form/CurrencyInput.vue';

export default {
    setup () { 
        return { 
            v$: useVuelidate(),

        } 
    },
    props :{
        data : Object,
    },
    components: {
        BaseLayout,
        flatPickr,
        vSelect,
        CurrencyInput
    },
    data(){
        return {
            step : {
                first : 1,
                last : 2,
            },
            current_step :1,
            step1 : {
                anggota_id : null,
                nama : null,
                no_ktp : null,
                jk : 'L',
                tmp_lahir : null,
                tgl_lahir : null,
                perkawinan : 'Lajang',
                no_hp : null,
                no_telp : null,
                email : null,
                pendidikan : null,
                pekerjaan : null,
                nama_ibu : null,
                ktp : null,
                tipe : 1,
                golongan : null,
                nip : null,
            },
            step2 : {
                id : null,
                alamat : null,
                wilayah_id : null,
                kode_pos : null,
                id2 : null,
                alamat2 : null,
                wilayah_id2 : null,
                kode_pos2 : null,
            },
            wil_selected : null,
            wil_selected2 : null,
            options : [],
            wilayah2 : [],
        } 
    },
    validations () {
        return {
            step1 : {
                anggota_id : {
                    required : helpers.withMessage('ID Anggota Wajib Diisi', required),
                },
                nama : {
                    required : helpers.withMessage('Nama Lengkap Wajib Diisi', required),
                    minLength: minLength(3)
                },
                no_ktp : {
                    required : helpers.withMessage('No KTP Wajib Diisi', required),
                    minLength: minLength(3)
                },
                tmp_lahir : {
                    required : helpers.withMessage('Tempat Lahir Wajib Diisi', required),
                    minLength: minLength(3)
                },
                tgl_lahir : {
                    required : helpers.withMessage('Tanggal Lahir Wajib Diisi', required),
                },
                no_hp : {
                    required : helpers.withMessage('No Handphone Wajib Diisi', required),
                    minLength: minLength(3)
                },
                pendidikan : {
                    required : helpers.withMessage('Pendidikan Terakhir Wajib Diisi', required),
                },
                nip : {
                    required : helpers.withMessage('NIP Wajib Diisi!', requiredIf(function(form){
                        return this.step1.tipe == 1
                    })),
                },
                golongan : {
                    required : helpers.withMessage('Golongan Wajib Diisi!', requiredIf(function(form){
                        return this.step1.tipe == 1
                    })),
                },
                pekerjaan : {
                    required : helpers.withMessage('Pekerjaan Wajib Diisi', requiredIf(function(form){
                        return this.step1.tipe == 0
                    })),
                },
                nama_ibu : {
                    required : helpers.withMessage('Nama Ibu Wajib Diisi', required),
                }
            },
            step2 : {
                alamat : {
                    required : helpers.withMessage('Alamat Wajib Diisi!', required),
                },
                wilayah_id : {
                    required : helpers.withMessage('Wilayah Wajib Diisi!', required),
                },
                kode_pos : {
                    required : helpers.withMessage('Kode POS Wajib Diisi!', required),
                },
            },
        }
    },
    watch : {
        wil_selected(val){
            if(val){
                this.step2.wilayah_id = val.id;
            }else{
                this.step2.wilayah_id = null;
            }
        },
        wil_selected2(val){
            if(val){
                this.step2.wilayah_id2 = val.id;
            }else{
                this.step2.wilayah_id2 = null;
            }
        }
    },
    mounted(){
        this.init();
    },
    methods: {
        onNext(){
            if(this.current_step === 1){
                // this.v$.step1.$validate()
                // if (this.v$.step1.$error) {
                //     return
                // }
                this.current_step++;
            }else{
                // this.v$.step3.$validate()
                // if (this.v$.step3.$error) {
                //     return
                // }
                
                this.$swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: '',
                    imageUrl: window._asset + 'media/loading.gif',
                    showConfirmButton: false,
                    // allowOutsideClick: false,
                });
                let form = this.$inertia.form(Object.assign({}, this.step1, this.step2))
                let url = this.route("anggota.update");
                form.post(url, {
                    preserveScroll: true,
                    resetOnSuccess: false,
                    onSuccess: () => {
                        return this.$swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `Data Anggota Berhasil Diperbaharui!`,
                            showCancelButton: true,
                            confirmButtonText: 'Tambah Lainnya',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$inertia.visit(this.route("anggota.create"));
                            }
                        });
                    },
                });
            }
        },
        onBack(){
            this.current_step--;
        },
        onSearch(search, loading) {
            if(search.length > 3) {
                loading(true);
                this.options = [];
                this.search(loading, search, this);
            }
        },
        search: _.debounce((loading, search, vm) => {
            // `https://api.github.com/search/repositories`
            fetch(vm.route('wilayah') +`?q=${escape(search)}`).then(res => {
                res.json().then(json => (vm.options = json));
                loading(false);
            });
        }, 350),
        onSearch2(search, loading) {
            if(search.length > 3) {
                loading(true);
                this.wil2 = [];
                this.search2(loading, search, this);
            }
        },
        search2: _.debounce((loading, search, vm) => {
            // `https://api.github.com/search/repositories`
            fetch(vm.route('wilayah') +`?q=${escape(search)}`).then(res => {
                res.json().then(json => (vm.wilayah2 = json));
                loading(false);
            });
        }, 350),
        init(){
            if(this.data){
                this.step1.anggota_id = this.data.anggota_id;
                this.step1.nama = this.data.nama;
                this.step1.no_ktp = this.data.no_ktp;
                this.step1.jk = this.data.jk;
                this.step1.tmp_lahir = this.data.tmp_lahir;
                this.step1.tgl_lahir = this.data.tgl_lahir;
                this.step1.perkawinan = this.data.perkawinan;
                this.step1.email = this.data.email;
                this.step1.no_hp = this.data.no_hp;
                this.step1.no_telp = this.data.no_telp;
                this.step1.pendidikan = this.data.pendidikan;
                this.step1.pekerjaan = this.data.pekerjaan;
                this.step1.nama_ibu = this.data.nama_ibu;
                this.step1.tipe = this.data.tipe;
                this.step1.golongan = this.data.golongan;
                this.step1.nip = this.data.nip;

                if(this.data.alamat.length){
                    this.data.alamat.forEach(el => {
                        if(this.data.alamat.length == 2){
                            // if(this.el.domisili == 1){
                            this.step2.alamat = el.alamat;
                            this.step2.kode_pos = el.pos;
                            this.step2.id = el.id;
                            this.wil_selected = {
                                id : el.wilayah_id,
                                text : el.daerah,
                            }
                            this.step2.wilayah_id = el.wilayah_id;

                            if(el.domisili == 1){
                                
                                this.step2.alamat2 = el.alamat;
                                this.step2.kode_pos2 = el.pos;
                                this.step2.id2 = el.id;
                                this.wil_selected2 = {
                                    id : el.wilayah_id,
                                    text : el.daerah,
                                }
                                this.step2.wilayah_id2 = el.wilayah_id;
                            }
                            // }

                        }else{
                            this.step2.alamat = el.alamat;
                            this.step2.kode_pos = el.pos;
                            this.step2.id = el.id;
                            this.wil_selected = {
                                id : el.wilayah_id,
                                text : el.daerah,
                            }
                            this.step2.wilayah_id = el.wilayah_id;
                        }
                    });
                    
                    // this.step2.alamat = this.data.alamat[0].alamat;
                    // this.step2.kode_pos = this.data.alamat[0].pos;
                    // this.step2.id = this.data.alamat[0].id;
                    // this.wil_selected = {
                    //     id : this.data.alamat[0].wilayah_id,
                    //     text : this.data.alamat[0].daerah,
                    // }
                    // this.step2.wilayah_id = this.data.alamat[0].wilayah_id;

                    // if(this.data.alamat.length > 1){
                        
                    //     this.step2.alamat2 = this.data.alamat[1].alamat;
                    //     this.step2.kode_pos2 = this.data.alamat[1].pos;
                    //     this.step2.id2 = this.data.alamat[1].id;
                    //     this.wil_selected2 = {
                    //         id : this.data.alamat[1].wilayah_id,
                    //         text : this.data.alamat[1].daerah,
                    //     }
                    //     this.step2.wilayah_id2 = this.data.alamat[1].wilayah_id;
                    // }
                }
            }
        }
    }
}
</script>
