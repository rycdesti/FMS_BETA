<template>
  <header class="app-header navbar">
    <button
      class="navbar-toggler mobile-sidebar-toggler d-lg-none"
      type="button"
      @click="mobileSidebarToggle"
    >
    </button>
    <b-link
      class="navbar-brand"
      to="#"
    />
    <button
      class="navbar-toggler sidebar-toggler d-md-down-none"
      type="button"
      @click="sidebarToggle"
    >
      <span class="navbar-toggler-icon" />
    </button>
    <b-navbar-nav class="d-md-down-none">
      <b-nav-item class="px-3">
        Dashboard
      </b-nav-item>
      <b-nav-item class="px-3">
        Users
      </b-nav-item>
      <b-nav-item class="px-3">
        Settings
      </b-nav-item>
    </b-navbar-nav>
    <b-navbar-nav class="ml-auto">
      <b-nav-item class="d-md-down-none">
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
      </b-nav-item>
      <header-dropdown />
    </b-navbar-nav>
  </header>
</template>
<script>
import HeaderDropdown from './HeaderDropdown.vue'

export default {
  name      : 'CHeader',
  components: { HeaderDropdown },
  methods   : {
    sidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-hidden')
    },
    sidebarMinimize (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-minimized')
    },
    mobileSidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-mobile-show')
    },
    asideToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('aside-menu-hidden')
    },
  },
}

</script>
