
import MedicalRecordList from "./components/MedicalRecordList";
import MedicalRecordForm from "./components/MedicalRecordForm";

export default [
    {
        path: "/admin/medicalrecords/",
        name: "medicalrecords.index",
        component: MedicalRecordList,
    },
    {
        path: "/admin/medicalrecords/create",
        name: "medicalrecords.create",
        component: MedicalRecordForm,
    },
    {
        path: "/admin/medicalrecords/:medicalrecord/edit",
        name: "medicalrecords.edit",
        component: MedicalRecordForm,
    },
];