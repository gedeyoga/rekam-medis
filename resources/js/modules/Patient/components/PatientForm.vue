<template>
    <div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                {{ $route.params.patient != undefined ? 'Edit Pasien' : 'Tambah Pasien' }}
            </h1>
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb bg-light">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">List Pasien</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $route.params.patient != undefined ? 'Edit Pasien' : 'Tambah Pasien' }}
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div
                class="card-header d-flex justify-content-between align-items-center"
            >
                <span>
                    {{ $route.params.patient != undefined ? 'Edit Pasien' : 'Tambah Pasien' }}
                </span>
            </div>
            <div class="card-body">
                <el-form
                    :model="data_patient"
                    :rules="rules"
                    ref="patientForm"
                    label-position="top"
                    v-loading="loading"
                >
                    <el-form-item label="Nama" prop="profile.name" :rules="rules.name">
                        <el-input
                            v-model="data_patient.profile.name"
                            placeholder="Cth: Jhon Doe"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Telepon" prop="profile.phone" :rules="rules.phone">
                        <el-input
                            min="0"
                            type="number"
                            v-model="data_patient.profile.phone"
                            placeholder="Cth: 082393052933"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Jenis Kelamin" prop="profile.gender" :rules="rules.gender">
                         <el-radio-group v-model="data_patient.profile.gender">
                            <el-radio label="laki_laki" border>Laki - Laki</el-radio>
                            <el-radio label="perempuan" border>Perempuan</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <div class="row">
                        <div class="col-md-3">
                            <el-form-item label="Tempat Lahir" prop="profile.place_of_birth" :rules="rules.place_of_birth">
                                <el-input
                                    v-model="data_patient.profile.place_of_birth"
                                    placeholder="Cth: Denpasar"
                                ></el-input>
                            </el-form-item>
                        </div>
                        <div class="col-md-9">
                            <el-form-item label="Tanggal Lahir" prop="profile.date_of_birth" :rules="rules.date_of_birth">
                                <el-date-picker
                                    v-model="data_patient.profile.date_of_birth"
                                    type="date"
                                    format="dd MMMM yyyy"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Pilih tanggal">
                                </el-date-picker>
                            </el-form-item>
                        </div>
                    </div>

                    <el-form-item label="Alamat" prop="profile.address" :rules="rules.address">
                        <el-input
                            v-model="data_patient.profile.address"
                            :rows="2"
                            type="textarea"
                            placeholder="Masukkan alamat.."
                        ></el-input>
                    </el-form-item>

                    <hr>

                    <el-form-item label="Nomor Induk Kependudukan" prop="nik">
                        <el-input
                            min="0"
                            type="number"
                            v-model="data_patient.nik"
                            placeholder="Cth:  3576014403910003"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="Jenis Pengobatan" prop="pengobatan_type">
                         <el-radio-group v-model="data_patient.pengobatan_type">
                            <el-radio label="umum" border>Umum</el-radio>
                            <el-radio label="bpjs" border>BPJS</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item v-if="data_patient.pengobatan_type == 'bpjs'" label="No BPJS" prop="no_bpjs" :rules="rules.no_bpjs">
                        <el-input
                            min="0"
                            type="number"
                            v-model="data_patient.no_bpjs"
                            placeholder="Cth:  3576014403910003"
                        ></el-input>
                    </el-form-item>

                    <el-form-item>
                        <div class="text-center mt-3">
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
                            {{ $route.params.patient ? 'Simpan Pasien' : 'Tambah Pasien' }}
                            </el-button
                            >
                        </div>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data_patient: {
                profile: {
                    name: "",
                    address: "",
                    phone: "",
                    place_of_birth: "",
                    date_of_birth: "",
                    gender: "",
                },
                nik: "",
                pengobatan_type: "",
                no_bpjs: "",
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

        fetchData() {
            axios
                .get(
                    route("api.patient.show", {
                        patient: this.$route.params.patient,
                    })
                )
                .then((response) => {
                    let patient = response.data.data;
                    this.data_patient = { ...this.data_patient, ...patient };
                });
        },

        getRoute() {
            if (typeof this.$route.params.patient != "undefined") {
                return axios.put(route('api.patient.update' , {
                    patient: this.$route.params.patient
                }) , this.data_patient);
            }
            return axios.post(route('api.patient.store') , this.data_patient);
        },
    },

    watch: {

    },

    mounted() {
        if (typeof this.$route.params.patient == "undefined") {
            // this.show_password = true;
        } else {
            this.fetchData();
        }
    },
};
</script>