<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <call_out_form :supplier_id="this.supplier_id"/>

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Supplier Contacts</strong>
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

                        <b-card
                                header-tag="header"
                                footer-tag="footer"
                        >
                            <div
                                    slot="header"
                                    class="pt-1"
                            >
                                <b-button
                                        v-b-tooltip.hover
                                        v-b-modal.form_modal
                                        title="Add New Record"
                                        variant="outline-primary"
                                >
                                    <i class="fa fa-plus"/>
                                </b-button>

                                <b-button
                                        v-b-tooltip.hover
                                        title="Generate PDF Report"
                                        variant="outline-primary"
                                        @click="generatePDFReport"
                                >
                                    <i class="fa fa-file-pdf-o"/>
                                </b-button>

                                <b-button
                                        v-b-tooltip.hover
                                        title="Generate Excel Report"
                                        variant="outline-primary"
                                >
                                    <i class="fa fa-file-excel-o"/>
                                </b-button>
                            </div>

                            <datatable
                                    :id="table_id"
                                    :headers="table_headers"
                                    :columns="table_columns"
                                    :url="table_url"
                            />
                        </b-card>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
    import CallOutForm from '@/views/sample/requisition/supplier_contact/form'

    export default {
        name: 'SupplierContact',
        components: {call_out_form: CallOutForm},
        props: ['supplier_id'],
        data() {
            return {
                table_id: 'tbl-supplier-contact',
                table_columns: [
                    {data: 'contact_person'},
                    {data: 'contact_info', bSortable: false, bSearchable: false},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false},
                ],
                table_headers: [
                    'Contact Name',
                    'Contact Information',
                    'Logs',
                    'Actions',
                ],
                table_url: '',
                data: '',
            }
        },
        created() {
            this.fetchData()
        },
        mounted() {
            const component = this;

            $(document).on('click', '#btn-edit', function () {
                component.$root.$emit('edit', $(this).data('id'));
            });

            $(document).on('click', '#btn-delete', function () {
                component.deleteData($(this).data('id'));
            });

            $(document).on('click', '#btn-update-status', function () {
                const id = $(this).data('id');
                const status = $(this).text();
                component.updateStatus(id, status);

                // var table = $('#tbl-supplier-contact').DataTable();
                // var node = table.row(`#row-${id}`).data();
            });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                [
                    '#btn-edit',
                    '#btn-delete',
                    '#btn-update-status',
                ],
                ['edit', 'beginning_bal'],
                this.$root
            )
        },
        methods: {
            fetchData() {
                this.table_url = `/api/requisition/supplier-contact/${this.supplier_id}`;
            },
            async deleteData(id) {
                const component = this;

                const result = await this.$swal.fire({
                    title: 'Delete Record',
                    text: 'Do you really want to delete this record?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                });

                if (result.value) {
                    axios.delete(`/api/requisition/supplier-contact/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-supplier-contact');
                                    table.DataTable().draw(false);
                                })
                            }
                        })
                        .catch(function (error) {
                        })
                        .finally(function () {
                        })
                }
            },

            async updateStatus(id, status) {
                const component = this;

                const result = await this.$swal.fire({
                    title: status,
                    text: `Do you really want to ${status.toLowerCase()} this record?`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                });

                if (result.value) {
                    axios.patch(`/api/requisition/supplier-contact/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-supplier-contact');
                                    table.DataTable().draw(false);
                                })
                            }
                        })
                        .catch(function (error) {
                        })
                        .finally(function () {
                        })
                }
            },

            generatePDFReport() {
                const url = `/api/reports/requisition/supplier_contact/${this.supplier_id}`;
                window.open(url, '_blank');
            },
        },
    }
</script>
