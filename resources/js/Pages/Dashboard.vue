<template>
    <BaseLayout title="Dashboard">
        <div class="content">
            <div class="row gutters-tiny">
                <!-- Row #1 -->
                <div class="col-md-6 col-xl-3" v-for="(d, i) in overview" :key="i">
                    <div class="block block-shadow-2 block-rounded">
                        <div class="block-content block-content-full text-right">
                            <div class="font-size-sm font-w600 text-uppercase text-muted">{{ d.title }}</div>
                            <div class="font-size-h2 font-w700">
                                <template v-if="i == 0">
                                    {{ d.data }}
                                </template>
                                <template v-else>
                                    {{ currency(d.data) }}
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Row #1 -->
            </div>
            <div class="block block-shadow block-bordered block-rounded">
                <div class="block-content">
                    <chartjs-line :chart-data="chartjsLineBarsRadarData" :options="chartjsOptions"></chartjs-line>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="content-head"></div>
                    <div class="block">
                        <div class="block-content">

                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="block block-rounded block-fx-shadow">
                        <div class="block-header">
                            <h3 class="block-title">Keanggotaan</h3>
                        </div>
                        <div class="block-content p-0">
                            <table class="table table-borderless table-striped table-vcenter">
                                <tbody>
                                    <tr v-for="(k, i) in keanggotaan.data" :key="i">
                                        <td style="width: 32px;">
                                            <i class="fa fa-circle text-warning"></i>
                                        </td>
                                        <td style="width: 140px;">
                                            <strong>Golongan {{ k.golongan }}</strong>
                                        </td>
                                        <td class="d-none d-sm-table-cell" style="width: 160px;">
                                            <b-progress :value="k.jumlah" :max="keanggotaan.total" show-progress animated></b-progress>
                                        </td>
                                        <td class="text-right">
                                            {{ k.jumlah }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="block-content block-content-full bg-body-light text-muted text-center font-w600">
                            Total Anggota ~ {{ keanggotaan.total }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue';
import Chart from 'chart.js'


import ChartjsLine from '@/components/Chartjs/Line'

export default {
    components: {
        BaseLayout,
        ChartjsLine,
    },
    data () {
        return {
            chartjsOptions: {
                maintainAspectRatio: false
            },
            chartjsLineBarsRadarData: {
                labels: this.simpanan.label,
                datasets: [
                    {
                        label: 'Simpanan Wajib',
                        fill: true,
                        backgroundColor: 'rgba(220,220,220,.3)',
                        borderColor: 'rgba(220,220,220,1)',
                        pointBackgroundColor: 'rgba(220,220,220,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(220,220,220,1)',
                        data: this.simpanan.wajib
                    },
                    {
                        label: 'Simpanan Sukarela',
                        fill: true,
                        backgroundColor: 'rgba(171, 227, 125, .3)',
                        borderColor: 'rgba(171, 227, 125, 1)',
                        pointBackgroundColor: 'rgba(171, 227, 125, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(171, 227, 125, 1)',
                        data: this.simpanan.sukarela
                    },
                ]
            },
        }
    },
    setup () {
        Chart.defaults.global.defaultFontColor              = '#999'
        Chart.defaults.global.defaultFontStyle              = '600'
        Chart.defaults.scale.gridLines.color                = "rgba(0,0,0,.05)"
        Chart.defaults.scale.gridLines.zeroLineColor        = "rgba(0,0,0,.1)"
        Chart.defaults.scale.display                        = true
        Chart.defaults.scale.ticks.beginAtZero              = true
        Chart.defaults.global.elements.line.borderWidth     = 2
        Chart.defaults.global.elements.point.radius         = 4
        Chart.defaults.global.elements.point.hoverRadius    = 6
        Chart.defaults.global.tooltips.cornerRadius         = 3
        Chart.defaults.global.legend.labels.boxWidth        = 15
    },
    props :{
        simpanan : Object,
        overview : Object,
        keanggotaan : Object,
    }
}
</script>
