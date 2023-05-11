<template>
    <div>
        <div class="row" v-if="dashboard">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pasien</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ dashboard.patient_all }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Rekam Medis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ dashboard.medical_record_day }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-file-invoice fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Pasien Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ dashboard.patient_day }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Rekam Medis Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ dashboard.medical_record_day }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-file-invoice fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-body">
                <h4 class="d-inline-block mb-3">Cari Pasien</h4>
                <el-button
                    class="float-right"
                    v-if="hasAccess('medicalrecord.medicalrecord-list')"
                    type="primary"
                    size="small"
                    icon="fas fa-plus"
                    @click="$router.push({ name: 'medicalrecords.create' })"
                    >Tambah Rekam Medis</el-button
                >
                <el-select 
                v-model="medical_id"
                    class="w-100"
                    remote
                    placeholder="Cari berdasarkan kode , nik dan nama pasien.."
                    :loading="loading"
                    :remote-method="fetchMedicalRecord"
                    filterable 
                    clearable>
                    <el-option
                        v-for="(medicalrecord , index) in list_medicalrecords"
                        :value="medicalrecord.id"
                        :label="medicalrecord.code  + ' / ' + medicalrecord.tanggal + ' / ' + medicalrecord.patient.profile.name"
                        :key="index"
                        ></el-option
                    >
                </el-select>

                <div  v-if="data_medical_record">
                    <div class="mt-5"></div>
                    <h4>Data Rekam Medis</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <table v-if="data_medical_record.patient" class="table table-responsive table-sm">
                                <tbody>
                                    <tr>
                                        <th width="180px">Nama</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.patient.profile.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.patient.profile.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <td>:</td>
                                        <td>
                                            <span v-if=" data_medical_record.patient.profile.gender == 'laki_laki' ">Laki - Laki</span>
                                            <span v-else>Perempuan</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tempat, Tgl Lahir</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.patient.profile.place_of_birth + ', ' + data_medical_record.patient.profile.date_of_birth}}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.patient.profile.address }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-responsive table-sm"> 
                                <tbody>
                                    <tr>
                                        <th width="180px">Kode Rekam Medis</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.tanggal }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <td>:</td>
                                        <td>{{ data_medical_record.patient.nik }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe</th>
                                        <td>:</td>
                                        <td><span style="text-transform: capitalize;">{{ data_medical_record.patient.pengobatan_type }}</span></td>
                                    </tr>
                                    <tr v-if="data_medical_record.patient.pengobatan_type == 'bpjs'">
                                        <th>NO BPJS</th>
                                        <td>:</td>
                                        <td>
                                            <span>{{ data_medical_record.patient.no_bpjs }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5"></div>

                    <h4>Dokumen Rekam Medis</h4>
                    <el-table
                        :data="data_medical_record.medical_record_files"
                        stripe
                        style="width: 100%"
                        ref="pageTable"
                    >
                        <el-table-column label="#" width="40">
                            <template slot-scope="scope">
                                {{ scope.$index + 1}}
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="Nama Dokumen">
                            <template slot-scope="scope">
                                <a :href="scope.row.url" target="_blank">
                                    {{ scope.row.name }}
                                </a>
                            </template>
                        </el-table-column>
                        <el-table-column prop="actions" label="Aksi">
                            <template slot-scope="scope">
                                <el-button-group>
                                    <el-button
                                        v-if="hasAccess('medicalrecord.medicalrecord-update')"
                                        type="primary"
                                        size="small"
                                        icon="el-icon-view"
                                        @click="goToPdf(scope)"
                                    > Lihat Dokumen</el-button>
                                </el-button-group>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
export default {
    data() {
        return {
            dashboard: {
                patient_all: 0,
                medical_all: 0,
                patient_day: 0,
                medical_record_day: 0,
            },
            loading: false,
            search: '',
            list_medicalrecords: [],
            medical_id: '',
        }
    },

    computed: {
        data_medical_record() {

            if(this.medical_id) {
                let item = this.list_medicalrecords.find((medical) => medical.id == this.medical_id);

                if(item) {
                    return item;
                }
            }
            return null
        }
    },

    methods: {
        fetchDataDashboard() {
            axios
                .get(route('api.dashboard')).then((response) => {
                    this.dashboard = response.data;
                })
        },

        fetchMedicalRecord(query) {
            this.loading = true;
            axios
                .get(
                    route("api.medicalrecord.index") , 
                    {
                        params: {
                            search: query,
                        }
                    }
                )
                .then((response) => {
                    this.loading = false;
                    this.list_medicalrecords = response.data.data;
                })
                .catch(() => this.loading = false);
        },

        goToPdf(scope) {
            window.open(scope.row.url);
        }
    },

    mounted() {

        this.fetchDataDashboard();

    }
}
</script>