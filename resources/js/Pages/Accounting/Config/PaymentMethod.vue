<template>
    <BaseLayout title="Metode Pembayaran">
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Metode Pembayaran
                <div class="float-right">
                    <button v-if="selected.length > 0" type="button" class="btn btn-sm btn-danger">
                        <i class="si si-trash"></i>
                        Hapus {{ selected.length }} data
                    </button>
                    <button class="btn btn-sm btn-secondary" type="button" @click="openModal">
                        <i class="si si-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
            <div class="block block-rounded block-shadow block-bordered d-md-block d-none mb-10">
                <div class="block-content p-2">
                    <div class="row justify-content-between">
                        <div class="col-4">
                            <div class="has-search">
                                <i class="fa fa-search"></i>
                                <input type="search" class="form-control" id="search-data-list" v-model="search"
                                    @keyup="doSearch()" @change="doSearch()">
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
                                    <Link :href="dataList.prev_page_url ? dataList.prev_page_url : '#'" as="button"
                                        class="btn btn-alt-secondary mx-1" type="button"
                                        :disabled="dataList.prev_page_url ? false : true">
                                    <i class="fa fa-chevron-left fa-fw"></i>
                                    </Link>
                                    <Link :href="dataList.next_page_url ? dataList.next_page_url : '#'" as="button"
                                        class="btn btn-alt-secondary mx-1" type="button"
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
                                    <b-form-checkbox id="selectAll" name="selectAll" v-model="selectAll"></b-form-checkbox>
                                </th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Total Pembayaran</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="5">
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
                                        <b-form-checkbox
                                            :id="'data-'+data.id"
                                            v-model="selected"
                                            :name="'data-'+data.id"
                                            :value="data.id"
                                            >
                                        </b-form-checkbox>
                                    </td>
                                    <td>{{ data.name }}</td>
                                    <td>{{ data.type }}</td>
                                    <td>
                                        <span class="badge badge-primary" v-if="data.status == 1">Aktif</span>
                                        <span class="badge badge-danger" v-else>Nonaktif</span>
                                    </td>
                                    <td></td>
                                    <th>
                                        <b-button variant="secondary" v-b-tooltip.hover.nofade.top="'Edit'"
                                            @click="edit(data)" size="sm">
                                            <i class="si si-note"></i>
                                        </b-button>
                                        <b-button variant="secondary" v-b-tooltip.hover.nofade.top="'Delete'"
                                            @click="destroy(data)" size="sm">
                                            <i class="si si-trash"></i>
                                        </b-button>
                                    </th>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-if="!Object.values(dataList.data).length">
                                    <td colspan="6">
                                        Data Kosong
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <b-modal v-model="modalShow" size="md" rounded body-class="p-0" centered hide-footer hide-header>
            <div class="block block-rounded  block-transparent mb-0">
                <form @submit.prevent="submit">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ title }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" @click="reset">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <b-form-group label="Tipe" v-slot="{ ariaDescribedby }">
                            <b-form-radio-group id="field-type" v-model="form.type" :aria-describedby="ariaDescribedby">
                                <b-form-radio value="offline">Offline</b-form-radio>
                                <b-form-radio value="online">Online</b-form-radio>
                            </b-form-radio-group>
                        </b-form-group>
                        <div class="form-group" v-if="form.type == 'offline'">
                            <label for="field-name">Nama</label>
                            <input type="text" v-bind:class="{'form-control':true, 'is-invalid' : errors.name}"
                                id="field-name" v-model="form.name">
                            <div v-if="errors.name" class="invalid-feedback font-size-sm">{{ errors.name[0] }}</div>
                        </div>

                        <div class="form-group" v-if="form.type == 'online'">
                            <label for="field-bank_id">Bank</label>
                                <v-select :options="bankList" v-model="bankSelected"  v-bind:class="{'form-control':true, 'p-0' : true, 'is-invalid' : errors.bank_id }"  index="id">
                                    <template v-slot:option="option">
                                            {{ option.name }} | {{ option.account_no }}
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <!-- <div class="selected d-center"> -->
                                            {{ option.name }} | {{ option.account_no }}
                                        <!-- </div> -->
                                    </template>
                                </v-select>
                            <div v-if="errors.bank_id" class="invalid-feedback font-size-sm">{{ errors.bank_id[0] }}</div>
                        </div>

                        <b-form-group label="Status" v-slot="{ ariaDescribedby }">
                            <b-form-radio-group id="field-state" v-model="form.status" :aria-describedby="ariaDescribedby" name="status">
                                <b-form-radio value="1">Aktif</b-form-radio>
                                <b-form-radio value="0">Nonaktif</b-form-radio>
                            </b-form-radio-group>
                        </b-form-group>
                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <b-button variant="alt-danger" class="mr-1" @click="reset">cancel</b-button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </b-modal>
    </BaseLayout>
