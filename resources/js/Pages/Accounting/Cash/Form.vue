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
                
                <div class="block block-rounded block-shadow">
                    <div class="block-content pb-15">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Transaksi</label>
                                    <input type="text" class="form-control" v-model="form.nomor" readonly="readonly"/>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <flat-pickr class="form-control" :config="transactionDateConfig" v-bind:class="{'form-control':true }" v-model="form.tgl"></flat-pickr>
                                    <span id="error-tgl" class="invalid-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" id="field-keterangan" class="form-control" v-model="form.note">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Ref</label>
                                    <input type="text" id="field-ref" class="form-control" v-model="form.ref">
                                </div>
                                <div class="form-group">
                                    <label>Kas</label>
                                    <PaymentMethodSelect @done="(value) => form.payment_method_id = value" :errors="errors.payment_method" :data="editMode ? data.payment.payment_method : null"/>
                                </div>
                                
                                <div class="form-group">
                                    <label>Jenis Transaksi</label>
                                    <v-select :options="[{label: 'Kas Masuk', value: 'inbound'}, {label: 'Kas Keluar', value: 'outbound'}]" :reduce="type => type.value" v-model="form.type"></v-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content-heading pt-0 mb-3">
                    Rincian Transaksi
                    <div class="float-right">
                        <button type="button" class="btn btn-sm btn-secondary" @click="addLine">
                            <i class="si si-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
                <div class="block block-rounded block-shadow">
                    <div class="block-content p-0">
                            <table class="table table-striped table-vcenter mb-0">
                                <thead>
                                    <tr>
                                        <th width="20px">No</th>
                                        <th>Jurnal Akun</th>
                                        <th width="30%">Jumlah</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(d, i) in lines" :key="i" >
                                        <td>{{ i +1 }}</td>
                                        <td>
                                            <v-select label="name" :options="journal" v-model.lazy="lines[i].journal_id" :reduce="value => value.id">
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
                                        </td>
                                        <td>
                                            <CurrencyInput :value="lines[i].jumlah" class="form-control" @change="lines[i].jumlah = $event"/>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="removeLine(i)" v-show="i != 0">
                                                <i class="si si-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-success">
                                        <th  colspan="3" class="font-w600 text-right">Total: </th>
                                        <td>{{ currency(form.total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </form>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue';
import flatPickr from 'vue-flatpickr-component';
import moment from 'moment';
import _ from 'lodash';
import CurrencyInput  from '@/components/Form/CurrencyInput.vue';
import PaymentMethodSelect from '@/components/Form/PaymentMethodSelect.vue';
import vSelect from 'vue-select';

export default {
    components: {
        BaseLayout,
        flatPickr,
        CurrencyInput,
        PaymentMethodSelect,
        vSelect
    },
    props :{
        nomor : String,
        errors : Object,
        editMode : Boolean,
        journal : Array,
    },
    data(){
        return {
            title : 'Tambah Transaksi Kas',
            form : {
                id : null,
                tgl : null,
                type : null,
                note : null,
                nomor : this.nomor,
                ref : null,
                payment_method_id : null,
                total : 0,
            },
            lines : [
                {
                    journal_id : null,
                    jumlah : 0,
                },
            ],
            transactionDateConfig: {
                altFormat: 'j F Y',
                altInput: true,
                dateFormat: 'd-m-Y',  
            },
        }
    },
    watch :{
        lines :{
            deep : true,
            handler(val){
                // for (var i = 0; i < val.length; i++) {

                //     this.form.total = this.form.total + this.lines[i].subtotal;
                // }
                let total = 0;
                val.forEach(el => {
                    total += el.jumlah;
                });
                this.form.total = total;
            }
        }
    },
    methods : {
        addLine(){
            this.lines.push({
                journal_id: "",
                jumlah : 0,
            });
        },
        removeLine(index){
            this.lines.splice(index, 1);
        },
        submit: function () {
            this.$swal.fire({
                title: 'Tunggu Sebentar...',
                text: '',
                imageUrl: window._asset + 'media/loading.gif',
                showConfirmButton: false,
                // allowOutsideClick: false,
            });
            let lines = {
                lines : this.lines
            }
            let data = Object.assign(this.form, lines)
            let form = this.$inertia.form(data)
            let url = this.editMode ? this.route("accounting.cash.update") : this.route(
                "accounting.cash.store");
            form.post(url, {
                preserveScroll: true,
                onSuccess: () => {
                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: `Data Berhasil Disimpan!`,
                        showConfirmButton: false,
                    });
                },
                onFinish: () => {
                    this.$swal.close();
                },
            });
        },
    }
}
</script>
