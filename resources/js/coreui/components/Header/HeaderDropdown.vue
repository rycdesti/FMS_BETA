<template>
  <b-nav-item-dropdown
    right
    no-caret
  >
    <b-dropdown-header
      tag="div"
      class="text-center"
    >
      <strong>Account</strong>
    </b-dropdown-header>
    <b-dropdown-item>
      <i class="fa fa-bell-o" /> Updates
      <b-badge variant="info">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-envelope-o" /> Messages
      <b-badge variant="success">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-tasks" /> Tasks
      <b-badge variant="danger">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-comments" /> Comments
      <b-badge variant="warning">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-header
      tag="div"
      class="text-center"
    >
      <strong>Settings</strong>
    </b-dropdown-header>
    <b-dropdown-item>
      <i class="fa fa-user" /> Profile
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-wrench" /> Settings
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-usd" /> Payments
      <b-badge variant="secondary">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-file" /> Projects
      <b-badge variant="primary">
        {{ itemsCount }}
      </b-badge>
    </b-dropdown-item>
    <b-dropdown-divider />
    <b-dropdown-item>
      <i class="fa fa-shield" /> Lock Account
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-lock" /> Logout
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
<script>
export default {
  name: 'HeaderDropdown',
  data: () => {
    return { itemsCount: 42 }
  },
}

</script>
