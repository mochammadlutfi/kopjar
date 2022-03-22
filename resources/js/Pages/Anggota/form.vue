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
                        <li class="nav-item">
                            <a class="nav-link"  v-bind:class="{'active' : current_step == 3}" href="">3. Pembayaran</a>
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
                                    <b-form-radio-group id="radio-group-2" v-model="step1.jk" :aria-describedby="ariaDescribedby" name="field-jk">
                                        <b-form-radio value="L">Laki-Laki</b-form-radio>
                                        <b-form-radio value="P">Perempuan</b-form-radio>
                                    </b-form-radio-group>
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
                                    <b-form-radio-group id="radio-group-2" v-model="step1.perkawinan" :aria-describedby="ariaDescribedby" name="field-perkawinan">
                                        <b-form-radio value="Lajang">Lajang</b-form-radio>
                                        <b-form-radio value="Menikah">Menikah</b-form-radio>
                                        <b-form-radio value="Duda/Janda">Duda/Janda</b-form-radio>
                                    </b-form-radio-group>
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
                                    <b-form-radio-group id="radio-group-2" v-model="step1.tipe" :aria-describedby="ariaDescribedby" name="field-status">
                                        <b-form-radio value="1">Pegawai</b-form-radio>
                                        <b-form-radio value="0">Non Pegawai</b-form-radio>
                                    </b-form-radio-group>
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
                                        <v-select label="name" :filterable="false" :reduce="(option) => option.id" :options="options" @search="onSearch" v-bind:class="{'p-0' : true, 'is-invalid' : v$.step2.wilayah_id.$error}" v-model="v$.step2.wilayah_id.$model">
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
                                        <v-select label="text" :filterable="false" :reduce="(wil2) => wil2.id" :options="wilayah2" @search="onSearch2" class="p-0" v-model="step2.wilayah_id2">
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

                <div class="block-content block-content-full" v-if="current_step == 3">
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label>No. Transaksi</label>
                                            <input type="text" class="form-control" v-model="step3.transaksi_ref"  readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <label for="field-tgl_payment">Tanggal Transaksi</label>
                                        <flat-pickr class="form-control" v-model="step3.tgl" :config="transactionDateConfig"></flat-pickr>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Metode Pembayaran</label>
                                        <PaymentMethodSelect @done="(value) => form.payment_method_id = value"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Keterangan (Optional)</label>
                                        <input type="text" id="field-keterangan" class="form-control" v-model="step3.keterangan">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-vcenter">
                                        <thead>
                                            <tr>
                                                <th width="30%">Jenis Pembayaran</th>
                                                <th width="20%">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>Administrasi</td>
                                                <td>
                                                    {{ currency(25000) }}
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td>Simpanan Pokok</td>
                                                <td>
                                                    {{ currency(parseFloat($page.props.settings.simpanan_pokok_amount)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Simpanan Wajib</td>
                                                <td>
                                                    {{ currency(step3.wajib) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Simpanan Sukarela</td>
                                                <td>
                                                    <CurrencyInput v-model="step3.sukarela" class="form-control"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th width="30%">TOTAL SETORAN</th>
                                                <th>
                                                    <div class="form-control-plaintext">
                                                        <div class="currency" id="total_setoran">{{ currency(step3.total) }}</div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
import PaymentMethodSelect from '@/components/Form/PaymentMethodSelect.vue';

export default {
    setup () { 
        return { 
            v$: useVuelidate(),

        } 
    },
    props :{
        transaksi_ref : String,
    },
    components: {
        BaseLayout,
        flatPickr,
        vSelect,
        CurrencyInput,
        PaymentMethodSelect
    },
    data(){
        return {
            step : {
                first : 1,
                last : 3,
            },
            current_step :1,
            step1 : {
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
                alamat : null,
                wilayah_id : null,
                kode_pos : null,
                alamat2 : null,
                wilayah_id2 : null,
                kode_pos2 : null,
            },

            step3 : {
                transaksi_ref : this.transaksi_ref,
                tgl : new Date(),
                keterangan : null,
                pokok : 150000,
                wajib : 0,
                sukarela : 0,
                total : 0,
            },

            options : [],
            wilayah2 : [],
            transactionDateConfig: {
                altInput: true,
                dateFormat: 'd-m-Y',  
            },
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
    watch :{
        step1 : {
            deep : true,
            handler(val){
                if(val.tipe == 1){
                    if(val.golongan == 1){
                        this.step3.wajib = 20000;
                    }else if(val.golongan == 2){
                        this.step3.wajib = 25000;
                    }else if(val.golongan == 3){
                        this.step3.wajib = 50000;
                    }else if(val.golongan == 4){
                        this.step3.wajib = 100000;
                    }else if(val.golongan == 5){
                        this.step3.wajib = 150000;
                    }else if(val.golongan == 6){
                        this.step3.wajib = 200000;
                    }else if(val.golongan == 7){
                        this.step3.wajib = 250000;
                    }else{
                        this.step3.wajib = 300000;
                    }
                }else{
                    this.step3.wajib = 20000;
                }
            }
        },
        step3 : {
            deep : true,
            handler(val){
                val.total = val.pokok + val.wajib + val.sukarela;
            }
        }
    },
    // computed: {
    //     // a computed getter
    //     wajibAmount: function () {
    //     // `this` points to the vm instance
    //         var amount = 0;
    //         if(this.step1.tipe == 1){
    //             if(this.step1.golongan == 1){
    //                 amount = 20000;
    //             }else if(this.step1.golongan == 2){
    //                 amount = 25000;
    //             }else if(this.step1.golongan == 3){
    //                 amount = 50000;
    //             }else if(this.step1.golongan == 4){
    //                 amount = 100000;
    //             }else if(this.step1.golongan == 5){
    //                 amount = 150000;
    //             }else if(this.step1.golongan == 6){
    //                 amount = 200000;
    //             }else if(this.step1.golongan == 7){
    //                 amount = 250000;
    //             }else{
    //                 amount = 300000;
    //             }
    //         }else{
    //             amount = 20000;
    //         }
    //         return amount;
    //     },
    // },
    methods: {
        onComplete(){

        },
        onNext(){
            if(this.current_step === 1){
                // this.v$.step1.$validate()
                // if (this.v$.step1.$error) {
                //     return
                // }
                this.current_step++;
            }else if(this.current_step === 2){
                // this.v$.step2.$validate()
                // if (this.v$.step2.$error) {
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
                    allowOutsideClick: false,
                });
                let form = this.$inertia.form(Object.assign({}, this.step1, this.step2, this.step3))
                let url = this.route("anggota.store");
                form.post(url, {
                    preserveScroll: true,
                    resetOnSuccess: false,
                    onSuccess: () => {
                        return this.$swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `Data Anggota Berhasil Disimpan!`,
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
        getWajibAmount(){

        }
    }
}
</script>
