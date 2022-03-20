<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                <!-- {{ data.ref }} -->
                Proses
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
                <div class="float-right">
                    <button type="button" class="btn btn-secondary btn-sm" v-if="data.status == 0" @click="updateState(1)">
                        <i class="si si-check mr-1"></i>
                        Terima
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" v-if="data.status == 0" @click="updateState(4)">
                        <i class="si si-close mr-1"></i>
                        Tolak
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" v-if="data.status == 1" @click="updateState(2)">
                        <i class="si si-paper-plane mr-1"></i>
                        Transfer
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    
                </div>
            </div>

            <div class="block block-rounded block-shadow">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="h5 mb-0 pt-0">Informasi Anggota</h2>
                            <hr class="border-2x">
                            <div class="row mb-2">
                                <div class="col-4">ID Anggota</div>
                                <div class="col-8 font-w700">: {{ data.anggota_id }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">Nama Anggota</div>
                                <div class="col-8 font-w700">: {{ data.anggota.nama }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">No Ponsel</div>
                                <div class="col-8 font-w700">: +62{{ data.anggota.no_hp }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">Alamat</div>
                                <div class="col-8 font-w700">: +62{{ data.anggota.no_hp }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="h5 mb-0 pt-0">Informasi Pembiayaan</h2>
                            <hr class="border-2x">
                            <div class="row mb-2">
                                <div class="col-5">No Pembiayaan</div>
                                <div class="col-7 font-w700">: {{ data.ref }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5">Tanggal Pengajuan</div>
                                <div class="col-7 font-w700">: {{ data.created_at }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5">Jumlah Pembiayaan</div>
                                <div class="col-7 font-w700">: {{ currency(data.jumlah) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5">Durasi Pembiayaan</div>
                                <div class="col-7 font-w700">: {{ data.durasi }} Bulan</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5">Biaya Admin</div>
                                <div class="col-7 font-w700">: {{ currency(data.admin_fee) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5">Jumlah Bagi Hasil</div>
                                <div class="col-7 font-w700">: {{ currency(data.bagi_hasil) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="data.line.length">
                    <div class="block-content">
                        <h2 class="h5 mb-0 pt-0">Rincian Angsuran</h2>
                        <hr class="border-2x">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Angsuran</th>
                                    <th>Status</th>
                                    <th>Tempo</th>
                                    <th>Jumlah</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(angs, index) in data.line" :key="index">
                                    <td>{{ angs.angsuran_ke }}</td>
                                    <td>0</td>
                                    <td>{{ angs.tgl_tempo }}</td>
                                    <td>{{ currency(parseInt(angs.amount)) }}</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue'
import moment from 'moment';
import axios from 'axios';
export default {
    components: {
        BaseLayout,
    },
    props :{
        data : Object,
    },
    methods :{
        updateState: function(value){
            if(value == 1){
                var title = 'Terima Pengajuan';
                var text = 'Kamu Yakin Terima Pengajuan Pembiayaan ini?';
            }else if(value == 4){
                var title = 'Tolak Pengajuan';
                var text = 'Kamu Yakin Tolak Pengajuan Pembiayaan ini?';
            }else if(value == 2){
                var title = 'Cairkan Dana';
                var text = 'Kamu Yakin Cairkan Dana Pembiayaan ini?';
            }
            let data = { id : this.data.id, status : value}
            this.$swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Tidak!',
                confirmButtonText: 'Ya!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$inertia.post(this.route('pembiayaan.tunai.updateState'), data, {
                            preserveScroll: true 
                        });
                        // axios.post(this.route('pembiayaan.tunai.updateState'),)
                        // .then((res) => {
                            // this.$swal.fire({
                            //     title: 'Berhasil!',
                            //     text: 'Pembiayaan Berhasil Diperbaharui!',
                            //     icon: 'success',
                            //     showCancelButton: false,
                            //     showConfirmButton: false
                            // });
                            // this.data = res.data.data;
                        // })
                        // .catch((error) => {

                        // });
                    }else{
                        this.$swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'error'
                        )
                    }
                })
        }
    }
}
</script>
