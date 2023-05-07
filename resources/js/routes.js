import Vue from "vue";
import VueRouter from "vue-router";

import UserRoutes from "./modules/User/UserRoutes";
import RoleRoutes from "./modules/Role/RoleRoutes";
import DashboardRoutes from "./modules/Dashboard/DashboardRoutes";
import PatientRoutes from "./modules/Patient/PatientRoutes";
import MedicalRecordRoutes from "./modules/MedicalRecord/MedicalRecordRoutes";

Vue.use(VueRouter);

const routes = [
    ...UserRoutes,
    ...RoleRoutes,
    ...DashboardRoutes,
    ...PatientRoutes,
    ...MedicalRecordRoutes,
];

const router = new VueRouter({
    mode: "history",
    routes: routes,
});

export default router;