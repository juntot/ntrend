import Home from './views/public/Home';
import Supplementary from './views/public/Supplementary';
import LeaveForm from './views/public/LeaveForm';
import UndertimeRequest from './views/public/UndertimeRequest';
import OvertimeRequest from './views/public/OvertimeRequest';
import SalaryDiscrepancy from './views/public/SalaryDiscrepancy';
import Offset from './views/public/Offset';
import IncidentReport from './views/public/IncidentReport';
import CompanyLoan from './views/public/CompanyLoan';
import ClearanceForm from './views/public/ClearanceForm';
import CallingCardRequest from './views/public/CallingCardRequest';
import LaptopAgreement from './views/public/LaptopAgreement';
import WorkRequest from './views/public/WorkRequest';
import FinancialAdvantage from './views/public/FinancialAdvantage';
import Canvas from './views/public/Canvas';
import MIIS from './views/public/MIIS';
import PRF from './views/public/PRF';
import TravelForm from './views/public/TravelForm';
import UrgentCheck from './views/public/UrgentCheck';
import SupplierAccreditation from './views/public/SupplierAccreditation';
import PRS from './views/public/PRS';
import OverrideForm from './views/public/OverrideForm';

// FOR FORM APPROVERS
import ApproverLeaveForm from './views/approver/ApproverLeaveForm';
import ApproverUndertimeRequest from './views/approver/ApproverUndertimeRequest';
import ApproverOvertimeRequest from './views/approver/ApproverOvertimeRequest';
import ApproverSalaryDiscrepancy from './views/approver/ApproverSalaryDiscrepancy';
import ApproverSupplementary from './views/approver/ApproverSupplementary';
import ApproverSupplementaryWitness from './views/approver/ApproverSupplementaryWitness';

import ApproverOffset from './views/approver/ApproverOffset';
import ApproverIncidentReport from './views/approver/ApproverIncidentReport';
import ApproverCompanyLoan from './views/approver/ApproverCompanyLoan';
// import ApproverClearanceForm from './views/approver/ApproverClearanceForm';
import ApproverCallingCardRequest from './views/approver/ApproverCallingCardRequest';
import ApproverLaptopAgreement from './views/approver/ApproverLaptopAgreement';
import ApproverWorkRequest from './views/approver/ApproverWorkRequest';
import ApproverFinancialAdvantage from './views/approver/ApproverFinancialAdvantage';
import ApproverCanvas from './views/approver/ApproverCanvas';
import ApproverMIIS from './views/approver/ApproverMIIS';
import ApproverPRF from './views/approver/ApproverPRF';
import ApproverTravelForm from './views/approver/ApproverTravelForm';
import ApproverUrgentCheck from './views/approver/ApproverUrgentCheck';
import ApproverSupplierAccreditation from './views/approver/ApproverSupplierAccreditation';
import ApproverPRS from './views/Approver/ApproverPRS';
import ApproverOverrideForm from './views/Approver/ApproverOverrideForm';

// REPORTS
import Reports from './views/public/Reports'

// ADMINISTRATIVE
import Users from './views/ManageUsers';
import JobPosition from './views/JobPosition';
import Department from './views/Department';
import Branch from './views/Branch';
import AdminPaySlip from './views/PaySlip';
// END

import Policy from './views/public/Policy';
import PaySlip from './views/public/PaySlip';
import Directory from './views/Directory';
import Videos from './views/Videos';
import CompanyProfile from './views/public/CompanyProfile';
import CompanyProfileMarketing from './views/public/CompanyProfileMarketing';
import CompanyProfileOperation from './views/public/CompanyProfileOperation';
import PageNotFound from './views/404';

