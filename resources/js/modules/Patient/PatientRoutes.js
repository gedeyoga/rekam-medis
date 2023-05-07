
import PatientList from "./components/PatientList";
import PatientForm from "./components/PatientForm";

export default [
    {
        path: "/admin/patients/",
        name: "patients.index",
        component: PatientList,
    },
    {
        path: "/admin/patients/create",
        name: "patients.create",
        component: PatientForm,
    },
    {
        path: "/admin/patients/:patient/edit",
        name: "patients.edit",
        component: PatientForm,
    },
];