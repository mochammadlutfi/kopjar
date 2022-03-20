<template>
    <BaseLayout>
        <div class="content">
            <h2 class="content-heading">Piutang Potong Gaji</h2>
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
                                <th width="10%">ID Anggota</th>
                                <th width="18%">Nama</th>
                                <th width="15%">Golongan</th>
                                <th width="15%">NIP</th>
                                <th width="25%">Jumlah</th>
                                <th width="9%"></th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="6">
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
                                <tr v-for="(data, i) in dataList.data" :key="i">
                                    <td>
                                        <b-form-checkbox
                                            :id="'data-'+data.anggota_id"
                                            v-model="selected"
                                            :name="'data-'+data.anggota_id"
                                            :value="data.anggota_id"
                                            >
                                        </b-form-checkbox>
                                    </td> 
                                    <td>{{ data.anggota_id }}</td>
                                    <td>{{ data.nama }}</td>
                                    <td>{{ data.golongan }}</td>
                                    <td>{{ data.nip }}</td>
                                    <td>{{ currency(data.jumlah) }}</td>
                                    <th>
                                        <Link :href="route('anggota.show', { id : data.anggota_id, state : ''})" as="button" class="btn btn-secondary btn-sm" type="button">
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
import BaseLayout from '@/Layouts/Authenticated.vue';
import _ from 'lodash';

export default {
    components: {
        BaseLayout,
    },
    data(){
        return {
            loading : false,
            selected: [],
            selectAll: false,
            search : this.route().params.search == '' ? '' : this.route().params.search
        } 
    },
    props: {
        dataList : Object
    },
    methods :{
        format_date(value){
            if (value) {
                return moment(String(value)).format('DD MMM YYYY')
            }
        },
        doSearch : _.throttle(function(){
            this.$inertia.get(this.route('anggota.index', { search : this.search }))
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
