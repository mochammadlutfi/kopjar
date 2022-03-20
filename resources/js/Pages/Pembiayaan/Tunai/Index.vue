<template>
    <BaseLayout>
        <div class="bg-body-light border-b">
            <div class="content py-5 text-center">
                <nav class="breadcrumb bg-body-light mb-0">
                    <span class="breadcrumb-item active">Pembiayaan Tunai</span>
                </nav>
            </div>
        </div>

        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Pembiayaan Tunai
                <div class="float-right">
                    <Link class="btn btn-sm btn-secondary" :href="route('pembiayaan.tunai.create')">
                        <i class="si si-plus"></i>
                        Tambah
                    </Link>
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
                                <th width="12%">No Pembiayaan</th>
                                <th>Anggota</th>
                                <th width="15%">Jumlah</th>
                                <th>Status</th>
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
                                    <td>{{ data.ref }}</td>
                                    <td>
                                        <Link class="font-w700" :href="route('anggota.show', { id : data.anggota_id })">
                                        <div>{{ data.anggota.anggota_id }}</div>
                                        <div>{{ data.anggota.nama }}</div>
                                        </Link>
                                    </td>
                                    <td>{{ currency(parseInt(data.jumlah)) }}</td>
                                    <td>
                                        <template v-if="data.status == 0">
                                            <span class="badge badge-warning">Pending</span>
                                        </template>
                                        <template v-else-if="data.status == 1">
                                            <span class="badge badge-info">Menunggu Pencairan</span>
                                        </template>
                                        <template v-else-if="data.status == 2">
                                            <span class="badge badge-primary">Aktif</span>
                                        </template>
                                        <template v-else-if="data.status == 3">
                                            <span class="badge badge-danger">Closed</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge badge-danger">Ditolak</span>
                                        </template>
                                    </td>
                                    <th>
                                        <Link class="btn btn-sm btn-secondary" :href="route('pembiayaan.tunai.show', {id : data.id})">
                                            <i class="si si-magnifier"></i>
                                            Detail
                                        </Link>
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

export default {
    components: {
        BaseLayout,
        Link,
    },
    data(){
        return {
            loading : false,
            search : this.route().params.search == '' ? '' : this.route().params.search,
            status : 'Semua'
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

            
            this.$inertia.get(this.route('simpanan.sukarela.index', params))
        }, 200),
    }
}
</script>

