<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Detail Transaksi Kas
                <!-- <span class="badge badge-primary" v-if="data.status == 1">Done</span>
                <span class="badge badge-warning" v-else-if="data.status == 0">Pending</span>
                <span class="badge badge-danger" v-else>Cancel</span>
                <div class="float-right">
                    <a href="#" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="si si-printer mr-1"></i> Print
                    </a>
                    <a :href="route('simpanan.sukarela.edit', data.id)" class="btn btn-secondary btn-sm">
                        <i class="si si-note mr-1"></i> Edit
                    </a>
                </div> -->
            </div>
            <div class="block block-rounded block-shadow mb-10">
                <div class="block-content">
                    <div class="row">
                        <div class="mb-4 col-sm-12 col-md-6 col-lg-6">
                            <div class="row gutter-tiny">
                                <div class="col-4">Nomor</div>
                                <div class="col-8">: <span class="font-w700">{{ data.nomor }}</span></div>
                            </div>
                            <div class="row gutter-tiny">
                                <div class="col-4">Jenis Transaksi</div>
                                <div class="col-8">: <span class="font-w700">{{ (data.type == 'inbound') ? 'Kas Masuk' : 'Kas Keluar' }}</span></div>
                            </div>
                            <div class="row gutter-tiny">
                                <div class="col-4">Keterangan</div>
                                <div class="col-8">: <span class="font-w700">{{ data.note }}</span></div>
                            </div>
                        </div>
                        <div class="mb-4 col-sm-12 col-md-6 col-lg-6">
                            <div class="row gutter-tiny">
                                <div class="col-4">Tanggal</div>
                                <div class="col-8">: <span class="font-w700">{{ format_date(data.tgl) }}</span></div>
                            </div>
                            <div class="row gutter-tiny">
                                <div class="col-4">Kas</div>
                                <div class="col-8">: <span class="font-w700">{{ data.payment.payment_method.name }}</span></div>
                            </div>
                            <div class="row gutter-tiny">
                                <div class="col-4">Ref</div>
                                <div class="col-8">: <span class="font-w700">{{ data.ref }}</span></div>
                            </div>
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
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, i) in data.line" :key="i">
                                    <td>{{ currency(d.journal.name) }}</td>
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
import BaseLayout from '@/Layouts/Authenticated.vue';
import moment from 'moment';

export default {
    components: {
        BaseLayout,
    },
    props :{
        data : Object,
    },
    methods : {
        format_date(value){
            if (value) {
                moment.locale('id');
                return moment(String(value)).format('dddd, DD MMMM YYYY')
            }
        },
    }
}
</script>
