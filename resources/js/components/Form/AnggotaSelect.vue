<template>
    <div>
        <v-select label="nama" :filterable="false" :options="options" @search="onSearch" v-bind:class="{'is-invalid' : error }"   v-model="anggotaSelected">
            <template slot="no-options">
                <div class="p-5 text-left">
                    Cari Anggota
                </div>
            </template>
            <template slot="option" slot-scope="option">
                <div class="d-center">
                    {{ option.anggota_id }} - {{ option.nama }}
                    </div>
                </template>
                <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                    {{ option.anggota_id }} - {{ option.nama }}
                </div>
            </template>
        </v-select>
        <div class="invalid-feedback" v-if="error">{{ error[0] }}</div>
    </div>
</template>
<script>

import vSelect from 'vue-select';
import _ from 'lodash';
export default {
    name : 'AnggotaSelectDropdown',
    components: {
        vSelect
    },
    data(){
        return {
            options : [],
            anggotaSelected : null,
            selected : null,
        }
    },
    watch : {
        anggotaSelected(value) {
            if(value){
                this.$emit('done', value)
            }else{
                this.$emit('done', null)
            }
        },
    },
    props : ['error', 'data'],
    mounted(){
        let vm = this;
        vm.$nextTick(function () {
            if(vm.data){
                vm.anggotaSelected = vm.data;
            }
        });
    },
    methods :{
        onSearch(search, loading) {
            if(search.length) {
                loading(true);
                this.search(loading, search, this);
            }
        },
        search: _.debounce((loading, search, vm) => {
            fetch(vm.route('anggota.data') +`?q=${escape(search)}`).then(res => {
                res.json().then(json => (vm.options = json));
                loading(false);
            });
        }, 350),
    },
}
</script>