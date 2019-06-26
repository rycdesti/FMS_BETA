<template>
    <div class="wrapper">
        <div class="animated fadeIn">
          <call_out_form/>
          <call_out_calendar_form :table_filter="this.table_filter_fields"/>
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

                              <b-button
                                v-b-tooltip.hover
                                v-b-modal.calendar_form_modal
                                id="btn-calendar"
                                title="Calendar"
                                variant="outline-primary"
                              >
                                <i class="fa fa-calendar"/>
                              </b-button>
                            </div>

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="float-right form-inline">
                                        <label class="mr-2">Frequency:</label>
                                        <b-form-select v-model="table_filter_fields.frequency_filter"
                                                       :options="frequency_opt"
                                                       id="frequency_filter"
                                                       class="input-container mb-2"
                                                       @change="filter">
                                            <template slot="first">
                                                <option value="">Display all</option>
                                            </template>
                                        </b-form-select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="float-right form-inline">
                                        <label class="mr-2">Status:</label>
                                        <b-form-select v-model="table_filter_fields.status_filter"
                                                       :options="status_opt"
                                                       id="status_filter"
                                                       class="input-container mb-2"
                                                       @change="filter">
                                            <template slot="first">
                                                <option value="">Display all</option>
                                            </template>
                                        </b-form-select>
                                    </div>
                                </div>
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
                                                      @closed="filter"
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
    import CallOutForm from '@/views/modules/ap/monthly_payment/review/form'
    import CallOutCalendarForm from '@/views/modules/ap/monthly_payment/review/calendar_form'

    export default {
        name: 'MonthlyPaymentReview',
        components: {
          'call_out_form': CallOutForm,
          'call_out_calendar_form': CallOutCalendarForm,
        },
        data() {
            return {
                table_id: 'tbl-monthly-payment',
                table_columns: [
                    {data: 'supplier_info', bSortable: false, bSearchable: true},
                    {data: 'voucher_info', bSortable: false, bSearchable: true},
                    {data: 'remarks', bSortable: false, bSearchable: false},
                    {data: 'due_date', bSortable: false, bSearchable: false},
                    {data: 'remaining_days', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false}
                ],
                table_headers: [
                    'Supplier Information',
                    'Voucher Information',
                    'Remarks',
                    'Due Date',
                    'Remaining Days',
                    'Actions'
                ],
                table_url: '',
                table_filter_fields: {
                    frequency_filter: '',
                    status_filter: '',
                    date_filter: new Date().getFullYear() + '-' + (new Date().getMonth() + 1),
                },
                data: '',
                frequency_opt : [{}],
                status_opt: {
                    'N': 'Not Requested',
                    'O': 'For Review',
                    'R': 'For Recommendation',
                    'F': 'For Approval',
                }
            }
        },
        created() {
            this.fetchData();
        },
        mounted() {
            const component = this;

            $(document).on('click', '#btn-calendar', function () {
              component.$root.$emit('calendar', '');
            });

            $(document).on('click', '#btn-check-voucher', function () {
                component.$root.$emit('edit', $(this).data('id'));
            });

            $(document).on('click', '#btn-delete-check-voucher', function () {
                component.deleteData($(this).data('id'));
            });

            $(document).on('click', '#btn-print-check-voucher', function () {
                component.generateCheckVoucherPDF($(this).data('id'));
            });

            axios.get('/api/ap/utils/get_frequency')
                .then(function (response) {
                    component.frequency_opt = response.data.frequency;
                });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-check-voucher', '#btn-print-check-voucher', '#btn-calendar'],
                ['edit', 'calendar'],
                this.$root
            );
        },
        methods: {
            filter() {
                this.table_filter_fields.frequency_filter = $('#frequency_filter').val();
                this.table_filter_fields.status_filter = $('#status_filter').val();
                if (this.table_filter_fields.date_filter instanceof Date) {
                    this.table_filter_fields.date_filter = this.table_filter_fields.date_filter.getFullYear() + '-' +
                        (this.table_filter_fields.date_filter.getMonth() + 1);
                }
                const table = $('#tbl-monthly-payment');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = '/api/ap/monthly-payment';
            },
            async deleteData(id) {
                const component = this;

                let result = await this.$swal.fire({
                    title: 'Delete Check Voucher',
                    text: 'Do you really want to delete this check voucher?',
                    type: 'question',
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
                                    'Delete Check Voucher',
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
                    type: 'question',
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
            },

            generatePDFReport() {
                const url = '/api/reports/ap/monthly-payment?date_filter=' + this.table_filter_fields.date_filter;
                window.open(url, '_blank')
            },

            generateCheckVoucherPDF(id) {
                const url = `/api/reports/ap/check-voucher/${id}`;
                window.open(url, '_blank')
            }
        }
    }
</script>
