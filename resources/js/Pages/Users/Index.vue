<template>
    <BaseLayout>
        <div class="bg-body-light border-b">
            <div class="content py-5 text-center">
                <nav class="breadcrumb bg-body-light mb-0">
                    <span class="breadcrumb-item active">Settings</span>
                </nav>
            </div>
        </div>

        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Users
                <div class="float-right">
                    <button v-if="selected.length > 0" type="button" class="btn btn-sm btn-danger" >
                        <i class="si si-trash"></i>
                        Delete {{ selected.length }} Selected
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" >
                        <i class="si si-plus"></i>
                        Create
                    </button>
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
                                <th width="2%">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input class="custom-control-input" type="checkbox" id="checkAll" v-model="selectAll" @click="select">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Join Date</th>
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
                                    <td>
                                        <label class="custom-control custom-checkbox">
                                            <input class="custom-control-input" :id="data.id" type="checkbox" :value="data.id" v-model="selected">
                                            <label class="custom-control-label" :for="data.id"></label>
                                        </label>
                                    </td> 
                                    <td>{{ data.name }}</td>
                                    <td>{{ data.email }}</td>
                                    <td>{{ format_date(data.created_at) }}</td>
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
            search : this.route().params.search == '' ? '' : this.route().params.search
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
