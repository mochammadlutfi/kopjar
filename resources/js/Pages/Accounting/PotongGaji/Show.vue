<template>
    <BaseLayout>
        <div class="content">
            <h2 class="content-heading">Detail Potong Gaji <small>{{ anggota.anggota_id }}</small></h2>
            <div class="block block-rounded block-shadow">
                <div class="block-content">
                    <div>ID Anggota :  <span class="font-w700">{{ anggota.anggota_id }}</span></div>
                    <div>Nama :  <span class="font-w700">{{ anggota.nama }}</span></div>
                    <div>NIP :  <span class="font-w700">{{ anggota.nip }}</span></div>
                    <div>Golongan :  <span class="font-w700">{{ anggota.golongan }}</span></div>
                    <hr class="border-2x">
                    <table class="table table-bordered table-sm">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>No Transaksi</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d, i) in transaksi" :key="i">
                                <td>{{ i+1 }}</td>
                                <td>{{ d.nomor }}</td>
                                <td>{{ d.jenis }}</td>
                                <td>{{ d.tgl }}</td>
                                <td>{{ currency(d.total) }}</td>
                            </tr>
                        </tbody>
                    </table>
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
    props : {
        anggota : Object,
        transaksi : Array,
    }
}
</script>
