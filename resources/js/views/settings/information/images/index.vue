<template>
    <div>
        <company-logo />

        <company-images />
    </div>
</template>

<script>
    import CompanyLogo from './CompanyLogo';
    import CompanyImages from './CompanyImages';

    export default {
        name: 'Images',
        components: {
            CompanyLogo,
            CompanyImages,
        },
    };
</script>

