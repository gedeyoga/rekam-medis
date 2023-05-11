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
            ref="medicalrecordForm"
            label-position="top"
            v-loading="loading"
        >
            <div class="card mb-3">
                <div
                    class="card-header d-flex align-items-center"
                    :class="$route.params.medicalrecord != undefined ? 'justify-content-between' : ''"
                >
                    <span>Data Pasien</span>
                    <el-button v-if="$route.params.medicalrecord != undefined" @click=" $router.push({
                        name: 'patients.edit',
                        params: {
                            patient: medicalrecord.patient_id,
                        },
                    })" icon="el-icon-edit">Edit Pasien</el-button>
                    <el-checkbox v-else class="m-0 ml-3" v-model="medicalrecord.new_patient" border>Tambah Pasien Baru</el-checkbox>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" v-if="$route.params.medicalrecord != undefined">
                           <table v-if="medicalrecord.patient" class="table table-responsive table-sm">
                                <tbody>
                                    <tr>
                                        <th width="180px">Nama</th>
                                        <td>:</td>
                                        <td>{{ medicalrecord.patient.profile.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>:</td>
                                        <td>{{ medicalrecord.patient.profile.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <td>:</td>
                                        <td>
                                            <span v-if=" medicalrecord.patient.profile.gender == 'laki_laki' ">Laki - Laki</span>
                                            <span v-else>Perempuan</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tempat, Tgl Lahir</th>
                                        <td>:</td>
                                        <td>{{ medicalrecord.patient.profile.place_of_birth + ', ' + medicalrecord.patient.profile.date_of_birth}}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>:</td>
                                        <td>{{ medicalrecord.patient.profile.address }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <td>:</td>
                                        <td>{{ medicalrecord.patient.nik }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe</th>
                                        <td>:</td>
                                        <td><span style="text-transform: capitalize;">{{ medicalrecord.patient.pengobatan_type }}</span></td>
                                    </tr>
                                    <tr v-if="medicalrecord.patient.pengobatan_type == 'bpjs'">
                                        <th>NO BPJS</th>
                                        <td>:</td>
                                        <td>
                                            <span>{{ medicalrecord.patient.no_bpjs }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                           </table>
                           
                        </div>
                        <div class="col-lg-12" v-else>
                            <el-form-item
                                v-if="!medicalrecord.new_patient"
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


                            <div v-else>
                                <el-form-item label="Nama" prop="profile.name" :rules="rules.name">
                                    <el-input
                                        v-model="medicalrecord.profile.name"
                                        placeholder="Cth: Jhon Doe"
                                    ></el-input>
                                </el-form-item>
                                <el-form-item label="Telepon" prop="profile.phone" :rules="rules.phone">
                                    <el-input
                                        min="0"
                                        type="number"
                                        v-model="medicalrecord.profile.phone"
                                        placeholder="Cth: 082393052933"
                                    ></el-input>
                                </el-form-item>
                                <el-form-item label="Jenis Kelamin" prop="profile.gender" :rules="rules.gender">
                                    <el-radio-group v-model="medicalrecord.profile.gender">
                                        <el-radio label="laki_laki" border>Laki - Laki</el-radio>
                                        <el-radio label="perempuan" border>Perempuan</el-radio>
                                    </el-radio-group>
                                </el-form-item>
                                <div class="row">
                                    <div class="col-md-3">
                                        <el-form-item label="Tempat Lahir" prop="profile.place_of_birth" :rules="rules.place_of_birth">
                                            <el-input
                                                v-model="medicalrecord.profile.place_of_birth"
                                                placeholder="Cth: Denpasar"
                                            ></el-input>
                                        </el-form-item>
                                    </div>
                                    <div class="col-md-9">
                                        <el-form-item label="Tanggal Lahir" prop="profile.date_of_birth" :rules="rules.date_of_birth">
                                            <el-date-picker
                                                v-model="medicalrecord.profile.date_of_birth"
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
                                        v-model="medicalrecord.profile.address"
                                        :rows="2"
                                        type="textarea"
                                        placeholder="Masukkan alamat.."
                                    ></el-input>
                                </el-form-item>

                                <hr>

                                <el-form-item label="Nomor Induk Kependudukan" prop="profile.nik" :rules="rules.nik">
                                    <el-input
                                        min="0"
                                        type="number"
                                        v-model="medicalrecord.profile.nik"
                                        placeholder="Cth:  3576014403910003"
                                    ></el-input>
                                </el-form-item>
                                <el-form-item label="Jenis Pengobatan" prop="profile.pengobatan_type" :rules="rules.pengobatan_type">
                                    <el-radio-group v-model="medicalrecord.profile.pengobatan_type" >
                                        <el-radio label="umum" border>Umum</el-radio>
                                        <el-radio label="bpjs" border>BPJS</el-radio>
                                    </el-radio-group>
                                </el-form-item>
                                <el-form-item v-if="medicalrecord.profile.pengobatan_type == 'bpjs'" label="No BPJS" prop="profile.no_bpjs" :rules="rules.no_bpjs">
                                    <el-input
                                        min="0"
                                        type="number"
                                        v-model="medicalrecord.profile.no_bpjs"
                                        placeholder="Cth:  3576014403910003"
                                    ></el-input>
                                </el-form-item>
                            </div>
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
                    <el-form-item label="Tanggal Rekam Medis" prop="tanggal" :rules="rules.tanggal">
                        <el-date-picker
                            v-model="medicalrecord.tanggal"
                            type="date"
                            format="dd MMMM yyyy"
                            value-format="yyyy-MM-dd"
                            placeholder="Pilih tanggal">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item
                        :prop="'medical_files'"
                        :label="'Upload Dokumen'"
                        :rules="{
                            validator: checkUploadedFile,
                            message: 'Dokumen belum di tambahkan',
                        }"
                    >
                        <el-upload
                            class="upload-demo"
                            drag
                            action="#"
                            :auto-upload="false"
                            accept="application/pdf"
                            :on-preview="handlePreview"
                            :on-remove="handleRemove"
                            :on-change="handleChange"
                            :file-list="medicalrecord.medical_record_files"
                            multiple>
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                        <div class="el-upload__tip" slot="tip">*Upload file rekam medis berformat pdf</div>
                        </el-upload>
                    </el-form-item>
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
                        {{ $route.params.medicalrecord ? 'Simpan Rekam Medis' : 'Tambah Rekam Medis' }}
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
            
            loading_patient: false,
            list_patients: [],
            medicalrecord: {
                new_patient: false,
                tanggal: '',
                patient_id : '',
                medical_files: [],
                medical_record_files: [],
                profile: {
                    name: "",
                    address: "",
                    phone: "",
                    place_of_birth: "",
                    date_of_birth: "",
                    gender: "",
                    nik: "",
                    pengobatan_type: "",
                    no_bpjs: "",
                },
                
            },
            loading: false,
            rules: {
                name: [{ required: true, message: "Nama tidak boleh kosong!" }],
                tanggal: [{ required: true, message: "Tanggal rekam medis tidak boleh kosong!" }],
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
            this.$refs["medicalrecordForm"].validate((valid) => {
                let fields = this.$refs["medicalrecordForm"].fields;
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

                        let form = new FormData();

                        for (const key in this.medicalrecord) {
                            
                            if(key == 'medical_files') {
                                if(Array.isArray(this.medicalrecord[key])) {
                                    this.medicalrecord[key].forEach((item, index) => {
                                        form.append('medical_files['+index+']' , item);
                                    })
                                }
                            }
                            else if (key == 'new_patient') {
                                form.append(key, this.medicalrecord.new_patient ? '1' : '0');
                            }
                            else if (key == 'profile') {
                               for (const key_profile in this.medicalrecord[key]) {
                                form.append(key +'[' + key_profile + ']' , this.medicalrecord[key][key_profile]);
                               }
                            }
                            else if(key == 'medical_record_files') {
                                for (const key_medical_file in this.medicalrecord[key]) {
                                    for (const key_file in this.medicalrecord[key][key_medical_file]) {
                                        form.append(key +'[' + key_medical_file + ']['+key_file+']' , this.medicalrecord[key][key_medical_file][key_file]);
                                    }
                                }
                            }
                            else {
                                form.append(key , this.medicalrecord[key])
                            }
                        }

                        let route_url = route('api.medicalrecord.store');
                        if(this.$route.params.medicalrecord != undefined) {
                            form.append('_method' , 'PUT');
                            route_url = route('api.medicalrecord.update' , {medicalrecord: this.$route.params.medicalrecord})
                        }

                        axios.post(route_url , form , {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        })
                            .then((response) => {
                                this.loading = false;
                                this.$notify({
                                    title: "Pemberitahuan",
                                    message: response.data.message,
                                    type: "success",
                                });
                                this.$router.back();
                            })
                            .catch((error) => {
                                this.loading = false;
                                if(error.response.status === 422) {
                                    let response = error.response.data; 

                                    if(response.errors) {
                                        Object.keys(response.errors).forEach((key) => {
                                            let fields = this.$refs.medicalrecordForm.fields;
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
            if(file.hasOwnProperty('raw')) {
                let index = this.medicalrecord.medical_files.findIndex((item) => item.uid == file.raw.uid);
                this.medicalrecord.medical_files.splice(index, 1);
            }else {
                this.medicalrecord.medical_record_files = fileList;
            }
        },
        handlePreview(file) {
            let url = '';
            if(file.hasOwnProperty('raw')) {
                url =  URL.createObjectURL(file.raw)
            } else {
                url = file.url;
            }
            window.open(url, '_blank');
        },
        handleChange(file, fileList) {
            if(Array.isArray(fileList)) {

                let data = [];
                fileList.forEach((file) => {
                    if(file.hasOwnProperty('raw')) {
                        data.push(file.raw);
                    }
                });

                this.medicalrecord.medical_files = data;
            }
        },

        fetchData() {
            axios
                .get(
                    route("api.medicalrecord.show", {
                        medicalrecord: this.$route.params.medicalrecord,
                    })
                )
                .then((response) => {
                    this.medicalrecord = response.data.data
                });
        },

        checkUploadedFile(rule, prop, callback) {
            if((this.medicalrecord.hasOwnProperty('medical_files'))) {
                if(this.medicalrecord.medical_files.length == 0 && this.medicalrecord.medical_record_files.length == 0 ) {
                    return callback(new Error('Dokumen Belum di upload'))
                }
            } else{
                if(this.medicalrecord.medical_record_files.length == 0 ) {
                    return callback(new Error('Dokumen Belum di upload'))
                }
            }

            callback();
            
        }
    },

    watch: {

    },

    mounted() {
        if(this.$route.params.medicalrecord  != undefined) {
            this.fetchData();
        }
    },
};
</script>

<style>
    .medical-records .upload-demo .el-upload ,  .medical-records .upload-demo  .el-upload .el-upload-dragger {
        width: 100%;
    }
</style>