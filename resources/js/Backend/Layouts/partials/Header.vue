<template>
    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="content-header-section">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout"
                    data-action="sidebar_toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->

                <!-- Open Search Section -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout"
                    data-action="header_search_on">
                    <i class="fa fa-search"></i>
                </button>
                <!-- END Open Search Section -->

                <!-- Layout Options (used just for demonstration) -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-circle btn-dual-secondary" id="page-header-options-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu min-width-300" aria-labelledby="page-header-options-dropdown">
                        <h5 class="h6 text-center py-10 mb-10 border-b text-uppercase">Settings</h5>
                        <h6 class="dropdown-header">Color Themes</h6>
                        <div class="row no-gutters text-center mb-5">
                            <div class="col-2 mb-5">
                                <a class="text-default" data-toggle="theme" data-theme="default"
                                    href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                            <div class="col-2 mb-5">
                                <a class="text-elegance" data-toggle="theme"
                                    data-theme="assets/css/themes/elegance.min.css" href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                            <div class="col-2 mb-5">
                                <a class="text-pulse" data-toggle="theme" data-theme="assets/css/themes/pulse.min.css"
                                    href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                            <div class="col-2 mb-5">
                                <a class="text-flat" data-toggle="theme" data-theme="assets/css/themes/flat.min.css"
                                    href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                            <div class="col-2 mb-5">
                                <a class="text-corporate" data-toggle="theme"
                                    data-theme="assets/css/themes/corporate.min.css" href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                            <div class="col-2 mb-5">
                                <a class="text-earth" data-toggle="theme" data-theme="assets/css/themes/earth.min.css"
                                    href="javascript:void(0)">
                                    <i class="fa fa-2x fa-circle"></i>
                                </a>
                            </div>
                        </div>
                        <h6 class="dropdown-header">Header</h6>
                        <div class="row gutters-tiny text-center mb-5">
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-block btn-alt-secondary"
                                    data-toggle="layout" data-action="header_fixed_toggle">Fixed Mode</button>
                            </div>
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-sm btn-block btn-alt-secondary d-none d-lg-block mb-10"
                                    data-toggle="layout" data-action="header_style_classic">Classic Style</button>
                            </div>
                        </div>
                        <h6 class="dropdown-header">Sidebar</h6>
                        <div class="row gutters-tiny text-center mb-5">
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10"
                                    data-toggle="layout" data-action="sidebar_style_inverse_off">Light</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10"
                                    data-toggle="layout" data-action="sidebar_style_inverse_on">Dark</button>
                            </div>
                        </div>
                        <div class="d-none d-xl-block">
                            <h6 class="dropdown-header">Main Content</h6>
                            <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10"
                                data-toggle="layout" data-action="content_layout_toggle">Toggle Layout</button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="row gutters-tiny text-center">
                            <div class="col-6">
                                <a class="dropdown-item mb-0" href="be_layout_api.html">
                                    <i class="si si-chemistry mr-5"></i> Layout API
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="dropdown-item mb-0" href="be_ui_color_themes.html">
                                    <i class="fa fa-paint-brush mr-5"></i> Color Themes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Layout Options -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="content-header-section">
                
                <b-dropdown variant="dual link" class="d-inline-block ml-2" menu-class="p-0 border-0 dropdown-menu-md" right no-caret ref="oneDropdownDefaultUser">
                    <template #button-content>
                    <div class="d-flex align-items-center">
                        <span class="d-none d-sm-inline-block ml-2">{{ $page.props.auth.user.name }}</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                    </div>
                    </template>
                    <div class="p-3 text-center bg-primary-dark rounded-top">
                        <p class="mt-2 mb-0 text-white font-w500">{{ $page.props.auth.user.name }}</p>
                        <p class="mb-0 text-white-50 font-size-sm">Web Developer</p>
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                        <span class="font-size-sm font-w500">Inbox</span>
                        <span class="badge badge-pill badge-primary ml-2">3</span>
                        </a>
                        <Link class="dropdown-item d-flex align-items-center justify-content-between" :href="route('user.profile')">
                            <span class="font-size-sm font-w500">Profile</span>
                        </Link>
                        <div role="separator" class="dropdown-divider"></div>
                        <Link :href="route('admin.logout')" method="post" as="button" class="dropdown-item d-flex align-items-center justify-content-between">
                            <span class="font-size-sm font-w500">Log Out</span>
                        </Link >
                    </div>
                </b-dropdown>

            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->
    </header>
    <!-- END Header -->
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue';
    export default {
        name: 'BaseHeader',
        components: {
            Link,
        },
        props: {
            user: Object
        },
    }

</script>
