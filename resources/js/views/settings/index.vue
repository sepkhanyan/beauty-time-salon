<template>
    <div class="app-container">
        <component :is="currentPage"/>
    </div>
</template>

<script>
    import Information from './information';
    import Services from './categories/list/index';
    import Employees from './employees';
    import Positions from './positions/list/index';
    import WorkingSchedule from './working-schedule';
    import AppointmentJournal from './appointment-journal';
    import UsersAndRoles from './users-and-roles';
    import Resources from './resources';

    export default {
        name: 'Settings',
        components: {
            Information,
            Services,
            Employees,
            Positions,
            WorkingSchedule,
            AppointmentJournal,
            UsersAndRoles,
            Resources,
        },
        props: {
            currentPage: {
                type: String,
                required: true,
            },
        },
    };
</script>
