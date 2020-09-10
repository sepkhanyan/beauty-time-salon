<template>
    <div class="components-container">
        <h2>Company Logo</h2>

        <pan-thumb :image="logo" :hoverable="false" />

        <el-button type="primary" icon="upload" style="position: absolute;bottom: 15px;margin-left: 40px;" @click="imagecropperShow = true">
            Change Logo
        </el-button>

        <image-cropper
                v-show="imagecropperShow"
                :key="imagecropperKey"
                :width="300"
                :height="300"
                :url="'/salons/' + userSalon.id + '/logo-upload'"
                field="logo"
                @close="close"
                @crop-upload-success="cropUploadSuccess"
        />
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ImageCropper from '@/components/ImageCropper';
    import PanThumb from '@/components/PanThumb';

    export default {
        name: 'CompanyLogo',
        components: { ImageCropper, PanThumb },
        data() {
            return {
                imagecropperShow: false,
                imagecropperKey: 0,
                logo: '/images/no-image.jpg',
            };
        },
        computed: {
            ...mapGetters([
                'userSalon',
            ]),
        },
        created(){
            this.getLogo();
        },
        methods: {
            getLogo(){
                this.logo = this.userSalon.logo ? this.userSalon.logo : '/images/no-image.jpg';
            },
            cropUploadSuccess(jsonData){
                this.imagecropperShow = false;
                this.imagecropperKey = this.imagecropperKey + 1;
                this.logo = jsonData.logo;
            },
            close() {
                this.imagecropperShow = false;
            },
        },
    };
</script>

<style scoped>
    .logo{
        width: 200px;
        height: 200px;
        border-radius: 50%;
    }
</style>
