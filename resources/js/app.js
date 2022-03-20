import { App, plugin } from '@inertiajs/inertia-vue'
import Vue from 'vue'
// import { InertiaProgress } from '@inertiajs/progress'
import BootstrapVue from 'bootstrap-vue'

// Custom components
import BaseLayoutModifier from '@/components/BaseLayoutModifier'
import BaseBlock from '@/components/BaseBlock'
import BaseBackground from '@/components/BaseBackground'
import BasePageHeading from '@/components/BasePageHeading'
import BaseNavigation from '@/components/BaseNavigation'
import VueCompositionAPI from '@vue/composition-api'
import VueSweetalert2 from 'vue-sweetalert2';

// Register global components
Vue.component(BaseLayoutModifier.name, BaseLayoutModifier)
Vue.component(BaseBlock.name, BaseBlock)
Vue.component(BaseBackground.name, BaseBackground)
Vue.component(BasePageHeading.name, BasePageHeading)
Vue.component(BaseNavigation.name, BaseNavigation)

Vue.use(plugin)
Vue.use(BootstrapVue)
Vue.use(VueCompositionAPI)
Vue.use(VueSweetalert2);
const el = document.getElementById('app');

Vue.mixin({ 
    methods: { 
        route,
        asset(path) {
            var base_path = window._asset || '';
            return base_path + path;
        },
        currency(value){
            if (typeof value !== "number") {
                return value;
             }
             
             var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
             });

             return formatter.format(value);
        },
        toUpperCase(value){
            var str = value.toString();
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase();
            });
        },
        percentage(old_val, new_val){
            if (typeof old_val !== "number" && typeof new_val !== "number") {
                return 0;
            }
            let value = old_val - new_val;
            let percentage = (value / old_val) * 100;
            // if(percentage)
            if(percentage === Number.POSITIVE_INFINITY){
                return '-100%';
            }else if(percentage === Number.NEGATIVE_INFINITY){
                return '100%';
            }else if(isNaN(percentage)){
                return '0%';
            }else if(percentage > 0){
                return +Math.abs(percentage) + '%';
            }else if(percentage < 0){
                return -percentage + '%';
            }
        }
    } 
});
new Vue({
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => {
                let parts = name.split('::')
                let type = false
                if(parts.length > 1){
                    let nameVue = parts[1].split('.')[0]
                    console.log(parts[1]);
                    return require(`../../Modules/${ parts[0] }/Resources/assets/js/Pages/${ nameVue }.vue`).default
                }else{
                    return require(`./Pages/${name}`).default
                }
            },
        },
    }),
}).$mount(el)
