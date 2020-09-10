<template>
    <div class="components-container">
        <h2>Company Images</h2>

        <el-upload
                action=""
                list-type="picture-card"
                :file-list="fileList"
                :on-remove="handleRemove"
                :before-upload="beforeUpload"
                :http-request="handleUpload"
                multiple
        >
            <i class="el-icon-plus"/>
        </el-upload>

        <el-button style="margin: 10px 0" type="primary" :loading="loading" @click="uploadImages">Upload</el-button>

        <div v-if="images.length" class="block">
            <el-image
                    v-for="image in images"
                    :key="image"
                    style="width: 200px; height: 200px; border-radius: 5px; margin-right: 5px"
                    :src="image"
                    fit="fill"
            />
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import SalonResource from '@/api/salon';

    const toBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
    const salonResource = new SalonResource();
    export default {
        name: 'CompanyImages',
        data(){
            return {
                fileList: [],
                requestImages: [],
                images: [],
                loading: false,
            };
        },
        computed: {
            ...mapGetters([
                'userSalon',
            ]),
        },
        created(){
            this.getImages();
        },
        methods: {
            getImages(){
                this.images = this.userSalon.images.length ? this.userSalon.images : [];
            },
            beforeUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('Avatar picture must be JPG or PNG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }
                return isJPG && isLt2M;
            },
            async handleUpload(file){
                this.requestImages.push(await toBase64(file.file));
            },
           uploadImages(){
               this.loading = true;
               salonResource.imagesUpload(this.userSalon.id, {images: this.requestImages}).then((response) => {
                   this.loading = false;
                   this.fileList = [];
                   this.requestImages = [];
                   this.images = response.data.images;
               });
            },
            handleRemove(file){
                if (file.status === 'success'){
                    const index = this.requestImages.indexOf(file);
                    this.requestImages.splice(index, 1);
                }
            },
        },
    };
</script>

