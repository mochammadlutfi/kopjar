<template>
    <BaseLayout>
        <div class="content">
            <h2 class="content-heading">Report Akumulasi Simpanan</h2>
            <div class="block">
                <div class="block-content">
                    <h3>Report Akumulasi Simpanan</h3>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th>Tahun Lalu</th>
                                <th>Tahun Ini</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d, i) in data" :key="i">
                                <td>{{ d["program"] }}</td>
                                <td>{{ currency(d["old"]) }}</td>
                                <td>{{ currency(d["now"]) }}</td>
                                <td>{{ percentage(5000000, d["now"]) }}</td>
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
        data : Array
    }
}
</script>
