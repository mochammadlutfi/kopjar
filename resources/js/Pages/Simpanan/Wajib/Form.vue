<template>
    <BaseLayout>
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

                <div class="block block-rounded">
                    <div class="block-content pb-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <anggota-select @done="(value) => anggotaSelected = value" :error="errors.anggota_id" :data="editMode ? anggotaSelected : null"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Transaksi</label>
                                    <input type="text" class="form-control" v-model="form.kd_transaksi"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Periode Bulan</label>
                                    <flat-pickr ref="periode" :config="periodeConfig" v-bind:class="{'form-control':true, 'is-invalid' : errors.periode}" v-model="form.periode" :disabled="!form.anggota_id"></flat-pickr>
                                    <div class="invalid-feedback" v-if="errors.periode">{{ errors.periode[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <flat-pickr class="form-control" :config="transactionDateConfig" v-bind:class="{'form-control':true }" v-model="form.tgl"></flat-pickr>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Setoran</label>
                                    <CurrencyInput v-model="form.wajib" class="form-control"/>
                                    <div class="invalid-feedback" v-if="errors.wajib">{{ errors.wajib[0] }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <!-- <anggota-select @done="(value) => anggotaSelected = value" :error="errors.anggota_id"/> -->
                                    <PaymentMethodSelect @done="(value) => form.payment_method_id = value" :errors="errors.payment_method" :data="editMode ? data.payment.payment_method : null"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Keterangan (Optional)</label>
                                    <input type="text" id="field-keterangan" class="form-control" v-model="form.note" placeholder="Masukan Keterangan (Jika Ada)">
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
import flatPickr from 'vue-flatpickr-component';
import moment from 'moment';
import _ from 'lodash';
import CurrencyInput  from '@/components/Form/CurrencyInput.vue';
import AnggotaSelect from '@/components/Form/AnggotaSelect.vue';
import PaymentMethodSelect from '@/components/Form/PaymentMethodSelect.vue';
import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect/index.js'
import 'flatpickr/dist/plugins/monthSelect/style.css'
import axios from 'axios';

export default {
    components: {
        BaseLayout,
        flatPickr,
        AnggotaSelect,
        CurrencyInput,
        PaymentMethodSelect,
    },
    props: {
        transaksi_ref : String,
        editMode : Boolean,
        data : Object,
    },
    data(){
        return {
            options : [],
            anggotaSelected : null,
            paymentSelected : null,
            form : {
                id : null,
                anggota_id : null,
                kd_transaksi : this.transaksi_ref,
                wajib : 0,
                tgl : new Date(),
                periode : null,
                payment_method_id : null,
                note : null,
            },
            transactionDateConfig: {
                altFormat: 'j F Y',
                altInput: true,
                dateFormat: 'd-m-Y',  
            },
            periodeConfig: {
                minDate: new moment().startOf('year').format('Y-M'),
                
                // minDate: '2021-01',
                maxDate: new moment().endOf('year').format('Y-M'),
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "F Y",
                    })
                ],
            },
            errors : {
                anggota_id : null,
                periode : null,
                tgl : null,
            },
            title : 'Tambah Setoran Simpanan Wajib',
        }
    },
    mounted(){
        if(this.editMode){
            this.editModeActive();
        }
    },
    watch : {
        form :{
            handler(value){
                // if(value.anggota_id){
                //     this.getPeriode(value.anggota_id);
                // }
                // if(value.periode){
                //     var fp = this.$refs.periode.fp;
                //     var fpHead = fp.monthNav.children;
                //     var rContainer = fp.rContainer;
                //     var monthsContainer = rContainer.children[0];
                //     var monthsElements = monthsContainer.children;
                //     fpHead[0].classList.add('flatpickr-disabled')
                //     fpHead[2].classList.add('flatpickr-disabled')
                //     if (this.disabledMonth) {
                //         this.disabledMonth.forEach((monthIndex) => {
                //             setTimeout(() => {
                //                 var monthEl = monthsElements[monthIndex];
                //                 monthEl.classList.add('disabled');
                //             }, 100);
                //         });
                //     }
                // }
            },
            deep: true
        },
        anggotaSelected(val){
            if(val){
                this.form.anggota_id = val.anggota_id;
                this.getPeriode(val.anggota_id);
                if(val.golongan == 1){
                    this.form.wajib = 20000;
                }else if(val.golongan == 2){
                    this.form.wajib = 25000;
                }else if(val.golongan == 3){
                    this.form.wajib = 50000;
                }else if(val.golongan == 4){
                    this.form.wajib = 100000;
                }else if(val.golongan == 5){
                    this.form.wajib = 150000;
                }else if(val.golongan == 6){
                    this.form.wajib = 200000;
                }else if(val.golongan == 7){
                    this.form.wajib = 250000;
                }else if(val.golongan == 8){
                    this.form.wajib = 300000;
                }else{
                    this.form.wajib = 20000;
                }
            }
        },
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
            if(this.editMode){
                var url = this.route('simpanan.wajib.update');
            }else{
                var url = this.route('simpanan.wajib.store');
            }
            axios.post(url, this.form)
            .then((res) => {
                if(res.data.fail){
                    this.errors = res.data.errors;
                    this.$swal.close();
                }else{
                    this.$swal.close();
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false,
                        html: `Setoran Simpanan Wajib Berhasil!
                        <br><br>
                            <a href="${ this.route('simpanan.wajib.create') }" class="btn btn-outline-primary">
                                <i class="si si-plus mr-1"></i>Tambah Lainnya
                            </a> 
                            <a href="${ this.route('simpanan.wajib.index') }" class="btn btn-primary">
                                <i class="si si-action-undo mr-1"></i>Kembali
                            </a>`,
                    })
                }
            })
            .catch((error) => {
                // console.log(error);
            });
        },

        getPeriode(value){
            axios.get(this.route('simpanan.wajib.paid', value))
            .then((res) => {
                var disabled = res.data.date;
                
                var fp = this.$refs.periode.fp;
                var rContainer = fp.rContainer;
                var fpHead = fp.monthNav.children;
                fpHead[0].classList.add('flatpickr-disabled');
                fpHead[2].classList.add('flatpickr-disabled');
                var monthsContainer = rContainer.children[0];
                var monthsElements = monthsContainer.children;

                if (disabled) {
                    disabled.forEach((monthIndex) => {
                        var month = moment(String(monthIndex)).format('M');
                        setTimeout(() => {
                            var monthEl = monthsElements[parseInt(month) - 1];
                            monthEl.classList.add('disabled');
                        }, 100);
                    });
                    }
            })
        },
        editModeActive(){
            this.title = 'Ubah Setoran Simpanan Wajib';
            this.anggotaSelected = this.data.anggota;
            this.form.id = this.data.id;
            this.form.anggota_id = this.data.anggota_id;
            this.form.kd_transaksi = this.data.nomor;
            this.form.wajib = this.data.total;
            this.form.tgl = moment(String(this.data.tgl)).format('D MMMM YYYY');
            this.form.periode = moment(String(this.data.simkop[0].periode)).format('MMMM YYYY');
            this.form.note = this.data.note;
        },
    }
}
</script>
