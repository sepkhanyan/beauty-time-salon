/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const settingsRoutes = {
  path: '/settings',
  component: Layout,
  redirect: 'noredirect',
  name: 'Settings',
  alwaysShow: true,
  meta: {
    roles: ['manager'],
    title: 'settings',
    icon: 'settings',
  },
  children: [
    {
      path: 'information',
      component: () => import('@/views/settings'),
      name: 'Information',
      meta: { title: 'information', auth: 'user' },
      props: { currentPage: 'Information' },
    },
    {
      path: 'services',
      component: () => import('@/views/settings'),
      name: 'Services',
      meta: { title: 'services', auth: 'user'},
      props: { currentPage: 'Services' },
    },
    {
      path: 'employees',
      component: () => import('@/views/settings'),
      name: 'Employees',
      meta: { title: 'employees', auth: 'user'},
      props: { currentPage: 'Employees' },
    },
    {
      path: 'positions',
      component: () => import('@/views/settings'),
      name: 'Positions',
      meta: { title: 'positions', auth: 'user'},
      props: { currentPage: 'Positions' },
    },
    {
      path: 'working-schedule',
      component: () => import('@/views/settings'),
      name: 'WorkingSchedule',
      meta: { title: 'workingSchedule', auth: 'user'},
      props: { currentPage: 'WorkingSchedule' },
    },
    {
      path: 'appointment-journal',
      component: () => import('@/views/settings'),
      name: 'AppointmentJournal',
      meta: { title: 'appointmentJournal', auth: 'user'},
      props: { currentPage: 'AppointmentJournal' },
    },
    {
      path: 'users-roles',
      component: () => import('@/views/settings'),
      name: 'UsersRoles',
      meta: { title: 'usersRoles', auth: 'user'},
      props: { currentPage: 'UsersAndRoles' },
    },
    {
      path: 'resources',
      component: () => import('@/views/settings'),
      name: 'Resources',
      meta: { title: 'resources', auth: 'user'},
      props: { currentPage: 'Resources' },
    },
  ],
};

export default settingsRoutes;
