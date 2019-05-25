<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <call_out_form :bank_account_id="table_filter_fields.bank_account_filter"/>
            <call_out_form2 :bank_account_id="table_filter_fields.bank_account_filter"/>

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Check Booklets</strong>
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

                              <!--v-b-modal.form_modal2-->
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
                                        <label class="mr-2">Bank Account:</label>
                                        <b-form-select v-model="table_filter_fields.bank_account_filter"
                                                       :options="bank_opt"
                                                       id="bank_account_filter"
                                                       class="input-container mb-2"
                                                       @change="getChecks">
                                            <template slot="first">
                                                <option value selected disabled>-- Please select an option --</option>
                                            </template>
                                        </b-form-select>
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
    import CallOutForm from '@/views/sample/ap/check/form'
    import CallOutForm2 from '@/views/sample/ap/check/form2'

    export default {
        name: 'Check',
        components: {
            'call_out_form': CallOutForm,
            'call_out_form2': CallOutForm2,
        },
        data() {
            return {
                table_id: 'tbl-check',
                table_columns: [
                    {data: 'acct_no', bSortable: false, bSearchable: false},
                    {data: 'check_from'},
                    {data: 'check_to'},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false},
                ],
                table_headers: [
                    'Account Number',
                    'Check Sequence From',
                    'Check Sequence To',
                    'Logs',
                    'Actions'
                ],
                table_url: '',
                data: '',
                table_filter_fields: {
                    bank_account_filter: 0,
                },
                bank_opt: [{}],
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

            $(document).on('click', '#btn-view-check', function () {
                component.$root.$emit('view_check', $(this).data('id'));
            });

            axios.get('/api/ap/utils/get_banks')
                .then(function (response) {
                    component.bank_opt = response.data;
                });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-delete', '#btn-view-check'],
                ['view_check'],
                this.$root
            );
        },
        methods: {
            getChecks() {
                this.table_filter_fields.bank_account_filter = $('#bank_account_filter').val();
                const table = $('#tbl-check');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = `/api/ap/check`;
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
                    axios.delete(`/api/ap/check/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-check');
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
                    axios.patch(`/api/ap/check/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-check');
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
              const url = `/api/reports/ap/check/${this.bank_account_id}`
              window.open(url, '_blank')
            },
        }
    }
</script>
