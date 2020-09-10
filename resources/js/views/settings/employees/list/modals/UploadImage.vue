<template>
  <div class="components-container">

    <el-button type="primary" icon="upload" style="position: absolute;bottom: -5px;right:120px"
               @click="imagecropperShow=true">
      Upload Image
    </el-button>

    <image-cropper
      v-show="imagecropperShow"
      :key="imagecropperKey"
      :width="300"
      :height="300"
      url="'/employees/' + id + '/image-upload'"
      lang-type="en"
      @close="close"
      @crop-upload-success="cropSuccess"
    />
  </div>
</template>

<script>
  import ImageCropper from '@/components/ImageCropper';

  export default {
    name: 'UploadImage',
    components: {ImageCropper},
    inject: ['$employeesList'],
    data() {
      return {
        imagecropperShow: false,
        imagecropperKey: 0,
        image: 'https://eclectickoifish.files.wordpress.com/2015/01/7826_one_piece.jpg',
      };
    },
    created() {
      this.$employeesList.$on('show-image-upload-dialog', this.showDialog);
    },
    methods: {
      showDialog() {
        imagecropperShow = true;
      },
      cropSuccess(resData) {
        this.imagecropperShow = false;
        this.imagecropperKey = this.imagecropperKey + 1;
        this.image = resData.files.avatar;
      },
      close() {
        this.imagecropperShow = false;
      },
    },
  };
</script>

<style scoped>
  .avatar {
    width: 200px;
    height: 200px;
    border-radius: 50%;
  }
</style>
