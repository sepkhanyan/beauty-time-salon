<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
    <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="150px"
             style="width: 450px; margin-left:50px;">
      <el-form-item :label="$t('table.name')" prop="name">
        <el-input v-model="temp.name"/>
      </el-form-item>
      <el-form-item :label="$t('table.phone')" prop="phone_number">
        <el-input v-model="temp.phone_number" maxlength="9" @keydown.native="validateNumber($event)"/>
      </el-form-item>
      <el-form-item :label="$t('table.position')" prop="position_id">
        <el-select v-model="temp.position_id" class="filter-item" placeholder="Please select" >
          <el-option v-for="item in positionOptions" :key="item.id" :label="item.title" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item :label="$t('table.image')" >
        <el-upload
          action=""
          list-type="picture"
          :file-list="fileList"
          :on-change="rmList"
          :before-upload="beforeUpload"
          :http-request="handleUpload"
        >
          <el-button type="primary">Select Image</el-button>
        </el-upload>
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="close">
        {{ $t('table.cancel') }}
      </el-button>
      <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
        {{ $t('table.confirm') }}
      </el-button>
    </div>

  </el-dialog>
</template>

<script type="text/babel">
  import EmployeesResource from '@/api/employees';
  // import UploadImage from '@/views/settings/employees/list/modals/UploadImage';
  const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
  const employeesResource = new EmployeesResource();

  export default {
    name: 'EmployeesModals',
    // components: {UploadImage},
    inject: ['$employeesList'],
    data() {
      return {
        dialogStatus: '',
        dialogFormVisible: false,
        textMap: {
          update: 'Edit',
          create: 'Create',
        },
        temp: {
          id: undefined,
          name: null,
          position_id: null,
          position_title: null,
          phone_number: null,
        },
        image: null,
        fileList: [],
        positionOptions: {},
        rules: {
          name: [{required: true, message: 'Name is required', trigger: 'blur'}],
          position_id: [{required: true, message: 'Position is required', trigger: 'blur'}],
          phone_number: [{required: true, message: 'Phone number is required', trigger: 'blur'}],
        },
      };
    },
    created() {
      this.$employeesList.$on('create-employees', this.handleCreate);
      this.$employeesList.$on('show-update-dialog', this.handleUpdate);
      this.$employeesList.$on('change-updated', this.changeUpdated);
      this.$employeesList.$on('get-positions-list', this.getPositions);
    },
    methods: {
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
      close() {
        this.dialogFormVisible = false;
        this.fileList = [];
        this.image = null;
      },
      rmList(file) {
        this.fileList = [];
        this.fileList.push(file);
        console.log(this.fileList);
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
        this.image = await toBase64(file.file);
      },
      uploadImages(){
        this.loading = true;
        // salonResource.imagesUpload(this.userSalon.id, {images: this.requestImages}).then((response) => {
        //   this.loading = false;
        //   this.fileList = [];
        //   this.requestImages = [];
        //   this.images = response.data.images;
        // });
      },
      handleCreate() {
        this.resetTemp();
        this.dialogStatus = 'create';
        this.dialogFormVisible = true;
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate();
        });
      },
      getPositions(data) {
        this.positionOptions = data;
      },
      createData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            this.temp.id = parseInt(Math.random() * 100) + 1024; // mock a id
            employeesResource.store(this.temp).then((response) => {
              this.$employeesList.$emit('update-employees-list', response.data);
              if (this.image){
                employeesResource.imageUpload(response.data.id, {image: this.image});
                this.image = null;
                this.fileList = [];
              }
              this.dialogFormVisible = false;
              this.$notify({
                title: 'Success',
                message: 'Created successfully',
                type: 'success',
                duration: 2000,
              });
            });
          }
        });
      },
      updateData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            const tempData = Object.assign({}, this.temp);
            employeesResource.update(tempData.id, tempData).then((response) => {
              this.$employeesList.$emit('show-updated-employees', response.data);
              if (this.image) {
                employeesResource.imageUpload(tempData.id, {image: this.image});
                this.image = null;
                this.fileList = [];
              }
              this.dialogFormVisible = false;
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
      changeUpdated(index) {
        this.$employeesList.$emit('change_updated_data', index);
      },
      handleUpdate(row) {
        this.temp = Object.assign({}, row); // copy obj
        this.temp.timestamp = new Date(this.temp.timestamp);
        this.temp.position_title = row.position.title;
        this.dialogStatus = 'update';
        this.dialogFormVisible = true;
      },
      resetTemp() {
        this.temp = {
          id: undefined,
          timestamp: new Date(),
          title: '',
          status: 'active',
        };
      },
    },
  };
</script>

<style scoped>

</style>
