<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <call_out_form :bank_id="this.bank_id"/>
            <call_out_form2/>

            <b-row>
                <b-col cols="12">
                    <b-card
                            header-tag="header"
                            footer-tag="footer"
                    >
                        <div slot="header">
                            <i class="fa fa-align-justify"/> <strong>List of Bank Accounts</strong>
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
                                        title="Back to Bank List"
                                        @click="$router.back()"
                                        variant="outline-primary"
                                >
                                    <i class="fa fa-arrow-left"/>
                                </b-button>

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
    import CallOutForm from '@/views/modules/ap/bank_account/form'
    import CallOutForm2 from '@/views/modules/ap/bank_account/form2'

    export default {
        name: 'BankAccount',
        props: ['bank_id'],
        components: {
            'call_out_form': CallOutForm,
            'call_out_form2': CallOutForm2,
        },
        data() {
            return {
                table_id: 'tbl-bank-account',
                table_columns: [
                    {data: 'bank_address'},
                    {data: 'account_info', bSortable: false, bSearchable: false},
                    {data: 'status', bSortable: false, bSearchable: false},
                    {data: 'logs', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false},
                ],
                table_headers: [
                    'Bank Address',
                    'Account Information',
                    'Status',
                    'Logs',
                    'Actions'
                ],
                table_url: '',
                table_filter_fields: {
                    status_filter: ''
                },
                data: '',
                status_opt: {
                    'N': 'Enabled',
                    'Y': 'Disabled'
                }
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

                // var table = $('#tbl-bank-account').DataTable();
                // var node = table.row(`#row-${id}`).data();
            });

            $(document).on('click', '#btn-beginning-bal', function () {
                component.$root.$emit('beginning_bal', $(this).data('id'));
            });

            $(document).on('click', '#btn-check-booklet', function () {
                const id = $(this).data('id');
                component.$router.push({name: 'Check', params: {bank_account_id: id}});
            });
        },
        beforeDestroy() {
            this.$root.$listener.destroy(
                ['#btn-edit', '#btn-delete', '#btn-update-status', '#btn-beginning_bal'],
                ['edit', 'beginning_bal'],
                this.$root
            );
        },
        methods: {
            filter() {
                this.table_filter_fields.status_filter = $('#status_filter').val();
                const table = $('#tbl-bank-account');
                table.DataTable().draw(false);
            },
            fetchData() {
                this.table_url = `/api/ap/bank-account/${this.bank_id}`;
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
                    axios.delete(`/api/ap/bank-account/${id}`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Delete Record',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-bank-account');
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
                    axios.patch(`/api/ap/bank-account/${id}/update_status`)
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    status,
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-bank-account');
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
              const url = `/api/reports/ap/bank-account/${this.bank_id}`
              window.open(url, '_blank')
            },
        }
    }
</script>
