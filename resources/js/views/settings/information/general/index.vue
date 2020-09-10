<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="6" :xs="24">
                <salon-card />
            </el-col>

            <el-col :span="18" :xs="24">
                <inputs-card />
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import SalonCard from './SalonCard';
    import InputsCard from './InputsCard';

    export default {
        name: 'General',
        components: {
            SalonCard,
            InputsCard,
        },
    };
</script>
