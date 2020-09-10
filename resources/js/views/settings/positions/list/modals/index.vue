<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
    <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px"
             style="width: 400px; margin-left:50px;">
      <el-form-item :label="$t('table.title')" prop="title">
        <el-input v-model="temp.title"/>
      </el-form-item>
      <el-form-item :label="$t('table.description')">
        <el-input v-model="temp.description" :autosize="{ minRows: 2, maxRows: 4}" type="textarea"
                  placeholder="Please input"/>
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">
        {{ $t('table.cancel') }}
      </el-button>
      <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
        {{ $t('table.confirm') }}
      </el-button>
    </div>
  </el-dialog>
</template>

<script type="text/babel">
  import PositionsResource from '@/api/positions';

  const positionsResource = new PositionsResource();

  export default {
        name: 'Modals',
    inject: ['$positionsList'],
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
            description: null,
            title: null,
          },
          rules: {
            title: [{required: true, message: 'title is required', trigger: 'blur'}],
          },
        };
      },
    created() {
      this.$positionsList.$on('show-dialog', this.handleCreate);
      this.$positionsList.$on('create-position', this.handleCreate);
      this.$positionsList.$on('show-update-dialog', this.handleUpdate);
      // this.$positionsList.$on('change-updated', this.changeUpdated);
    },
    methods: {
      handleCreate() {
        this.resetTemp();
        this.dialogStatus = 'create';
        this.dialogFormVisible = true;
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate();
        });
      },
      createData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            this.temp.id = parseInt(Math.random() * 100) + 1024; // mock a id
            positionsResource.store(this.temp).then((response) => {
              console.log(response.data);
              this.$positionsList.$emit('update-positions-list', response.data);
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
            console.log(valid);
            const tempData = Object.assign({}, this.temp);
            positionsResource.update(tempData.id, tempData).then(() => {
              this.$positionsList.$emit('show-updated-position', this.temp);
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
        this.$positionsList.$emit('change_updated_data', index);
      },
      handleUpdate(row) {
        this.temp = Object.assign({}, row); // copy obj
        this.temp.timestamp = new Date(this.temp.timestamp);
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
