<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <!--<call_out_form/>-->

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Monthly Payments</strong>
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

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="float-right form-inline">
                                        <label class="mr-2">Month and Year:</label>
                                        <b-datepicker v-model="table_filter_fields.date_filter"
                                                      class="mb-2"
                                                      format="MMM yyyy"
                                                      minimum-view="month"
                                                      @closed="dateFilter"
                                        >
                                        </b-datepicker>
                                    </div>
                                </div>
                            </div>

                            <datatable
                                    :id="table_id"
                                    :headers="table_headers"
                                    :columns="table_columns"
                                    :url="table_url"
                                    :filter_fields="table_filter_fields"></datatable>
                        </b-card>
                    </b-card>
                </b-col>
            </b-row>

        </div>
    </div>
</template>

<style>
    .vdp-datepicker__calendar {
        left: -120px;
    }
</style>

<script>
    import CallOutForm from '@/views/sample/ap/monthly_payment/form'

    export default {
        name: 'MonthlyPayment',
        components: {
            'call_out_form': CallOutForm,
        },
        data() {
            return {
                table_id: 'tbl-monthly-payment',
                table_columns: [
                    {data: 'supplier_info', bSortable: false, bSearchable: false},
                    {data: 'remarks', bSortable: false, bSearchable: false},
                    {data: 'due_date', bSortable: false, bSearchable: false},
                    {data: 'remaining_days', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false}
                ],
                table_headers: ['Supplier Information', 'Remarks', 'Due Date', 'Remaining Days', 'Actions'],
                table_url: '',
                data: '',
                table_filter_fields: {
                    date_filter: new Date().getFullYear() + '-' + (new Date().getMonth() + 1),
                }
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

            $(document).on('click', '#btn-update-status', function () {
                const id = $(this).data('id');
                const status = $(this).text();
                component.updateStatus(id, status);
            });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-delete', '#btn-update-status'],
                ['edit'],
                this.$root
            );
        },
        methods: {
            dateFilter() {
                this.table_filter_fields.date_filter = this.table_filter_fields.date_filter.getFullYear() + '-' +
                    (this.table_filter_fields.date_filter.getMonth() + 1);
                const table = $('#tbl-monthly-payment');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = '/api/ap/monthly-payment';
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
                    axios.delete(`/api/ap/monthly-payment/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-monthly-payment');
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
                    axios.patch(`/api/ap/monthly-payment/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-monthly-payment');
                                    table.DataTable().draw(false);
                                });
                            }
                        })
                        .catch(function (error) {
                        })
                        .finally(function () {
                        });
                }
            }
        }
    }
</script>
