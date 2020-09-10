<template>
    <div class="salon-card">
        <el-card>
            <div class="salon-card-section">
                <div class="salon-card-section-header">
                    <svg-icon icon-class="salon"/>
                    <span>Company name</span>
                </div>

                <div class="salon-card-section-body">
                    {{ userSalon.name }}
                </div>
            </div>

            <div class="user-skills salon-card-section">
                <div class="salon-card-section-header">
                    <svg-icon icon-class="user"/>
                    <span>Director name</span>
                </div>

                <div class="salon-card-section-body">
                    {{ name }}
                </div>
            </div>

            <div class="salon-card-section">
                <div class="salon-card-section-header">
                    <svg-icon icon-class="email"/>
                    <span>Email</span>
                </div>

                <div class="salon-card-section-body">
                    {{ email }}
                </div>
            </div>

            <div class="salon-card-section">
                <div class="salon-card-section-header">
                    <svg-icon icon-class="phone"/>
                    <span>Phone number</span>
                </div>

                <div class="salon-card-section-body">
                    +374 {{ userSalon.phone }}
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'SalonCard',
        computed: {
            ...mapGetters([
                'name',
                'email',
                'userSalon',
            ]),
        },
    };
</script>
<style lang="scss" scoped>

    .text-muted {
        color: #777;
    }

    .salon-card {
        color: #606266;

        span {
            padding-left: 4px;
        }

        .salon-card-section {
            font-size: 14px;
            padding: 15px 0;

            .salon-card-section-header {
                border-bottom: 1px solid #dfe6ec;
                padding-bottom: 10px;
                margin-bottom: 10px;
                font-weight: bold;
            }
        }
    }
</style>
