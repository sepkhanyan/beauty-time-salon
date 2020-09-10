<template>
    <div>
        <el-tabs v-model="activeName">
            <el-tab-pane label="General" name="general">
                <general />
            </el-tab-pane>

            <el-tab-pane label="Images" name="images">
                <images />
            </el-tab-pane>

            <el-tab-pane label="Branches" name="branches">
                <branches />
            </el-tab-pane>

            <el-tab-pane label="Payment info" name="payment">
                <payment-info />
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
    import General from './general';
    import Images from './images';
    import Branches from './Branches';
    import PaymentInfo from './PaymentInfo';

    export default {
        name: 'Information',
        components: {
            General,
            Images,
            Branches,
            PaymentInfo,
        },
        data() {
            return {
                activeName: 'general',
            };
        },
    };
</script>
