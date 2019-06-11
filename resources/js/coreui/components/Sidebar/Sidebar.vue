<template>
    <div class="sidebar">
        <sidebar-header/>
        <sidebar-form/>
        <nav class="sidebar-nav">
            <div slot="header"/>
            <ul class="nav">
                <template v-for="(item, index) in navItems">
                    <template v-if="item.title">
                        <sidebar-nav-title
                                :key="index"
                                :name="item.name"
                                :classes="item.class"
                                :wrapper="item.wrapper"
                        />
                    </template>
                    <template v-else-if="item.divider">
                        <sidebar-nav-divider
                                :key="index"
                                :classes="item.class"
                        />
                    </template>
                    <template v-else-if="item.label">
                        <sidebar-nav-label
                                :key="index"
                                :name="item.name"
                                :url="item.url"
                                :icon="item.icon"
                                :label="item.label"
                                :classes="item.class"
                        />
                    </template>
                    <template v-else>
                        <template v-if="item.children">
                            <!-- First level dropdown -->
                            <sidebar-nav-dropdown
                                    :key="index"
                                    :name="item.name"
                                    :url="item.url"
                                    :color="item.color"
                            >
                                <template v-for="(childL1, index1) in item.children">
                                    <template>
                                        <!-- Second level dropdown -->
                                        <sidebar-nav-title class="pl-4"
                                                :key="index1"
                                                :name="childL1.name"
                                                :classes="childL1.class"
                                                :wrapper="childL1.wrapper"
                                                :icon="childL1.icon"
                                                :color="childL1.color"/>

                                        <!--Third level dropdown-->
                                        <li
                                                v-for="(childL2, index2) in childL1.children"
                                                :key="index2"
                                                class="nav-item"
                                        >
                                            <sidebar-nav-link
                                                    :name="childL2.name"
                                                    :url="childL2.url"
                                                    :icon="childL2.icon"
                                                    :badge="childL2.badge"
                                                    :variant="childL2.variant"
                                            />
                                        </li>
                                    </template>
                                </template>
                            </sidebar-nav-dropdown>
                        </template>
                        <template v-else>
                            <sidebar-nav-item
                                    :key="index"
                                    :classes="item.class"
                            >
                                <sidebar-nav-link
                                        :name="item.name"
                                        :url="item.url"
                                        :icon="item.icon"
                                        :badge="item.badge"
                                        :variant="item.variant"
                                />
                            </sidebar-nav-item>
                        </template>
                    </template>
                </template>
            </ul>
            <slot/>
        </nav>
        <sidebar-footer/>
    </div>
</template>
<script>
    import SidebarFooter from './SidebarFooter'
    import SidebarForm from './SidebarForm'
    import SidebarHeader from './SidebarHeader'
    import SidebarMinimizer from './SidebarMinimizer'
    import SidebarNavDivider from './SidebarNavDivider'
    import SidebarNavDropdown from './SidebarNavDropdown'
    import SidebarNavLink from './SidebarNavLink'
    import SidebarNavTitle from './SidebarNavTitle'
    import SidebarNavItem from './SidebarNavItem'
    import SidebarNavLabel from './SidebarNavLabel'

    export default {
        name: 'Sidebar',
        components: {
            SidebarFooter,
            SidebarForm,
            SidebarHeader,
            SidebarNavDivider,
            SidebarNavDropdown,
            SidebarNavLink,
            SidebarNavTitle,
            SidebarNavItem,
            SidebarNavLabel,
        },
        props: {
            navItems: {
                type: Array,
                required: true,
                default: () => [],
            },
        },
        methods: {
            handleClick(e) {
                e.preventDefault()
                e.target.parentElement.classList.toggle('open')
            },
        },
    }

</script>

<style lang="css">
    .nav-link {
        cursor: pointer;
    }

</style>
