<template>
  <div class="container">
    <div class="col-md-4">
      <el-image v-if="employee.image" :src="employee.image" style="width: 200px; height: 200px; border-radius: 5px; margin-left: 200px"
                fit="fill"/>
      <el-image v-else src="/images/no-image.jpg" style="width: 200px; height: 200px; border-radius: 5px; margin-left: 200px"
                fit="fill"/>
    </div>
    <div class="col-md-8">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px"
               style=" margin:50px;width: 800px">
        <el-form-item :label="$t('table.name')" prop="name">
          <el-input v-model="temp.name"/>
        </el-form-item>
        <el-form-item :label="$t('table.phone')" prop="phone_number">
          <el-input v-model="temp.phone_number" maxlength="11" @keydown.native="validateNumber($event)"/>
        </el-form-item>
        <el-form-item :label="$t('table.position')" prop="position_id">
          <el-select v-model="temp.position_id" class="filter-item" placeholder="Please select">
            <el-option v-for="item in positionOptions" :key="item.id" :label="item.title" :value="item.id"/>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('table.image')">
          <el-upload
            action=""
            list-type="picture"
            :file-list="fileList"
            :multiple="false"
            :on-change="rmList"
            :before-upload="beforeUpload"
            :http-request="handleUpload"
            class="image-uploader"
          >
            <el-button type="primary">Select Image</el-button>
          </el-upload>
        </el-form-item>
      </el-form>
      <div slot="footer" align="center" style="width:475px">
        <el-button type="primary" style="margin-left: auto" @click="updateData">
          {{ $t('table.confirm') }}
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
  import EmployeesResource from '@/api/employees';
  import PositionsResource from '@/api/positions';

  const positionsResource = new PositionsResource();
  const employeesResource = new EmployeesResource();
  import {mapGetters} from 'vuex';

  const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
  export default {
    name: 'Information',
    inject: ['$employeeDetails'],
    data() {
      return {
        temp: {
          id: undefined,
          name: '',
          position: {},
          position_id: null,
          phone_number: '',
          image: '',
        },
        image: null,
        rules: {
          name: [{required: true, message: 'Name is required', trigger: 'blur'}],
          position_id: [{required: true, message: 'Position is required', trigger: 'blur'}],
          phone_number: [{required: true, message: 'Phone number is required', trigger: 'blur'}],
        },
        fileList: [],
        positionOptions: [],
      };
    },
    computed: {
      ...mapGetters({
        employee: 'employee',
      }),
    },
    created() {
      this.getEmployeeData();
    },
    methods: {
      rmList(file) {
        this.fileList = [];
        this.fileList.push(file);
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
      async handleUpload(file) {
        this.image = await toBase64(file.file);
      },
      getEmployeeData() {
        this.$store.dispatch('employee/get', this.$route.params.id);
        this.temp = this.employee;
        this.temp.position_id = this.employee.position.id;
        this.temp.image = this.employee.image;
        this.employee.image = this.employee.image ? this.employee.image : '/images/no-image.jpg';
        this.getPositionsData();
      },
      getPositionsData() {
        positionsResource.list()
          .then(response => {
            this.positionOptions = response.data.positions;
          });
      },
      validateNumber(e) {
        if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
          (e.keyCode === 65 && e.ctrlKey === true) ||
          (e.keyCode === 67 && e.ctrlKey === true) ||
          (e.keyCode === 88 && e.ctrlKey === true) ||
          (e.keyCode >= 35 && e.keyCode <= 39)) {
          return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
        }
      },
      updateData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            const tempData = Object.assign({}, this.temp);
            employeesResource.update(tempData.id, tempData).then((response) => {
              if (this.image) {
                employeesResource.imageUpload(tempData.id, {image: this.image}).then(response => {
                  this.image = null;
                  this.fileList = [];
                  this.employee.image = response.data.image;
                });
              }
              this.$notify({
                title: 'Success',
                message: 'Updated successfully',
                type: 'success',
                duration: 2000,
              });
            });
          }
        });
      },
    },
  };
</script>

<style scoped>
  .image-preview {
    width: 200px;
    height: 200px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    margin-left: 50px;
  }
  .image-preview-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
  }

  img {
    width: 100%;
    height: 100%;
  }
</style>
