<template>
    <BaseLayout>
        <div class="content">
            <form v-on:submit.prevent="submit">
                <div class="content-heading pt-0 mb-3">
                        Buat Setoran Simpanan Sukarela
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
                                    <anggota-select @done="(value) => anggotaSelected = value" :error="errors.anggota_id" :data="editMode ? anggotaSelected : null"/>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <flat-pickr class="form-control" :config="transactionDateConfig" v-bind:class="{'form-control':true }" v-model="form.tgl"></flat-pickr>
                                    <span id="error-tgl" class="invalid-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <PaymentMethodSelect @done="(value) => form.payment_method_id = value" :errors="errors.payment_method" :data="editMode ? data.payment.payment_method : null"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Transaksi</label>
                                    <input type="text" class="form-control" v-model="form.kd_transaksi" readonly="readonly"/>
                                </div>
                                <div class="form-group">
                                    <label for="field-jumlah">Jumlah</label>
                                    <CurrencyInput v-model="form.amount" v-bind:class="{'form-control':true, 'is-invalid' : errors.amount }"/>
                                    <span v-if="errors.amount" class="invalid-feedback">{{ errors.amount[0] }}</span>
                                </div>
                                
                                <div class="form-group">
                                    <label>Keterangan (Optional)</label>
                                    <input type="text" id="field-keterangan" class="form-control" v-model="form.note">
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
import PaymentMethodSelect from '@/components/Form/PaymentMethodSelect.vue';

export default {
    components: {
        BaseLayout,
        flatPickr,
        AnggotaSelect,
        CurrencyInput,
        PaymentMethodSelect,
    },
    data(){
        return {
            form : {
                id : null,
                anggota_id : null,
                kd_transaksi : this.transaksi_ref,
                amount : null,
                tgl : new Date(),
                type : 'deposit',
                note : null,
                payment_method_id : null,
            },
            transactionDateConfig: {
                altFormat: 'j F Y',
                altInput: true,
                dateFormat: 'd-m-Y',  
            },
            anggotaSelected : null,
            paymentSelected : null,
            title : 'Tambah Setoran Simpanan Wajib',
        }
    },
    props:  {
        transaksi_ref : String,
        type : String,
        data : Object,
        errors : Object,
        editMode : Boolean,
    },
    watch: {
        
        anggotaSelected(val){
            if(val){
                this.form.anggota_id = val.anggota_id;
            }else{
                this.form.anggota_id = null;
            }
        },
    },
    mounted(){
        if(this.editMode){
            this.editModeActive();
        }
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
            let form = this.$inertia.form(this.form)
            let url = this.editMode ? this.route("simpanan.sukarela.update") : this.route("simpanan.sukarela.store");
            form.post(url, {
                preserveScroll: true,
                onProgress: () => {
                },
                onSuccess: () => {
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false,
                        html: `Setoran Simpanan Sukarela Berhasil!
                        <br><br>
                            <a href="${ this.route('simpanan.sukarela.create') }" class="btn btn-outline-primary">
                                <i class="si si-plus mr-1"></i>Tambah Lainnya
                            </a> 
                            <a href="${ this.route('simpanan.sukarela.index') }" class="btn btn-primary">
                                <i class="si si-action-undo mr-1"></i>Kembali
                            </a>`,
                    })
                    this.reset();
                },
                onError:(error) => {
                    this.$swal.close();
                },
            });
            
            // axios.post(this.route('simpanan.sukarela.store'), this.form)
            // .then((res) => {
            //     if(res.data.fail){
            //         this.errors = res.data.errors;
            //         this.$swal.close();
            //     }else if(!res.data.fail){
            //         this.$swal.fire({
            //             icon: 'success',
            //             title: 'Success',
            //             showConfirmButton: false,
            //             showCancelButton: false,
            //             // allowOutsideClick: false,
            //             html: `Setoran Simpanan Sukarela Berhasil!
            //             <br><br>
            //                 <a href="${ this.route('simpanan.sukarela.create') }" class="btn btn-outline-primary">
            //                     <i class="si si-plus mr-1"></i>Tambah Lainnya
            //                 </a> 
            //                 <a href="${ this.route('simpanan.sukarela.index') }" class="btn btn-primary">
            //                     <i class="si si-action-undo mr-1"></i>Kembali
            //                 </a>`,
            //         });
            //     }
            // })
            // .catch((error) => {
            //     // console.log(error);
            // });
        },
        editModeActive(){
            this.title = 'Ubah Simpanan Sukarela';
            this.anggotaSelected = this.data.anggota;
            this.form.id = this.data.id;
            this.form.anggota_id = this.data.anggota_id;
            this.form.kd_transaksi = this.data.nomor;
            this.form.amount = this.data.simla.debit;
            this.form.tgl = moment(String(this.data.tgl)).format('D MMMM YYYY');
            this.form.note = this.data.note;
            this.payment_method_id = this.data.payment.payment_method_id;
        },
    }
}
</script>
