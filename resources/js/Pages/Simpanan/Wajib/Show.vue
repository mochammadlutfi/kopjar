<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Detail Transaksi
                <span class="badge badge-primary" v-if="data.status == 1">Done</span>
                <span class="badge badge-warning" v-else-if="data.status == 0">Pending</span>
                <span class="badge badge-danger" v-else>Cancel</span>
                <div class="float-right">
                    <a href="#" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="si si-printer mr-1"></i> Print
                    </a>
                    <a :href="route('simpanan.wajib.edit', data.id)" class="btn btn-secondary btn-sm">
                        <i class="si si-note mr-1"></i> Edit
                    </a>
                </div>
            </div>
            <div class="block block-rounded block-shadow mb-10">
                <div class="block-content">
                    <div class="row">
                        <div class="mb-4 col-sm-12 col-md-6 col-lg-4">
                            <h5 class="font-weight-bold mb-3">Informasi Anggota</h5>
                            <div>ID Anggota :  {{ data.anggota_id }}</div>
                            <div>Nama :  {{ data.anggota.nama }}</div>
                            <div>{{ data.anggota.phone }}</div>
                        </div>
                        <div class="mb-4 col-sm-12 col-md-6 col-lg-4">
                            <h5 class="font-weight-bold">Informasi Transaksi</h5>
                            <div>No Transaksi :  <span class="font-w700">{{ data.nomor }}</span></div>
                            <div>Tanggal :  <span class="font-w700">{{ data.tgl }}</span></div>
                            <div>Jenis Transaksi : <span class="font-w700">{{ data.jenis_transaksi }}</span></div>
                        </div>
                        <div class="mb-4 col-sm-12 col-md-4 col-lg-4">
                            <h5 class="font-weight-bold">Informasi Pembayaran</h5>
                            <div>Metode :  <span class="font-w700">{{ data.payment.payment_method.name }}</span></div>
                            <div>Status : 
                                <template v-if="data.payment.status == 'paid'">
                                    <span class="badge badge-primary">Lunas</span>
                                </template>
                                <template v-else-if="data.payment.status == 'unpaid'">
                                    <span class="badge badge-danger">Belum Bayar</span>
                                </template>
                                <template v-else>
                                    <span class="badge badge-warning">Cancel</span>
                                </template>
                            </div>
                            <div v-if="data.payment.status == 'paid'">Tanggal : <span class="font-w700">{{ data.payment.tgl_bayar }}</span></div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="block block-rounded block-shadow">
                <div class="block-content p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter mb-0">
                            <thead>
                                <tr>
                                    <th>Program</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, i) in data.line" :key="i">
                                    <td>{{ currency(d.nama) }}</td>
                                    <td>{{ currency(d.jumlah) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <th class="font-w600">Total: </th>
                                    <td>{{ currency(data.total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue'

export default {
    components: {
        BaseLayout,
    },
    props :{
        data : Object,
    }
}
</script>
