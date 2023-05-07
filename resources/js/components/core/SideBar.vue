<template>
    <!-- Sidebar -->
    <ul
        class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
        id="accordionSidebar"
    >
        <!-- Sidebar - Brand -->
        <a
            class="sidebar-brand d-flex align-items-center justify-content-center"
            href="index.html"
        >
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0" />

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a
                class="nav-link"
                href="#"
                @click.prevent="$router.push({ name: 'admin.dashboard' })"
            >
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a
            >
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <template
            v-if="
                hasAccess('role.role-list') || 
                hasAccess('user.user-list')
            "
        >
        <!-- Heading -->
        <div class="sidebar-heading">Master Data</div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a
                class="nav-link"
                href="#"
                v-if="hasAccess('user.user-list')"
                @click.prevent="$router.push({ name: 'users.index' })"
            >
                <i class="fas fa-users"></i>
                <span>User</span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="#"
                v-if="hasAccess('role.role-list')"
                @click.prevent="$router.push({ name: 'roles.index' })"
            >
                <i class="fas fa-flag"></i>
                <span>Roles</span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="#"
                v-if="hasAccess('patient.patient-list')"
                @click.prevent="$router.push({ name: 'patients.index' })"
            >
                <i class="fas fa-user"></i>
                <span>Pasien</span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="#"
                v-if="hasAccess('medicalrecord.medicalrecord-list')"
                @click.prevent="$router.push({ name: 'medicalrecords.index' })"
            >
                <i class="fas fa-file"></i>
                <span>Rekam Medis</span>
            </a>
        </li>
        </template>
    </ul>
    <!-- End of Sidebar -->
</template>