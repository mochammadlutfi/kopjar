<template>
    <BaseLayout>
        <div class="content">
            <div class="content-heading pt-0 mb-3">
                Product Category
            </div>
            <b-row>
                <b-col cols="4">
                    <div class="block">
                        <div class="block-content pb-15">
                            <button class="btn btn-primary btn-block add-root-category">Tambah Kategori Induk</button>
                            <button class="btn btn-primary btn-block add-sub-category disabled">Tambah Sub Kategori</button>
                            <div class="mt-10 mb-10">
                                <a href="#" class="collapse-all">Collapse All</a> |
                                <a href="#" class="expand-all">Expand All</a>
                            </div>
                        </div>
                    </div>
                </b-col>
                <b-col cols="8">
                    <form @submit.prevent="submit">
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title" id="form-title">Tambah kategori</h3>
                                <button type="button" class="btn btn-danger float-right mr-5 hide" id="hapus-kategori">
                                    <i class="si si-trash mr-1"></i>
                                    Hapus
                                </button>
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="si si-paper-plane mr-1"></i>
                                    Simpan
                                </button>
                            </div>
                            <div class="block-content">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="field-nama">Nama</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" id="field-nama" name="nama" placeholder="Masukan Nama Kategori..">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="field-nama">Thumbnail</label>
                                    <div class="col-lg-7">
                                        <div class="col-6 pl-0">
                                            <input type="hidden" name="thumbnail" value="">
                                            <div class="btn btn-primary btn-block">
                                                <input type="file" class="file-upload" id="uploadThumb" accept="image/*">
                                                <i class="si si-camera mr-1"></i>Pilih Foto
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </b-col>
            </b-row>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@/Layouts/Authenticated.vue'

export default {
    components: {
        BaseLayout,
    },
}
</script>
