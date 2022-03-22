<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Detail Piutang <small>{{ anggota.anggota_id }}</small>
                
                <div class="float-right">
                    <a :href="route('accounting.potong_gaji.pdf', {id : anggota.anggota_id })" class="btn btn-sm btn-secondary" target="_blank">Print PDF</a>
                </div>
            </div>
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
                                <td class="text-center">{{ i+1 }}</td>
                                <td>{{ d.nomor }}</td>
                                <td>{{ getType(d.jenis) }}</td>
                                <td>{{ format_date(d.tgl) }}</td>
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
import moment from 'moment';
export default {
    components: {
        BaseLayout,
    },
    props : {
        anggota : Object,
        transaksi : Array,
    },
    methods : {
        format_date(value){
            if (value) {
                return moment(String(value)).format('DD MMMM YYYY')
            }
        },
        getType(value){
            if(value == 'setoran sukarela'){
                return 'Simpanan Sukarela';
            }else if(value == 'pendaftaran'){
                return 'Pendaftaran';
            }else{
                return 'Simpanan Wajib'
            }

        }
    }
}
</script>
