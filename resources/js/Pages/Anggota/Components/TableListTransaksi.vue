<template>
    <div>
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
                            <th width="5%">ID Anggota</th>
                            <th width="8%">Nama</th>
                            <th width="15%">Kontak</th>
                            <th width="25%">Alamat</th>
                            <th width="9%">Status</th>
                            <th width="9%">Aksi</th>
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
                                <td>{{ data.anggota_id }}</td>
                                <td>{{ data.nama }}</td>
                                <td>
                                    <div class="font-size-16 font-w600">{{ data.no_hp }}</div>
                                    <div class="font-size-15">{{ data.email == "" ? "" :  data.email }}</div>
                                </td>
                                <td>{{ data.alamat[0].alamat_lengkap }}</td>
                                <td>{{ format_date(data.created_at) }}</td>
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
</template>

<script>
export default {
    props : ['dataList']
}
</script>