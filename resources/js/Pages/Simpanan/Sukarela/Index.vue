<template>
    <BaseLayout>
        <div class="bg-body-light border-b">
            <div class="content py-5 text-center">
                <nav class="breadcrumb bg-body-light mb-0">
                    <span class="breadcrumb-item active">Riwayat Transaksi</span>
                </nav>
            </div>
        </div>

        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Statistik
                
                <div class="float-right">
                    <date-range-picker 
                    ref="picker" 
                    :locale-data="{ firstDay: 1, format: 'dd mmmm yyyy' }" 
                    control-container-class="form-control form-control-sm" 
                    v-model="filterDate"
                    :auto-apply="true"
                    @update="doSearch"
                    >
                    </date-range-picker>
                </div>
            </div>

            <div class="row gutters-tiny">
                <div class="col-md-6 col-xl-3">
                    <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                        <div class="block-content block-content-full block-sticky-options">
                            <div class="block-options">
                                <div class="block-options-item">
                                <i class="fa fa-circle-o fa-2x text-info-light"></i>
                                </div>
                            </div>
                            <div class="py-20 text-center">
                                <div class="font-size-h2 font-w700 mb-0 text-info">{{ statistic['all'] }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Semua Transaksi</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-xl-3">
                    <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                        <div class="block-content block-content-full block-sticky-options">
                            <div class="block-options">
                                <div class="block-options-item">
                                <i class="fa fa-spinner fa-spin fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="py-20 text-center">
                                <div class="font-size-h2 font-w700 mb-0 text-warning">{{ statistic['pending'] }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Pending</div>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-xl-3">
                    <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                        <div class="block-content block-content-full block-sticky-options">
                            <div class="block-options">
                                <div class="block-options-item">
                                <i class="fa fa-check fa-2x text-success-light"></i>
                                </div>
                            </div>
                            <div class="py-20 text-center">
                                <div class="font-size-h2 font-w700 mb-0 text-success">{{ statistic['done'] }}</div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Done</div>
                            </div>
                        </div>
                    </a>

                </div>

                <div class="col-md-6 col-xl-3">
                    <Link class="block block-rounded block-link-shadow" :href="route('simpanan.sukarela.create')">
                        <div class="block-content block-content-full block-sticky-options">
                            <div class="block-options">
                                <div class="block-options-item">
                                <i class="si si-paper-plane fa-3x text-success-light"></i>
                                </div>
                            </div>
                            <div class="py-20 text-center">
                                <div class="font-size-h2 font-w700 mb-0 text-success">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Tambah</div>
                            </div>
                        </div>
                    </Link>
                </div>

            </div>

            <div class="content-heading mb-3">
                Setoran Simpanan Sukarela
                <div class="float-right">
                </div>
            </div>
            <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
                <div class="block-content p-2">
                    <div class="row justify-content-between">
                        <div class="col-4">
                            <div class="has-search">
                                <i class="fa fa-search"></i>
                                <input type="search" class="form-control" id="search-data-list" v-model="search" @keyup="doSearch()" @change="doSearch()">
                            </div>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <div class="d-flex float-right">
                                <div class="my-auto px-3">
                                    <span>{{ dataList.from }}-{{ dataList.to }}/{{ dataList.total }}</span>
                                </div>
                                <div class="pt-25 pl-0">
                                    <Link :href="dataList.prev_page_url ? dataList.prev_page_url : '#'" as="button" class="btn btn-alt-secondary mx-1" type="button"
                                    :disabled="dataList.prev_page_url ? false : true">
                                        <i class="fa fa-chevron-left fa-fw"></i>
                                    </Link>
                                    <Link :href="dataList.next_page_url ? dataList.next_page_url : '#'" as="button" class="btn btn-alt-secondary mx-1" type="button"
                                    :disabled="dataList.next_page_url ? false : true">
                                        <i class="fa fa-chevron-right fa-fw"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block block-rounded block-shadow-2 block-bordered mb-5">
                <div class="block-content px-0 py-0">
                    <table class="table table-striped table-vcenter table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="12%">No. Transaksi</th>
                                <th>Anggota</th>
                                <th width="15%">Jumlah</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Status Pembayaran</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="4">
                                    <div class="text-center py-50">
                                        <div class="spinner-border text-primary wh-50" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <template v-if="Object.values(dataList.data).length">
                                <tr v-for="data in dataList.data" :key="data.id">
                                    <td>{{ format_date(data.tgl) }}</td>
                                    <td>{{ data.nomor }}</td>
                                    <td>
                                        <Link class="font-w700" :href="route('anggota.show', { id : data.anggota_id })">
                                        <div>{{ data.anggota_id }}</div>
                                        <div>{{ data.nama }}</div>
                                        </Link>
                                    </td>
                                    <td>{{ currency(parseFloat(data.debit)) }}</td>
                                    <td class="text-center">
                                        <template v-if="data.status == 1">
                                            <span class="badge badge-primary">Done</span>
                                        </template>
                                        <template v-else-if="data.status == 2">
                                            <span class="badge badge-danger">Cancel</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge badge-warning">Pending</span>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <template v-if="data.payment_status == 'paid'">
                                            <span class="badge badge-primary">Lunas</span>
                                        </template>
                                        <template v-else-if="data.payment_status == 'unpaid'">
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge badge-warning">Cancel</span>
                                        </template>
                                    </td>
                                    <th>
                                        <a class="btn btn-secondary btn-sm" :href="route('simpanan.sukarela.show', { id : data.id })">
                                            <i class="si si-magnifier"></i>
                                            Detail
                                        </a>
                                    </th>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-if="!Object.values(dataList.data).length">
                                    <td colspan="7">Data Kosong</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue';
import BaseLayout from '@/Layouts/Authenticated.vue';
import moment from 'moment';
import _ from 'lodash';
import flatPickr from 'vue-flatpickr-component';
import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
export default {
    components: {
        BaseLayout,
        Link,
        DateRangePicker
    },
    data(){
        return {
            loading : false,
            status : this.route().params.status == '' ? '' : this.route().params.status,
            search : this.route().params.search == '' ? '' : this.route().params.search,
            filterDate : {
                startDate : this.route().params.from == null ? moment().startOf('month') :  moment(this.route().params.from, 'DD-MM-YYYY'),
                endDate :  this.route().params.to == null ? moment() : moment(this.route().params.to, 'DD-MM-YYYY'),
            },
            configRange : {
                mode: "multiple",
                maxDate: "today",
                dateFormat: "Y-m-d",
            }
        } 
    },
    watch: {
        search: function (value) {
            if(value.length > 4){
                this.doSearch()
            }
        },
    },
    props: {
        dataList: Object,
        statistic : Object
    },
    methods :{
        format_date(value){
            if (value) {
            return moment(String(value)).format('DD MMM YYYY')
            }
        },
        doSearch : _.throttle(function(){
            let params = {};

            if(this.search){
                let search = {
                    search : this.search
                }
                params = Object.assign(params, search)
            }
            if(this.status){
                let status = {
                    status : this.status
                }
                params = Object.assign(params, status);
            }
            
            if(this.filterDate.startDate && this.filterDate.endDate){
                params = Object.assign(params, {
                    from : moment(this.filterDate.startDate).format('DD-MM-YYYY'),
                    to : moment(this.filterDate.endDate).format('DD-MM-YYYY')
                })
            }
            
            this.$inertia.get(this.route('simpanan.sukarela.index', params))
        }, 200),
    }
}
</script>
