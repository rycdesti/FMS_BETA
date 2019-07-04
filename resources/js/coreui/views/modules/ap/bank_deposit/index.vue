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
                            <i class="fa fa-align-justify"/> <strong>List of Bank Deposits</strong>
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

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="float-right form-inline">
                                        <label class="mr-2">Period from:</label>
                                        <b-datepicker v-model="table_filter_fields.period_from_filter"
                                                      class="mb-2"
                                                      format="MMM dd yyyy"
                                                      minimum-view="day"
                                                      @closed="filter"></b-datepicker>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="float-right form-inline">
                                        <label class="mr-2">Period to:</label>
                                        <b-datepicker v-model="table_filter_fields.period_to_filter"
                                                      class="mb-2"
                                                      format="MMM dd yyyy"
                                                      minimum-view="day"
                                                      @closed="filter"></b-datepicker>
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

<script>
    import CallOutForm from '@/views/modules/ap/bank_deposit/form'

    export default {
        name: 'Bank',
        components: {
            'call_out_form': CallOutForm,
        },
        data() {
            return {
                table_id: 'tbl-bank-deposit',
                table_columns: [
                    {data: 'bank_details', bSortable: false, bSearchable: true},
                    {data: 'date_deposit', bSortable: false, bSearchable: true},
                    {data: 'time_deposit', bSortable: false, bSearchable: false},
                    {data: 'ref_no', bSortable: false, bSearchable: false},
                    {data: 'cash_deposit', bSortable: false, bSearchable: false},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false}
                ],
                table_headers: [
                    'Bank Details',
                    'Date Deposited',
                    'Time Deposited',
                    'Reference No',
                    'Cash Deposited',
                    'Logs',
                    'Actions'
                ],
                table_url: '',
                table_filter_fields: {
                    period_from_filter: moment(new Date(new Date().getFullYear(), new Date().getMonth(), 1)).format('YYYY-MM-DD'),
                    period_to_filter: moment(new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)).format('YYYY-MM-DD'),
                },
                data: '',
            }
        },
        created() {
            this.fetchData();
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

            });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-edit', '#btn-delete', '#btn-update-status'],
                ['edit'],
                this.$root
            );
        },
        methods: {
            filter() {
                if (this.table_filter_fields.period_from_filter instanceof Date) {
                    this.table_filter_fields.period_from_filter = this.table_filter_fields.period_from_filter.getFullYear() + '-' +
                        (this.table_filter_fields.period_from_filter.getMonth() + 1) + '-' + this.table_filter_fields.period_from_filter.getDate();
                }

                if (this.table_filter_fields.period_to_filter instanceof Date) {
                    this.table_filter_fields.period_to_filter = this.table_filter_fields.period_to_filter.getFullYear() + '-' +
                        (this.table_filter_fields.period_to_filter.getMonth() + 1) + '-' + this.table_filter_fields.period_to_filter.getDate();
                }
                const table = $('#tbl-bank-deposit');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = '/api/ap/bank-deposit';
            },
            async deleteData(id) {
                const component = this;

                let result = await this.$swal.fire({
                    title: 'Delete Record',
                    text: 'Do you really want to delete this record?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    axios.delete(`/api/ap/bank-deposit/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-bank-deposit');
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
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    axios.patch(`/api/ap/bank-deposit/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-bank-deposit');
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
              const url = '/api/reports/ap/bank';
              window.open(url, '_blank')
            },
        }
    }
</script>
