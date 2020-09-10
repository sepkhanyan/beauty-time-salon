<template>
  <div class="components-container">
    <h2>Employee Images</h2>

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

    <div v-if="employee.images.length" class="block">
      <el-image
        v-for="image in employee.images"
        :key="image"
        style="width: 200px; height: 200px; border-radius: 5px; margin-right: 5px"
        :src="image"
        fit="fill"
      />
    </div>
  </div>
</template>

<script>
  import EmployeesResource from '@/api/employees';
  const employeesResource = new EmployeesResource();
  import {mapGetters} from 'vuex';

  const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
  export default {
    name: 'EmployeeImages',
    data(){
      return {
        fileList: [],
        requestImages: [],
        images: [],
        loading: false,
      };
    },
    computed: {
      ...mapGetters({
        employee: 'employee',
      }),
    },
    created(){
      this.getImages();
    },
    methods: {
      getImages(){
        this.$store.dispatch('employee/get', this.$route.params.id);
        this.images = this.employee.images.length ? this.employee.images : [];
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
        employeesResource.imagesUpload(this.employee.id, {images: this.requestImages}).then((response) => {
          this.employee.images = response.data.images;
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

