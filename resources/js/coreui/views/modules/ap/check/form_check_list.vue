<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal2" size="xl"
             hide-footer no-enforce-focus
             class="modal-primary" no-close-on-backdrop>

        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="float-right form-inline">
                    <label class="mr-2">Status:</label>
                    <b-form-select v-model="form_filter_fields.status_filter"
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

        <datatable v-if="is_open"
                   :id="form_table_id"
                   :headers="form_headers"
                   :columns="form_columns"
                   :url="form_url"
                   :filter_fields="form_filter_fields"></datatable>

        <b-button type="reset" size="sm" variant="danger" @click="formClose"><i
                class="fa fa-ban"></i> Cancel
        </b-button>
    </b-modal>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "form",
        props: [
            'data',
        ],
        data() {
            return {
                form_table_id: 'tbl-check-list',
                form_columns: [
                    {data: 'check_no'},
                    {data: 'voucher_no'},
                    {data: 'status', bSortable: false, bSearchable: false},
                    {data: 'remarks', bSortable: false, bSearchable: false},
                    {data: 'actions', bSortable: false, bSearchable: false},
                ],
                form_headers: ['Check Number', 'Voucher Number', 'Status', 'Remarks', 'Actions'],
                form_url: '',
                form_filter_fields: {
                    status_filter: '',
                },
                form: new Form({
                    id: 0,
                }),
                is_open: false,
                status_opt: {
                    'I': 'Issued Check',
                    'N': 'Blank Check',
                    'Y': 'Voided Check',
                }
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            this.$root.$on('view_check', (sequence) => {
                component.fetchData(sequence);
            });
        },
        beforeUpdate() {
            $(`#${this.form_table_id} tbody`).off('click', 'td #btn-void-check');
        },
        updated() {
            const component = this;

            $(`#${this.form_table_id} tbody`).on('click', 'td #btn-void-check', function () {
                component.voidCheck($(this).data('id'));
            })
        },
        methods: {
            filter() {
                this.form_filter_fields.status_filter = $('#status_filter').val();
                const table = $('#tbl-check-list');
                table.DataTable().draw(false);
            },
            fetchData(sequence) {
                const component = this;
                $.fn.modal.Constructor.prototype.enforceFocus = function () {
                };
                component.$root.$emit('bv::show::modal', 'form_modal2');
                const table = $('#tbl-check-list').DataTable();

                component.form_url = `/api/ap/check/${sequence}/get_check_list`;
                table.ajax.url(this.form_url);
                component.is_open = true;
                table.draw(true);
            },
            async voidCheck(id) {
                const component = this;

                let {value: text} = await this.$swal.fire({
                    title: 'Void Check',
                    text: 'Do you really want to void this check?',
                    type: 'question',
                    input: 'text',
                    inputPlaceholder: 'Reason for voiding',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    inputValidator: (text) => {
                        return !text && 'Reason for voiding is required!'
                    }
                });

                if (text) {
                    axios.patch(`/api/ap/check/${id}`, {remarks: text})
                        .then(function (response) {
                            if (response.data.success) {
                                component.$swal.fire(
                                    'Void Check',
                                    response.data.message,
                                    'success'
                                ).then(() => {
                                    const table = $('#tbl-check-list');
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
            formReset() {
                this.form.reset();
                this.id = 0;
            },
            formClose() {
                this.$root.$emit('bv::hide::modal', 'form_modal2');
            },
            formTitle() {
                const form_modal2 = $('#form_modal2').find('.modal-title');
                form_modal2.text('List of Checks');
            },
        }
    }
</script>

<style scoped>

</style>