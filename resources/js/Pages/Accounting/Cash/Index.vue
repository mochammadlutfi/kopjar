<template>
    <BaseLayout>

        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Transaksi Kas
                <div class="float-right">
                    <a :href="route('accounting.cash.create')" class="btn btn-sm btn-secondary" >
                        <i class="si si-plus"></i>
                        Create
                    </a>
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
                                <th>Tanggal</th>
                                <th>Nomor</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th>Kas</th>
                                <th>Total</th>
                                <th width="10%"></th>
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
                                    <td>{{ (data.type == 'inbound') ? 'Kas Masuk' : 'Kas Keluar' }}</td>
                                    <td>{{ data.note }}</td>
                                    <td>{{ data.payment.payment_method.name }}</td>
                                    <td>{{ currency(data.total) }}</td>
                                    <th>
                                        <a class="btn btn-secondary btn-sm" :href="route('accounting.cash.show', {id : data.id})">
                                            <i class="si si-magnifier"></i>
                                            Detail
                                        </a>
                                    </th>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-if="!Object.values(dataList.data).length">
                                    <td>Data Kosong</td>
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
            selected: [],
            selectAll: false,
            search : this.route().params.search == '' ? '' : this.route().params.search,
            page : this.route().params.page == '' ? 1 : this.route().params.page,
        } 
    },
    mounted() {
        this.$inertia.on('start', (event) => {
            this.loading = true
        })
        this.$inertia.on('finish', (event) => {
            this.loading = false
        })
    },
    watch: {
        search: function () {
            this.doSearch()
        }
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
            this.$inertia.get(this.route('settings.user.index', { search : this.search }))
        }, 200),

        select() {
			this.selected = [];
			if (!this.selectAll) {
                this.dataList.data.forEach((value, index) => {
                    this.selected.push(value.id)
                    
                });
			}
		}
    }
}
</script>
