<template>
    <div class="medical-records">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                {{ $route.params.medicalrecord != undefined ? 'Edit Rekam Medis' : 'Tambah Rekam Medis' }}
            </h1>
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb bg-light">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">List Rekam Medis</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $route.params.medicalrecord != undefined ? 'Edit Rekam Medis' : 'Tambah Rekam Medis' }}
                    </li>
                </ol>
            </nav>
        </div>

        <el-form
            :model="medicalrecord"
            :rules="rules"
            ref="medicalrecordForm"
            label-position="top"
            v-loading="loading"
        >
            <div class="card">
                <div
                    class="card-header d-flex align-items-center"
                >
                    <span>Data Pasien</span>
                    <el-checkbox class="m-0 ml-3" v-model="new_patient" border>Tambah Pasien Baru</el-checkbox>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <el-form-item
                                v-if="!new_patient"
                                :prop="'patient_id'"
                                :label="'Cari Pasien'"
                                :rules="{
                                    required: true,
                                    message: 'Pasien belum dipilih',
                                }"
                            >
                            
                                <el-select 
                                    class="w-100"
                                    v-model="medicalrecord.patient_id" 
                                    remote
                                    placeholder="Cari berdasarkan kode , nik dan nama pasien.."
                                    :loading="loading_patient"
                                    :remote-method="fetchListPatient"
                                    filterable 
                                    clearable>
                                <el-option
                                    v-for="(patient , index) in list_patients"
                                    :value="patient.id"
                                    :label="patient.code + ' - ' + patient.profile.name"
                                    :key="index"
                                    ></el-option
                                >
                                </el-select>
                            </el-form-item>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div
                    class="card-header d-flex align-items-center"
                >
                    <span>Data Rekam Medis</span>
                </div>
                <div class="card-body">
                    <el-upload
                        class="upload-demo"
                        drag
                        action="#"
                        :auto-upload="false"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :on-change="handleChange"
                        list-type="picture"
                        :file-list="medicalrecord.medical_record_files"
                        multiple>
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                    <div class="el-upload__tip" slot="tip">Upload file rekam medis</div>
                    </el-upload>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-bo">
                    <div class="text-center my-3">
                        <el-button
                            v-loading="loading"
                            @click.prevent="$router.back()"
                            >Kembali</el-button
                        >
                        <el-button
                            v-loading="loading"
                            type="primary"
                            @click="onSubmit()"
                        >
                        {{ $route.params.patient ? 'Simpan Rekam Medis' : 'Tambah Rekam Medis' }}
                        </el-button
                        >
                    </div>
                </div>
            </div>
        </el-form>

    </div>
</template>

<script>
export default {
    data() {
        return {
            new_patient: false,
            loading_patient: false,
            list_patients: [],
            medicalrecord: {
                patient_id : null,
                files: [],
                medical_record_files: [],
            },
            loading: false,
            rules: {
                name: [{ required: true, message: "Nama tidak boleh kosong!" }],
                nik: [{ min:16, max: 16, message: "Nik harus 16 karakter!" }],
                address: [{ required: true, message: "Alamat tidak boleh kosong!" }],
                phone: [{ required: true, message: "Telepon tidak boleh kosong!" }],
                place_of_birth: [{ required: true, message: "Tempat lahir tidak boleh kosong!" }],
                date_of_birth: [{ required: true, message: "Tanggal lahir tidak boleh kosong!" }],
                gender: [{ required: true, message: "Jenis Kelamin tidak boleh kosong!" }],
                no_bpjs: [{ required: true, message: "No BPJS tidak boleh kosong!" }],
                pengobatan_type: [{ required: true, message: "Jenis pengobatan tidak boleh kosong!" }],
            },

            list_roles: [],
        };
    },
    methods: {
        onSubmit() {
            this.$refs["patientForm"].validate((valid) => {
                let fields = this.$refs["patientForm"].fields;
                for (let i = 0; i < fields.length; i++) {
                    if (fields[i].validateState == "error") {
                        $(fields[i].$el).find("input").focus();
                        return false;
                    }
                }

                if (valid) {
                    this.$confirm("Apakah anda yakin ?", "Konfirmasi", {
                        confirmButtonText: "Simpan",
                        cancelButtonText: "Batal",
                        type: "warning",
                    }).then((result) => {
                        this.loading = true;

                        this.getRoute()
                            .then((response) => {
                                this.loading = false;
                                this.$notify({
                                    title: "Pemberitahuan",
                                    message: response.data.message,
                                    type: "success",
                                });
                                this.$router.push({ name: "patients.index" });
                            })
                            .catch((error) => {
                                this.loading = false;
                                if(error.response.status === 422) {
                                    let response = error.response.data; 

                                    if(response.errors) {
                                        Object.keys(response.errors).forEach((key) => {
                                            let fields = this.$refs.patientForm.fields;
                                            for (let index = 0; index < fields.length; index++) {
                                                if(fields[index].prop == key) {
                                                    fields[index].validateState = 'error';
                                                    fields[index].validateMessage = response.errors[key][0];
                                                }
                                                
                                            }
                                        })
                                    }


                                }
                            });
                    });
                }
            });
        },

        fetchListPatient(query) {
            axios
                .get(
                    route("api.patient.index") , 
                    {
                        params: {
                            search: query,
                            relations: 'profile',
                        }
                    }
                )
                .then((response) => {
                    this.list_patients = response.data.data;
                });
        },

        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePreview(file) {
            console.log(file);
        },
        handleChange(file, fileList) {
            console.log(fileList);
        }
    },

    watch: {

    },

    mounted() {
        // this.fetchListPatient;
    },
};
</script>

<style>
    .medical-records .upload-demo .el-upload ,  .medical-records .upload-demo  .el-upload .el-upload-dragger {
        width: 100%;
    }
</style>