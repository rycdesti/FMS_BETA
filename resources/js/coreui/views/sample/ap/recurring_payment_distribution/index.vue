<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <call_out_form :recurring_payment_id="this.recurring_payment_id"/>

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Recurring Payment Distributions</strong>
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
    import CallOutForm from '@/views/sample/ap/recurring_payment_distribution/form'

    export default {
        name: 'RecurringPaymentDistribution',
        props: ['recurring_payment_id'],
        components: {
            'call_out_form': CallOutForm,
        },
        data() {
            return {
                table_id: 'tbl-recurring-payment-distribution',
                table_columns: [
                    {data: 'acct_code', bSortable: false, bSearchable: false},
                    {data: 'acct_desc', bSortable: false, bSearchable: false},
                    {data: 'debit', bSortable: false, bSearchable: false},
                    {data: 'credit', bSortable: false, bSearchable: false},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false}
                ],
                table_headers: ['Account Code', 'Description', 'Debit', 'Credit', 'Logs', 'Actions'],
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
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-delete'],
                [],
                this.$root
            );
        },
        methods: {
            fetchData() {
                this.table_url = `/api/ap/recurring-payment-distribution/${this.recurring_payment_id}`;
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
                    axios.delete(`/api/ap/recurring-payment-distribution/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-recurring-payment-distribution');
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
        }
    }
</script>
