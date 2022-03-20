<template>
    <!-- Sidebar -->
    <nav id="sidebar" aria-label="Main Navigation">
        <slot>
            <!-- Sidebar Content -->
            <div class="sidebar-content">
                <!-- Side Header -->
                <div class="content-header content-header-fullrow px-15">
                    <!-- Mini Mode -->
                    <div class="content-header-section sidebar-mini-visible-b">
                        <!-- Logo -->
                        <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                            <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                        </span>
                        <!-- END Logo -->
                    </div>
                    <!-- END Mini Mode -->

                    <!-- Normal Mode -->
                    <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                        <!-- Close Sidebar, Visible only on mobile screens -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r"
                            data-toggle="layout" data-action="sidebar_close">
                            <i class="fa fa-times text-danger"></i>
                        </button>
                        <!-- END Close Sidebar -->

                        <!-- Logo -->
                        <div class="content-header-item">
                            <a :href="route('dashboard')">
                                <img :src="asset('media/logo/logo.png')" width="180px">
                            </a>
                        </div>
                        <!-- END Logo -->
                    </div>
                    <!-- END Normal Mode -->
                </div>
                <!-- END Side Header -->


                <!-- Sidebar Scrolling -->
                <simplebar class="js-sidebar-scroll">
                    <!-- Side Navigation -->
                    <div class="content-side">
                        <base-navigation :nodes="$page.props.menu"></base-navigation>
                    </div>
                    <!-- END Side Navigation -->
                </simplebar>
                <!-- END Sidebar Scrolling -->
            </div>
            <!-- Sidebar Content -->
        </slot>
    </nav>
    <!-- END Sidebar -->
</template>

<script>
    // SimpleBar, for more info and examples you can check out https://github.com/Grsmto/simplebar/tree/master/packages/simplebar-vue
    import simplebar from 'simplebar-vue'

    // Get navigation data
    import menuList from '@/data/menu'

    export default {
        name: 'BaseSidebar',
        props: {
            classes: String
        },
        components: {
            simplebar
        },
        data() {
            return {
                // Get main navigation
                navigation: menuList.main
            }
        }
    }

</script>