// route key name will be use to display from name this.$route.name
export default{

    mode: 'history',
    base: '/ntrends/',
    routes:[
        {
            path: '/',
            component: Home,
            name: 'Home',
            // props: {name: this.$root.name}
        },
        // Supplementary
        {
            path: '/supplementary',
            component: Supplementary,
            name: 'Supplementary',
            // props: {name: this.$root.name}
        },
        // Leave Form
        {
            path: '/leave-form',
            component: LeaveForm,
            name: 'Leave Form',
        },
        // Undertime Request
        {
            path: '/undertime-request',
            component: UndertimeRequest,
            name: 'undertime request',
        },
        // Undertime Request
        {
            path: '/overtime-request',
            component: OvertimeRequest,
            name: 'overtime request',
        },
        // Salary Discrepancy
        {
            path: '/salary-discrepancy',
            component: SalaryDiscrepancy,
            name: 'Salary Discrepancy',
        },
        // Offset
        {
            path: '/offset',
            component: Offset,
            name: 'Offset',
        },
        // Incident Report
        {
            path: '/incident-report',
            component: IncidentReport,
            name: 'Incident Report',
        },
        // Company Loan
        {
            path: '/company-loan',
            component: CompanyLoan,
            name: 'Company Loan',
        },
        // Clearance Form
        {
            path: '/clearance-form',
            component: ClearanceForm,
            name: 'Clearance Form',
        },
        // Calling Card Request
        {
            path: '/calling-card-request',
            component: CallingCardRequest,
            name: 'calling Card Request',
        },
        // Laptop Form
        {
            path: '/laptop-form',
            component: LaptopAgreement,
            name: 'Laptop Form',
        },
        // Work Request
        {
            path: '/work-request',
            component: WorkRequest,
            name: 'Work Request',
        },
        // Financial Advantage
        {
            path: '/financial-advance',
            component: FinancialAdvantage,
            name: 'Financial Advantage',
        },
        // Canvas
        {
            path: '/canvas',
            component: Canvas,
            name: 'Canvas',
        },
        // MIIS
        {
            path: '/miis',
            component: MIIS,
            name: 'Marketing Information Sheet(MIIS)',
        },
        // PRF
        {
            path: '/prf',
            component: PRF,
            name: 'Purchase Requesition Form',
        },
        // Travel Form
        {
            path: '/travel-form',
            component: TravelForm,
            name: 'Travel Form',
        },
        // Urgent Check
        {
            path: '/urgent-check',
            component: UrgentCheck,
            name: 'Urgent Check',
        },
        // Supplier Acrreditation
        {
            path: '/supplier-accreditation',
            component: SupplierAccreditation,
            name: 'Supplier Accreditation',
        },
        // PRS
        {
            path: '/prs',
            component: PRS,
            name: 'Purchase Receiving Slip',
        },
        // Overtime Form
        {
            path: '/override-form',
            component: OverrideForm,
            name: 'Override Request Form'
        },

        // ==================================  FOR FORM APPROVERS  ==================================

        // Leave Form
        {
            path: '/approval-leave-form',
            component: ApproverLeaveForm,
            name: 'Approval Leave Form',
        },
        // Supplementary
        // witness supp
        {
            path: '/supplementary-witness',
            component:  ApproverSupplementaryWitness,
        },
        // approver supp
        {
            path: '/approval-supplementary',
            component: ApproverSupplementary,
            name: 'Approval Supplementary',
            // props: {name: this.$root.name}
        },
        // Undertime Request
        {
            path: '/approval-undertime-request',
            component: ApproverUndertimeRequest,
            name: 'Approval undertime request',
        },
        // Overtime Request
        {
            path: '/approval-overtime-request',
            component: ApproverOvertimeRequest,
            name: 'Approval overtime request',
        },
        // Salary Discrepancy
        {
            path: '/approval-salary-discrepancy',
            component: ApproverSalaryDiscrepancy,
            name: 'Approval Salary Discrepancy',
        },
        // Offset
        {
            path: '/approval-offset',
            component: ApproverOffset,
            name: 'Approval Offset',
        },
        // Incident Report
        {
            path: '/approval-incident-report',
            component: ApproverIncidentReport,
            name: 'Approval Incident Report',
        },
        // Company Loan
        {
            path: '/approval-company-loan',
            component: ApproverCompanyLoan,
            name: 'Approval Company Loan',
        },
        // // Clearance Form
        // {
        //     path: '/clearance-form',
        //     component: ClearanceForm,
        //     name: 'Clearance Form',
        // },
        // Calling Card Request
        {
            path: '/approval-calling-card-request',
            component: ApproverCallingCardRequest,
            name: 'Approver calling Card Request',
        },
        // Laptop Agreement
        {
            path: '/approval-laptop-form',
            component: ApproverLaptopAgreement,
            name: 'Approver Laptop Agreement',
        },
        // Work Request
        {
            path: '/approval-work-request',
            component: ApproverWorkRequest,
            name: 'Approver Work Request',
        },
        // Financial Advantage
        {
            path: '/approval-financial-advance',
            component: ApproverFinancialAdvantage,
            name: 'Approver Financial Advantage',
        },
        // Canvas
        {
            path: '/approval-canvas',
            component: ApproverCanvas,
            name: 'Approver Canvas',
        },
        // MIIS
        {
            path: '/approval-miis',
            component: ApproverMIIS,
            name: 'Approver Marketing Information Sheet(MIIS)',
        },
        // PRF
        {
            path: '/approval-prf',
            component: ApproverPRF,
            name: 'Approval Purchase Requesition Form',
        },
        // Travel Form
        {
            path: '/approval-travel-form',
            component: ApproverTravelForm,
            name: 'ApproverTravel Form',
        },
        // Urgent Check
        {
            path: '/approval-urgent-check',
            component: ApproverUrgentCheck,
            name: 'Approver Urgent Check',
        },
        // Supplier Acrreditation
        {
            path: '/approval-supplier-accreditation',
            component: ApproverSupplierAccreditation,
            name: 'Approver Supplier Accreditation',
        },
        // PRS
        {
            path: '/approval-prs',
            component: ApproverPRS,
            name: 'Approval Purchase Receiving Slip',
        },
        // Overtime Form
        {
            path: '/approval-override-form',
            component: ApproverOverrideForm,
            name: 'Approval Override Request Form'
        },
        // ============================================ REPORTS =====================================================
        {
            path: '/reports',
            component: Reports
        },

        // ===================================== ADMINISTRATIVE COMPONENTS ==========================================
        {
            path: '/manage-users',
            component: Users,
        },
        // jobposition
        {
            path: '/manage-position',
            component: JobPosition,
        },
        // department
        {
            path: '/manage-department',
            component: Department,
        },
        // branch
        {
            path: '/manage-branch',
            component: Branch,
        },
        // PAYSLIP ADMIN
        {
            path: '/admin-payslip',
            component: AdminPaySlip,
        },
        // POLICY
        {
            path: '/policy/:policy_id',
            component: Policy,
        },
        // PAYSLIP
        {
            path: '/payslip',
            component: PaySlip
        },
        // DIRECTORY
        {
            path: '/directory',
            component: Directory
        },
        // Videos
        {
            path: '/videos',
            component: Videos,
        },
        // Company Profile
        // {
        //     path: '/company-profile',
        //     component: CompanyProfile,
        // },
        {
            path: '/company-profile/:comp_prof_id',
            component: CompanyProfile,
        },
        // Company Profile Operation
        {
            path: '/company-profile-operation',
            component: CompanyProfileOperation,
        },

        // Company Profile Marketing
        {
            path: '/company-profile-marketing',
            component: CompanyProfileMarketing,
        },

        // Terms and conditions
        // {
        //     path: '/terms-and-conditions',
        //     component: PageNotFound,
        // },

        //404
        {
            path: '*',
            component: PageNotFound,
        },

    ]

};

