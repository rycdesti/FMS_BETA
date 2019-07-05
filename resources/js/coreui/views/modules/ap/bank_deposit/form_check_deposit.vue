<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_check_deposit" size="lg"
             hide-footer no-enforce-focus
             class="modal-primary" no-close-on-backdrop>

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
                form_table_id: 'tbl-check-deposit',
                form_columns: [
                    {data: 'bank', bSortable: true, bSearchable: true},
                    {data: 'check_no', bSortable: true, bSearchable: true},
                    {data: 'amount', bSortable: false, bSearchable: false},
                ],
                form_headers: ['Bank', 'Check No', 'Amount'],
                form_url: '',
                form_filter_fields: {
                    status_filter: '',
                },
                form: new Form({
                    id: 0,
                }),
                is_open: false,
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            this.$root.$on('view_check_deposit', (bank_deposit_id) => {
                component.fetchData(bank_deposit_id);
            });
        },
        methods: {
            fetchData(bank_deposit_id) {
                const component = this;
                $.fn.modal.Constructor.prototype.enforceFocus = function () {
                };
                component.$root.$emit('bv::show::modal', 'form_check_deposit');
                const table = $('#tbl-check-deposit').DataTable();

                component.form_url = `/api/ap/bank-deposit/${bank_deposit_id}/get_check_deposit`;
                table.ajax.url(this.form_url);
                component.is_open = true;
                table.draw(true);
            },
            formReset() {
                this.form.reset();
                this.id = 0;
            },
            formClose() {
                this.$root.$emit('bv::hide::modal', 'form_check_deposit');
            },
            formTitle() {
                const form_modal2 = $('#form_check_deposit').find('.modal-title');
                form_modal2.text('Check Deposit');
            },
        }
    }
</script>

<style scoped>

</style>