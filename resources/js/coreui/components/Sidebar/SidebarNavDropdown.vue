<template>
    <router-link
            :to="url"
            tag="li"
            class="nav-item nav-dropdown"
            disabled
    >
        <div
                :class="'nav-link nav-dropdown-toggle ' + color"
                @click="handleClick"
        >
            <i v-if="icon" :class="icon + ' ' + color"></i> {{ name }}
        </div>
        <ul class="nav-dropdown-items">
            <slot/>
        </ul>
    </router-link>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: '',
            },
            url: {
                type: String,
                default: '',
            },
            icon: {
                type: String,
                default: '',
            },
            color: {
                type: String,
                default: '',
            }
        },
        methods: {
            handleClick(e) {
                e.preventDefault()
                e.target.parentElement.classList.toggle('open')
            },
        },
    }
</script>
