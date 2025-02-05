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
                            <i class="fa fa-align-justify"/> <strong>List of Monthly Payments for Recommendation</strong>
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
                                        v-if="data.items_checked > 0"
                                        v-b-tooltip.hover
                                        variant="outline-primary"
                                        @click="submitRecommendation"
                                >
                                    Submit for Recommendation
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
                                    :filter_fields="table_filter_fields"/>
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
    export default {
        name: 'MonthlyPaymentReview',
        data() {
            return {
                table_id: 'tbl-monthly-payment',
                table_columns: [
                    {data: 'actions', bSortable: false, bSearchable: false},
                    {data: 'supplier_info', bSortable: false, bSearchable: true},
                    {data: 'amount_date', bSortable: false, bSearchable: false},
                    {data: 'voucher_info', bSortable: false, bSearchable: true},
                    {data: 'distribution_info', bSortable: false, bSearchable: true},
                ],
                table_headers: [
                    '',
                    'Supplier Information',
                    'Amount/Date',
                    'Voucher Information',
                    'Distribution',
                ],
                table_url: '',
                table_filter_fields: {
                    frequency_filter: '',
                    status_filter: 'R',
                    period_from_filter: moment(new Date(new Date().getFullYear(), new Date().getMonth(), 1)).format('YYYY-MM-DD'),
                    period_to_filter: moment(new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)).format('YYYY-MM-DD'),
                },
                data: {
                    items_checked: 0,
                },
                frequency_opt: [{}],
            }
        },
        created() {
            this.fetchData();
        },
        mounted() {
            const component = this;

            $(document).on('change', '#checkbox-item', function () {
                $(this).closest("tr").toggleClass("bg-gray-200", this.checked);
                component.data.items_checked = $(':checkbox:checked').length
            });

            axios.get('/api/ap/utils/get_frequency')
                .then(function (response) {
                    component.frequency_opt = response.data.frequency;
                });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#checkbox-item'],
                [],
                this.$root
            );
        },
        methods: {
            submitRecommendation: async function () {
                const component = this;

                let batch_id = $(':checkbox:checked').map(function () {
                    return $(this).data('id')
                }).get().join();

                let result = await this.$swal.fire({
                    title: 'Submit for Approval',
                    text: 'Do you really want to submit these record/s for approval?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    await axios.patch(`/api/ap/monthly-payment/batch/${batch_id}/${component.table_filter_fields.status_filter}`).then(function (response) {
                        if (response.data.success) {
                            component.$swal.fire(
                                'Submit for Approval',
                                response.data.message,
                                'success'
                            ).then(() => {
                                const table = $('#tbl-monthly-payment');
                                table.DataTable().draw(true);
                            });
                        }
                    });
                }
            },

            filter() {
                this.table_filter_fields.frequency_filter = $('#frequency_filter').val();
                if (this.table_filter_fields.period_from_filter instanceof Date) {
                    this.table_filter_fields.period_from_filter = this.table_filter_fields.period_from_filter.getFullYear() + '-' +
                        (this.table_filter_fields.period_from_filter.getMonth() + 1) + '-' + this.table_filter_fields.period_from_filter.getDate();
                }

                if (this.table_filter_fields.period_to_filter instanceof Date) {
                    this.table_filter_fields.period_to_filter = this.table_filter_fields.period_to_filter.getFullYear() + '-' +
                        (this.table_filter_fields.period_to_filter.getMonth() + 1) + '-' + this.table_filter_fields.period_to_filter.getDate();
                }
                const table = $('#tbl-monthly-payment');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = '/api/ap/monthly-payment/batch';
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
