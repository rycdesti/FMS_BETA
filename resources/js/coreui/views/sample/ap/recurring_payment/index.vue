<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <call_out_form/>

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Recurring Payments</strong>
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
                            <div class="pt-1" slot="header">
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
                                        @click="generatePDFReport"
                                        variant="outline-primary"
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
                                    :url="table_url"></datatable>
                        </b-card>
                    </b-card>
                </b-col>
            </b-row>

        </div>
    </div>
</template>

<script>
    import CallOutForm from '@/views/sample/ap/recurring_payment/form'

    export default {
        name: 'RecurringPayment',
        components: {
            'call_out_form': CallOutForm,
        },
        data() {
            return {
                table_id: 'tbl-recurring-payment',
                table_columns: [
                    {data: 'supplier_name'},
                    {data: 'supplier_info', bSortable: false, bSearchable: false},
                    {data: 'duration', bSortable: false, bSearchable: false},
                    {data: 'frequency', bSortable: false, bSearchable: false},
                    {data: 'status', bSortable: false, bSearchable: false},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false}
                ],
                table_headers: [
                    'Supplier Name',
                    'Supplier Information',
                    'Duration',
                    'Frequency',
                    'Status',
                    'Logs',
                    'Actions'
                ],
                table_url: '',
                data: '',
            }
        },
        created() {
            this.fetchData();
        },
        mounted() {
            const component = this;

            $(document).on('click', '#btn-delete', function () {
                component.deleteData($(this).data('id'));
            });

            $(document).on('click', '#btn-recurring-payment-distribution', function () {
                const id = $(this).data('id');
                component.$router.push({name: 'RecurringPaymentDistribution', params: {recurring_payment_id: id}});

                // var table = $('#tbl-bank').DataTable();
                // var node = table.row(`#row-${id}`).data();
            });

            $(document).on('click', '#btn-update-status', function () {
                const id = $(this).data('id');
                const status = $(this).text();
                component.updateStatus(id, status);
            });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-delete', '#btn-recurring-payment-distribution', '#btn-update-status'],
                ['edit'],
                this.$root
            );
        },
        methods: {
            fetchData() {
                this.table_url = '/api/ap/recurring-payment';
            },
            async deleteData(id) {
                const component = this;

                let result = await this.$swal.fire({
                    title: 'Delete Record',
                    text: 'Do you really want to delete this record?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    axios.delete(`/api/ap/recurring-payment/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-recurring-payment');
                                    table.DataTable().draw(false);
                                });
                            }
                        })
                        .catch(function (error) {
                        })
                        .finally(function () {
                        });
                }
            },
            async updateStatus(id, status) {
                const component = this;

                let result = await this.$swal.fire({
                    title: status,
                    text: 'Do you really want to ' + status.toLowerCase() + ' this record?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    axios.patch(`/api/ap/recurring-payment/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-recurring-payment');
                                    table.DataTable().draw(false);
                                });
                            }
                        })
                        .catch(function (error) {
                        })
                        .finally(function () {
                        });
                }
            },

            generatePDFReport () {
              const url = `/api/reports/ap/recurring-payment`
              window.open(url, '_blank')
            },
        }
    }
</script>
