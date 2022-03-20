<template>
    <BaseLayout>
        <div class="content">
            <div class="block block-shadow block-rounded mb-10">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-3">
                                    <img :src="asset(anggota.avatar)" class="img-avatar img-avatar96">
                                </div>
                                <div class="col-9">
                                    <h2 class="h5 mb-5">{{ anggota.anggota_id }}</h2>
                                    <h1 class="h3 font-w700 mb-5">{{ anggota.nama }}
                                        <i v-if="anggota.status == 1" class="si si-check text-primary"></i>
                                        <i v-else class="si si-close text-danger"></i>
                                    </h1>
                                    <ProfileAnggota :data="anggota"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="block block-shadow block-rounded mb-10">
                <ul class="nav nav-tabs nav-tabs-alt nav-fill">
                    <li class="nav-item">
                        <a class="nav-link" :href="route('anggota.show', {id : anggota.anggota_id})" v-bind:class="{'active' : !state}">Riwayat Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :href="route('anggota.show', {id : anggota.anggota_id, state : 'simpanan'})" v-bind:class="{'active' : state == 'simpanan'}">Simpanan</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" :href="route('anggota.show', {id : anggota.anggota_id, state : 'pembiayaan'})" v-bind:class="{'active' : state == 'pembiayaan'}">Pembiayaan</a>
                    </li> -->
                </ul>
            </div>

            
            <div class="row gutters-tiny" v-if="overview">
                <!-- Row #1 -->
                <div class="col-md-6 col-xl-4" v-for="(data, index) in overview" :key="index">
                    <div class="block block-shadow-2 block-bordered">
                        <div class="block-content block-content-full text-right">
                            <div class="font-size-sm font-w600 text-uppercase text-muted">{{ data['program'] }}</div>
                            <div class="font-size-h2 font-w700">{{ currency(data['saldo']) }}</div>
                        </div>
                    </div>
                </div>
                <!-- END Row #1 -->
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
                                <th>Layanan</th>
                                <th>Jenis</th>
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
                                    <td>{{ data.nomor }}</td>
                                    <td>{{ data.service }}</td>
                                    <td>{{ data.jenis }}</td>
                                    <td>{{ currency(parseInt(data.total)) }}</td>
                                    <td>status</td>
                                    <th>
                                        <button class="btn btn-secondary btn-sm" type="button">
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
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue'
// import ListTransaksi from '/Components/TableListTransaksi.vue';
import ProfileAnggota from './Components/ProfileModal.vue'
import { Link } from '@inertiajs/inertia-vue';
import moment from 'moment';
import _ from 'lodash';
export default {
    components: {
        BaseLayout,
        Link,
        ProfileAnggota
    },
    data(){
        return {
            loading : false,
            search : this.route().params.search == '' ? '' : this.route().params.search,
        }
    },
    watch: {
        search: function (value) {
            if(value.length > 4){
                this.doSearch()
            }
        },
    },
    props:{
        anggota : Object,
        state : String,
        dataList : Object,
        overview : Array,
    },
    methods :{
        format_date(value){
            if (value) {
            moment.locale('id');
            return moment(String(value)).format('DD MMMM YYYY')
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
            
            if(this.filterDate.startDate && this.filterDate.endDate){
                params = Object.assign(params, {
                    from : moment(this.filterDate.startDate).format('DD-MM-YYYY'),
                    to : moment(this.filterDate.endDate).format('DD-MM-YYYY')
                })
            }
            
            this.$inertia.get(this.route('simpanan.wajib.index', params))
        }, 200),
    }
}
</script>
