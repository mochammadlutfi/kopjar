<template>
    <BaseLayout>
        <div class="content">
            <form v-on:submit.prevent="submit">
                <div class="content-heading pt-0 mb-3">
                        Buat Pengajuan Pembiayaan Tunai
                    <div class="float-right">
                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i class="si si-paper-plane mr-1"></i> Simpan
                        </button>
                    </div>
                </div>
                
                <div class="block">
                    <div class="block-content pb-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <anggota-select @done="(value) => form.anggota_id = value" :error="errors.anggota_id"/>
                                </div>
                                <div class="form-group">
                                    <label for="field-jumlah">Jumlah</label>
                                    <CurrencyInput v-model="form.amount" v-bind:class="{'form-control':true, 'is-invalid' : errors.amount }"/>
                                    <span v-if="errors.amount" class="invalid-feedback">{{ errors.amount[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Pengajuan</label>
                                    <flat-pickr class="form-control" :config="transactionDateConfig" v-bind:class="{'form-control':true }" v-model="form.tgl"></flat-pickr>
                                    <span id="error-tgl" class="invalid-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label for="field-tenor">Durasi Pembiayaan</label>
                                    <b-form-select v-model="form.tenor" id="field-tenor" class="form-control">
                                        <b-form-select-option :value="null">Pilih Tenor</b-form-select-option>
                                        <b-form-select-option value="2">2 Bulan</b-form-select-option>
                                        <b-form-select-option value="3">3 Bulan</b-form-select-option>
                                        <b-form-select-option value="6">6 Bulan</b-form-select-option>
                                        <b-form-select-option value="9">9 Bulan</b-form-select-option>
                                        <b-form-select-option value="12">12 Bulan</b-form-select-option>
                                    </b-form-select>
                                    <span id="error-tenor" class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Biaya Admin (1%)</label>
                                    <CurrencyInput v-model="form.admin_fee" v-bind:class="{'form-control-plaintext':true, 'is-invalid' : errors.amount }"/>
                                </div>
                                <div class="form-group">
                                    <label>Angsuran Pokok Bulanan</label>
                                    <CurrencyInput v-model="form.angsuran_pokok" v-bind:class="{'form-control-plaintext':true, 'is-invalid' : errors.amount }"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Jumlah Yang Deterima</label>
                                    <CurrencyInput v-model="form.diterima" v-bind:class="{'form-control-plaintext':true, 'is-invalid' : errors.amount }"/>
                                </div>
                                <div class="form-group">
                                    <label for="field-angsuran_bunga">Jumlah Angsuran Bulanan (3.5%)</label>
                                    <CurrencyInput v-model="form.angsuran_total" v-bind:class="{'form-control-plaintext':true, 'is-invalid' : errors.amount }"/>
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
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import moment from 'moment';
import _ from 'lodash';
import CurrencyInput  from '@/components/Form/CurrencyInput.vue';
import AnggotaSelect from '@/components/Form/AnggotaSelect.vue';

export default {
    components: {
        BaseLayout,
        flatPickr,
        AnggotaSelect,
        CurrencyInput,
    },
    data(){
        return {
            form : {
                anggota_id : null,
                amount : null,
                admin_fee : null,
                diterima : null,
                angsuran_pokok : null,
                bagi_hasil : null,
                angsuran_total : null,
                tgl : new Date(),
                tenor : null,
            },
            transactionDateConfig: {
                altFormat: 'j F Y',
                altInput: true,
                dateFormat: 'd-m-Y',  
            },
            errors : {
                anggota_id : null,
                periode : null,
                tgl : null,
            }
        }
    },
    props:  {
        transaksi_ref : String,
        type : String,
    },
    watch: {
        form :{
            handler(value){
                if(value.amount && value.tenor){
                    this.calculate(value.amount);
                }
            },
            deep: true
        },
    },
    mounted(){
        this.form.amount = 200000;
        this.form.tenor = 2;
    },
    methods : {
        submit: function(){
            this.$swal.fire({
                title: 'Tunggu Sebentar...',
                text: '',
                imageUrl: window._asset + 'media/loading.gif',
                showConfirmButton: false,
                allowOutsideClick: false,
            });
            
            axios.post(this.route('pembiayaan.tunai.store'), this.form)
            .then((res) => {
                if(res.data.fail){
                    this.errors = res.data.errors;
                    this.$swal.close();
                }else if(!res.data.fail){
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false,
                        html: `Pengajuan Pembiayaan Berhasil Disimpan!
                        <br><br>
                            <a href="${ this.route('simpanan.sukarela.create') }" class="btn btn-outline-primary">
                                <i class="si si-plus mr-1"></i>Tambah Lainnya
                            </a> 
                            <a href="${ this.route('simpanan.sukarela.index') }" class="btn btn-primary">
                                <i class="si si-action-undo mr-1"></i>Kembali
                            </a>`,
                    });
                }
            })
            .catch((error) => {
                // console.log(error);
            });
        },
        calculate: function(value){
            this.form.admin_fee = value * 1/100;
            this.form.diterima = value - this.form.admin_fee;
            this.form.angsuran_pokok = value / this.form.tenor;
            this.form.bagi_hasil = value * 3.9/100;
            this.form.angsuran_total = this.form.angsuran_pokok + this.form.bagi_hasil;
        }
    }
}
</script>