</template>

<script>
    import {
        Link
    } from '@inertiajs/inertia-vue';
    import moment from 'moment';
    import _ from 'lodash';

    import BaseLayout from '@/Layouts/Authenticated.vue'
    import vSelect from 'vue-select';


    export default {
        components: {
            BaseLayout,
            Link,
            vSelect
        },
        data() {
            return {
                loading: false,
                selected: [],
                selectAll: false,
                search: this.route().params.search == '' ? '' : this.route().params.search,
                form: {
                    id: null,
                    name: null,
                    type : 'offline',
                    status: 1,
                    bank_id : null
                },
                title: 'Tambah Metode Pembayaran Baru',
                modalShow: false,
                editMode: false,
                groupList : [],
                bankSelected : null,
            }
        },
        mounted() {
            // if(this.bankList){
            //     this.bankList.forEach((val, i) => {
            //         var data;
            //         if(val.child.length){
            //             let opc = [];
            //             val.child.forEach((c, e)=> {
            //                 opc.push({ text: c.name, value: c.id })
            //             });
            //             data = {
            //                 label: val.name,
            //                 options: opc
            //             };
            //         }else{
            //             data = { text: val.name, value: val.id };
            //         }
            //         this.groupList.push(data);
            //     });
            // }
        },
        watch: {
            search: function () {
                this.doSearch()
            },
            form :{
                handler(value){
                    if(value.type){
                        if(value.type == 'offline'){
                            // value.bank_id = null;
                            this.bankSelected = null;
                        }else{
                            // value.name = null;
                        }
                    }
                },
                deep: true
            },
            bankSelected: function (val) {
                // if(val){
                //     this.form.bank_id = val.id;
                //     this.form.name = val.name;
                // }else{
                //     this.form.bank_id = null;
                //     this.form.name = null;
                // }
            },
            selectAll: function(val){
                this.selected = [];
                if(val){
                    this.dataList.data.forEach((value, index) => {
                        this.selected.push(value.id)
                    });
                }
            }
        },
        props: {
            errors: Object,
            dataList: Object,
            bankList : Array,
        },
        methods: {
            reset() {
                this.form = {
                    id: null,
                    name: null,
                    type : 'offline',
                    status: 1,
                    bank_id : null
                };
                this.modalShow = false;
                this.editMode = true;
            },
            openModal() {
                this.editMode = false;
                this.modalShow = true;
            },
            submit: function () {
                this.$swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: '',
                    imageUrl: window._asset + 'media/loading.gif',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
                let form = this.$inertia.form(this.form)
                let url = this.editMode ? this.route("accounting.payment_method.update") : this.route(
                    "accounting.payment_method.store");
                form.post(url, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `Data Berhasil Disimpan!`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false,
                            timer: 1500
                        });
                        this.reset();
                    },
                    onFinish:() => {
                        this.$swal.close();
                    }
                });
            },
            edit: function (data) {
                this.form.id = data.id;
                this.form.name = data.name;
                this.form.type = data.type;
                this.form.status = data.status;
                if(data.type == 'online'){
                    this.form.bank_id = data.bank_id;
                    this.bankSelected = data.bank;
                }
                this.editMode = true;
                this.title = 'Ubah Metode Pembayaran';
                this.modalShow = true;
            },
            destroy: function (data) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Kamu Yakin?',
                    text: `Data yang dihapus tidak bisa dikembalikan!`,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Tidak, Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$inertia.delete(this.route('accounting.payment_method.delete', data.id), {
                            preserveScroll: true
                        })
                        this.reset();
                    }
                })
            },
            format_date(value) {
                if (value) {
                    return moment(String(value)).format('DD MMM YYYY')
                }
            },
            doSearch: _.throttle(function () {
                this.$inertia.get(this.route('accounting.payment_method.index', {
                    search: this.search
                }), {
                    preserveScroll: true,
                })
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
