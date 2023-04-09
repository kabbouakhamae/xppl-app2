
import { createWebHistory, createRouter } from "vue-router";

import Home from "../pages/Home.vue";
import SignIn from "../pages/SignIn.vue";
import SignUp from "../pages/SignUp.vue";
import Employee from "../pages/Employee.vue";
import Permission from "../pages/Permission.vue";
import Annual from "../pages/Annual.vue";
import LeaveInfo from "../pages/LeaveInfo.vue";
import Transport from "../pages/Transport.vue";
import FuelCons from "../pages/FuelCons.vue";
import FuelReport from "../pages/FuelReport.vue";
import FuelLookup from "../pages/FuelLookup.vue";
import Lookup from "../pages/Lookup.vue";
import Roster from "../pages/Roster.vue";
import Fingerscan from "../pages/Fingerscan.vue";
import IncidentRecord from "../pages/IncidentRecord.vue";
import IncidentLookup from "../pages/IncidentLookup.vue";
import IncidentReport from "../pages/IncidentReport.vue";
import Overtime from "../pages/Overtime.vue";
import ProdEntry from "../pages/ProdEntry.vue";
import PR from "../pages/PR.vue";
import SafetyDocument from "../pages/SafetyDocument.vue";
import Treatment from "../pages/ClinicTreatment.vue";
import CliLookup from "../pages/ClinicLookup.vue";
import HQPayment from "../pages/HQPaymentRequisition.vue";

import Macro from "../pages/Macro.vue";





const routes =[
    {
        name: "Home",
        path: "/",
        component: Home
    },
    {
        name: "signin",
        path: "/signin",
        component: SignIn
    },
    {
        name: "signup",
        path: "/signup",
        component: SignUp
    },
    {
        name: "employee",
        path: "/employee",
        component: Employee
    },
    {
        name: "permission",
        path: "/permiss",
        component: Permission
    },
    {
        name: "annual",
        path: "/annual",
        component: Annual
    },
    {
        name: "leaveinfo",
        path: "/leaveinfo",
        component: LeaveInfo
    },
    {
        name: "transport",
        path: "/transport",
        component: Transport
    },
    {
        name: "fuelcons",
        path: "/fuelcons",
        component: FuelCons
    },
    {
        name: "fuelreport",
        path: "/fuelreport",
        component: FuelReport
    },
    {
        name: "fuellookup",
        path: "/fuellookup",
        component: FuelLookup
    },
    {
        name: "lookup",
        path: "/lookup",
        component: Lookup
    },
    {
        name: "roster",
        path: "/roster",
        component: Roster
    },
    {
        name: "macro",
        path: "/macro",
        component: Macro
    },
    {
        name: "fingerscan",
        path: "/fingerscan",
        component: Fingerscan
    },
    {
        name: "increcord",
        path: "/increcord",
        component: IncidentRecord
    },
    {
        name: "inclookup",
        path: "/inclookup",
        component: IncidentLookup
    },
    {
        name: "increport",
        path: "/increport",
        component: IncidentReport
    },
    {
        name: "overtime",
        path: "/overtime",
        component: Overtime
    },
    {
        name: "prodentry",
        path: "/prodentry",
        component: ProdEntry
    },
    {
        name: "pr",
        path: "/pr",
        component: PR
    },
    {
        name: "safdocument",
        path: "/safdocs",
        component: SafetyDocument
    },
    {
        name: "treatment",
        path: "/treatment",
        component: Treatment
    },
    {
        name: "clilookup",
        path: "/clilookup",
        component: CliLookup
    },
    {
        name: "HQ Payment Requisition",
        path: "/hqpayment",
        component: HQPayment
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior(){
        window.scrollTo(0,0);
    }
});


export default router;