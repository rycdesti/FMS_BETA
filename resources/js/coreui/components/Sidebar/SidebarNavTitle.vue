<template>
    <li :class="classList">
        <template v-if="wrapper && wrapper.element">
            <component
                    :is="wrapper.element"
                    v-bind="wrapper.attributes"
            >
                <div :class="color"><i v-if="icon" :class="icon + ' ' + color"></i> {{ name }}</div>
            </component>
        </template>
        <template v-else>
            <div :class="color"><i v-if="icon" :class="icon + ' ' + color"></i> {{ name }}</div>
        </template>
    </li>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: '',
            },
            classes: {
                type: String,
                default: '',
            },
            wrapper: {
                type: Object,
                default: () => {
                },
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
        computed: {
            classList() {
                return ['nav-title', ...this.itemClasses]
            },
            itemClasses() {
                return this.classes ? this.classes.split(' ') : ''
            },
        },
    }
</script>
