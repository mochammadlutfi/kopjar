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
                Tunggakan Simpanan Wajib
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
                                <th>ID Anggota</th>
                                <th>Nama</th>
                                <th>Transaksi Terakhir</th>
                                <th>Jumlah</th>
                                <th>Nominal</th>
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
                                    <td>{{ data.anggota_id }}</td>
                                    <td>{{ data.nama }}</td>
                                    <td>{{ format_date(data.last_payment) }}</td>
                                    <td>{{ data.jumlah }} Bulan</td>
                                    <td>{{ currency(parseInt(data.nominal)) }}</td>
                                    <th>
                                        <button class="btn btn-secondary btn-sm" type="button" @click="showDetail(data)">
                                            <i class="si si-magnifier"></i>
                                            Detail
                                        </button>
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
        <b-modal v-model="showModal" size="md" no-close-on-backdrop rounded body-class="p-0" content-class="rounded" centered hide-footer hide-header>
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Detail Tunggakan</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="closeModal">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full" v-if="selected">
                    <div class="form-group row mb-1">
                        <label class="col-4 mb-0">ID Anggota</label>
                        <div class="col-8">
                            <div class="form-control-plaintext text-left py-0">{{ selected.anggota_id }}</div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-4 mb-0">Nama Lengkap</label>
                        <div class="col-8">
                            <div class="form-control-plaintext text-left py-0">{{ selected.nama }}</div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-4 mb-0">Jumlah Tunggakan</label>
                        <div class="col-8">
                            <div class="form-control-plaintext text-left py-0">{{ selected.jumlah }} Bulan</div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-4 mb-0">Nominal Tunggakan</label>
                        <div class="col-8">
                            <div class="form-control-plaintext text-left py-0">{{ currency(parseInt(selected.nominal)) }}</div>
                        </div>
                    </div>
                    <hr class="border-2x">
                    <div class="pb-15" v-for="(value, index) in selected.list" :key="index">
                        <h3 class="h5 mb-5">{{ index }}</h3>
                        <span class="badge badge-primary mr-1" v-for="(data, i) in value" :key="i">{{ data }}</span>
                    </div>
                </div>
            </div>
        </b-modal>
    </BaseLayout>
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
            showModal : false,
            search : this.route().params.search == '' ? '' : this.route().params.search,
        }
    },
    mounted(){
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
    },
    methods :{
        format_date(value){
            if (value) {
            moment.locale('id');
            return moment(String(value)).format('DD MMMM YYYY')
            }
        },

        showDetail: function(value){
            this.selected = value;
            if(this.selected){
            this.showModal = true;
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
            this.$inertia.get(this.route('simpanan.wajib.tunggakan', params))
        }, 200),
        closeModal(){
            this.selected = null;
            this.showModal = false;
        },
    }
}
</script>
