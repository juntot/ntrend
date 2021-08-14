import Home from './views/Home';
// import Users from './views/Users';
import UserRole from './views/UserRole';
import Posts from './views/Posts';
import Users from './views/ManageUsers';
import JobPosition from './views/JobPosition';
import Department from './views/Department';
import Branch from './views/Branch';
import Company from './views/Company';
import ManageForm from './views/ManageForm';
import ManageUsersForm from './views/ManageUsersForm';
import FormApprover from './views/FormApprover';
import FormApproverByUser from './views/FormApproverByUser';
import ReportByUsers from './views/ReportByUsers';

import AdminFormGroup from './views/AdminFormGroup'
import AdminWitnessSup from './views/AdminWitnessSup'
import AdminPolicy from './views/AdminPolicy'
import Videos from './views/Videos';
import PaySlip from './views/PaySlip';
import Directory from './views/Directory';
import AdminCompanyProfile from './views/AdminCompanyProfile';
import Settings from './views/Settings';
import TermsCondition from './views/TermsCondition';
import PageNotFound from './views/404';
import AdminCalendar from './calendar_view/Admin_Calendar'

export default{

    mode: 'history',
    base: '/ntrends/',
    routes:[
        // graph
        {
            path: '/',
            component: Home,
            name: 'Home',

            // props: {name: this.$root.name}
        },
        // posts
        {
            path: '/post',
            component: Posts,
        },
        // users
        {
            path: '/manage-users',
            component: Users,
        },
        {
            path: '/user-role',
            component: UserRole,
        },
        // jobposition
        {
            path: '/job-position',
            component: JobPosition,
        },
        // department
        {
            path: '/dept',
            component: Department,
        },
        // branch
        {
            path: '/branch',
            component: Branch,
        },
        // company
        {
            path: '/company',
            component: Company,
        },
        // form groups
        {
            path: '/form-group',
            component: AdminFormGroup
        },
        {
            path: '/witness-supplementary',
            component: AdminWitnessSup
        },
        // forms
        {
            path: '/manage-form',
            component: ManageForm,
        },
        {
            path: '/manage-user-form',
            component: ManageUsersForm,
        },
        {
            path: '/form-approver',
            component: FormApprover,
        },
        {
            path: '/form-approver-byuser',
            component: FormApproverByUser,
        },
        {
            path: '/manage-reports',
            component: ReportByUsers,
        },
        // Policy
        {
            path: '/manage-policy',
            component: AdminPolicy,
        },

        // Videos
        {
            path: '/videos',
            component: Videos,
        },

        // PAYSLIP
        {
            path: '/payslip',
            component: PaySlip,
        },

        // DIRECTORY
        {
            path: '/directory',
            component: Directory
        },

        // COMPANY PROFILE
        {
            path: '/manage-comp-profile',
            component: AdminCompanyProfile
        },


        // CALENDAR
        {
            path: '/admin-calendar',
            component: AdminCalendar,
        },

        // SETTINGS
        {
            path: '/settings',
            component: Settings,
        },
        // TERMS AND CONDITIONS
        {
            path: '/terms-conditions',
            component: TermsCondition,
        },
        //404
        {
            path: '*',
            component: PageNotFound,
        },
    ]

};