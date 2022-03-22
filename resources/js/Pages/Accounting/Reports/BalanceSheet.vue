<template>
    <BaseLayout>
        <div class="content">
            <h2 class="content-heading">Neraca Saldo</h2>
            <div class="block">
                <div class="block-content">
                    <div class="text-center">
                        <h3 class="h4">Neraca Saldo</h3>
                    </div>
                    <div class="row">
                        
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
    props : {
        data : Array
    }
}
</script>
