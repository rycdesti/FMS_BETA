<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>Bootstrap Breadcrumb</strong>
                            <div class="card-actions">
                                <a
                                        href="https://bootstrap-vue.js.org/docs/components/breadcrumb"
                                        target="_blank"
                                >
                                    <small class="text-muted">
                                        docs
                                    </small>
                                </a>
                            </div>
                        </div>
                        <b-breadcrumb :items="items"/>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Breadcrumbs',
        data() {
            return {
                items: [
                    {
                        text: 'Admin',
                        href: '#',
                    },
                    {
                        text: 'Manage',
                        href: '#',
                    },
                    {
                        text: 'Library',
                        active: true,
                    },
                ],
            }
        },
    }
</script>
