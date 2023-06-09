<template>
    <div class="patient-list">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">List Pasien</h1>
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb bg-light">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Pasien
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div
                class="card-header d-flex justify-content-between align-items-center"
            >
                <span>List Pasien</span>
                <el-button
                    v-if="hasAccess('patient.patient-list')"
                    type="primary"
                    size="small"
                    icon="fas fa-plus"
                    @click="$router.push({ name: 'patients.create' })"
                    >Tambah Pasien</el-button
                >
            </div>
            <div class="card-body">
                <div class="row mb-3 align-items-end"> 
                    <div class="col-md-4">
                        <span class="filter-text">Filter Tipe</span>
                        <el-checkbox-group v-model="filter.pengobatan_type" @change="fetchData">
                            <el-checkbox label="umum" border>Umum</el-checkbox>
                            <el-checkbox label="bpjs" border>BPJS</el-checkbox>
                        </el-checkbox-group>
                    </div>
                    <div class="col-md-4">
                        <span class="filter-text">Filter Jenis Kelamin</span>
                        <el-checkbox-group v-model="filter.gender" @change="fetchData">
                            <el-checkbox label="laki_laki" border>Laki - Laki</el-checkbox>
                            <el-checkbox label="perempuan" border>Perempuan</el-checkbox>
                        </el-checkbox-group>
                    </div>
                    <div class="col-md-4">
                        <div
                            class="btn-group float-right"
                        >
                            <el-input
                                prefix-icon="el-icon-search"
                                @keyup.enter.native="fetchData"
                                v-model="filter.search"
                                :placeholder="'Cari..'"
                            >
                                <template slot="append">
                                    <el-button @click="fetchData">
                                        <span class="fa fa-search"></span>
                                    </el-button>
                                </template>
                            </el-input>
                        </div>
                    </div>
                </div>

                <div class="border-bottom"></div>

                <el-table
                    :data="data"
                    stripe
                    style="width: 100%"
                    ref="pageTable"
                    v-loading.body="tableIsLoading"
                >
                    <el-table-column label="#" width="40">
                        <template slot-scope="scope">
                            {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1}}
                        </template>
                    </el-table-column>
                    <el-table-column prop="code" label="Kode" width="120"></el-table-column>
                    <el-table-column prop="profile.name" label="Nama Pasien">
                        <template slot-scope="scope">
                            <a
                                @click.prevent="
                                    $router.push({
                                        name: 'patients.edit',
                                        params: {
                                            patient: scope.row.id,
                                        },
                                    })
                                "
                                href="#"
                            >
                                {{ scope.row.profile.name }}
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column prop="profile.phone" label="Phone">
                    </el-table-column>
                    <el-table-column prop="profile.gender" label="Jenis Kelamin">
                        <template slot-scope="scope">
                            {{ scope.row.profile.gender  == 'laki_laki' ? 'Laki - Laki' : 'Perempuan' }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="pengobatan_type" label="Tipe">
                        <template slot-scope="scope">
                            <span style="text-transform: capitalize;">{{ scope.row.pengobatan_type }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="actions" label="Aksi">
                        <template slot-scope="scope">
                            <el-button-group>
                                <el-button
                                    v-if="hasAccess('patient.patient-update')"
                                    type="primary"
                                    size="small"
                                    icon="el-icon-edit"
                                    @click="
                                        $router.push({
                                            name: 'patients.edit',
                                            params: {
                                                patient: scope.row.id,
                                            },
                                        })
                                    "
                                ></el-button>
                                <el-button
                                    size="small"
                                    type="danger"
                                    plain
                                    icon="el-icon-delete"
                                    @click="onDelete(scope.row)"
                                ></el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
                <div
                    class="pagination-wrap"
                    style="text-align: center; padding-top: 20px"
                >
                    <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="meta.current_page"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="parseInt(meta.per_page)"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="meta.total"
                    ></el-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from "lodash";

export default {
    data() {
        return {
            data: [],
            tableIsLoading: false,
            meta: {
                current_page: 1,
                per_page: 10,
            },
            order_meta: {
                order_by: "",
                order: "",
            },
            list_roles: [],
            filter: {
                pengobatan_type: [],
                gender: [],
                search: '',
            },
        };
    },

    watch: {
        'filter.role' : function(value) {
            this.fetchData();
        }
    },
    methods: {
        queryServer(customProperties) {
            const cancelSource = axios.CancelToken.source();
            const properties = {
                params: {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    relations: "profile",
                    ...this.filter,
                },
                cancelToken: cancelSource.token,
            };
            this.request = {
                cancel: cancelSource.cancel,
            };
            axios.get(route("api.patient.index"), properties).then((response) => {
                if (typeof response !== "undefined") {
                    this.tableIsLoading = false;
                    this.data = response.data.data;
                    this.meta = response.data.meta;
                    this.order_meta.order_by = properties.order_by;
                    this.order_meta.order = properties.order;
                }
            });
        },
        fetchData() {
            this.tableIsLoading = true;
            if (this.request) this.cancel();
            this.queryServer();
        },
        handleSizeChange(event) {
            console.log(`per page :${event}`);
            this.tableIsLoading = true;
            this.meta.per_page = event;
            this.queryServer();
        },
        handleCurrentChange(event) {
            console.log(`current page :${event}`);
            this.tableIsLoading = true;
            this.meta.current_page = event;
            this.queryServer();
        },
        handleSortChange(event) {
            console.log("sorting", event);
            this.tableIsLoading = true;
            this.queryServer({
                order_by: event.prop,
                order: event.order,
            });
        },
        performSearch: _.debounce(function (query) {
            console.log(`searching:${query.target.value}`);
            this.tableIsLoading = true;
            this.queryServer({
                search: query.target.value,
            });
        }, 300),
        cancel() {
            this.request.cancel();
        },

        onDelete(row) {
            this.$confirm("Apakah anda yakin ?", "Konfirmasi", {
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
                type: "warning",
            })
            .then(() => {
                this.tableIsLoading = true;
                axios
                    .delete(
                        route("api.patient.destroy", {
                            patient: row.id,
                        })
                    )
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.$notify({
                            title: "Pemberitahuan",
                            message: response.data.message,
                            type: "success",
                        });
    
                        this.fetchData();
                    })
                    .catch(() => this.tableIsLoading = false);
            })
        },

        fetchRoles() {
            axios
                .get(route("api.role.index"), {
                    params: {
                        type: "all",
                    },
                })
                .then((response) => {
                    this.list_roles = response.data.data;
                });
        },
    },

    mounted() {
        this.fetchData();
        this.fetchRoles();
    },
};
</script>

<style>
.patient-list .filter-text {
    font-size: 14px !important;
}
.patient-list .el-checkbox{
    margin-right: 0 !important;
}
</style>