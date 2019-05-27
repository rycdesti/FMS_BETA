<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account"
                    description="Please select account.">
                <b-form-select v-model="form.chart_of_account_id"
                               :options="chart_account_opt"
                               :class="{ 'is-invalid': form.errors.has('chart_of_account_id') }"
                               name="chart_of_account_id"
                               class="input-container">
                    <template slot="first">
                        <option value selected disabled>-- Please select an option --</option>
                    </template>
                </b-form-select>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Type"
                    description="Please select account type.">
                <b-form-select v-model="form.typical_balance"
                               :options="typical_balance_opt"
                               :class="{ 'is-invalid': form.errors.has('typical_balance') }"
                               name="typical_balance"
                               class="input-container">
                    <template slot="first">
                        <option value selected disabled>-- Please select an option --</option>
                    </template>
                </b-form-select>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Amount"
                    description="Please enter amount.">
                <b-form-input v-model="form.amount"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('amount') }"
                              type="number"
                              name="amount"
                              class="input-container"
                              :maxlength="18"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <alert-errors :form="form" message="There were some problems with your input."/>

            <div class="mt-4">
                <b-button :disabled="form.busy" type="submit" size="sm" variant="primary"><i
                        class="fa fa-dot-circle-o"></i> Submit
                </b-button>
                <b-button type="reset" size="sm" variant="danger" @click="formClose"><i
                        class="fa fa-ban"></i> Cancel
                </b-button>
            </div>
        </b-form>
    </b-modal>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "form",
        props: [
            'data',
            'recurring_payment_id',
        ],
        data() {
            return {
                selected: 1,
                form: new Form({
                    id: 0,
                    recurring_payment_id: this.recurring_payment_id,
                    chart_of_account_id: '',
                    typical_balance: '',
                    amount: '',
                }),
                chart_account_opt: [{}],
                typical_balance_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/financial/utils/get_chart_account'),
                axios.get('/api/financial/utils/get_typical_balance'),
            ]).then(axios.spread(function (chart_account, typical_balance) {
                component.chart_account_opt = chart_account.data;
                component.typical_balance_opt = typical_balance.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/recurring-payment-distribution/${id}`)
                    .then(function (response) {
                        component.form.fill(response.data);
                        component.$root.$emit('bv::show::modal', 'form_modal');
                    })
                    .catch(function (error) {
                    })
                    .finally(function () {
                    })
            },
            formSubmit: async function () {
                const is_update = !!this.id;
                let result = await this.$swal.fire({
                    title: is_update ? 'Update Record' : 'Add New Record',
                    text: is_update ? 'Do you really want to update this record?' : 'Do you really want to add this record?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    const response = is_update ?
                        await this.form.patch(`/api/ap/recurring-payment-distribution/${this.id}`) : await this.form.post('/api/ap/recurring-payment-distribution');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-recurring-payment-distribution');
                            table.DataTable().draw(true);
                        });
                    }
                }
            },
            formReset() {
                this.form.reset();
                this.id = 0;
                this.form.errors.clear();
            },
            formClose() {
                this.$root.$emit('bv::hide::modal', 'form_modal');
            },
            formTitle() {
                const form_modal = $('#form_modal').find('.modal-title');
                this.id ? form_modal.text('Edit Recurring Payment Distribution') : form_modal.text('Add New Recurring Payment Distribution');
            }
        }
    }
</script>

<style scoped>

</style>