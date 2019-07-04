<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Bank"
                    description="Please select bank.">
                <b-form-select v-model="form.bank_id"
                               :options="bank_opt"
                               id="form_bank_filter"
                               class="input-container mb-2"
                               @change="getBankAccounts"
                               :class="{ 'is-invalid': form.errors.has('bank_id') }">
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
                    v-if="form.bank_id"
                    label="Account Number"
                    description="Please select account number.">
                <b-form-select v-model="form.bank_account_id"
                               :options="bank_account_opt"
                               class="input-container mb-2"
                               :class="{ 'is-invalid': form.errors.has('bank_account_id') }">
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
                    label="Date Deposit"
                    description="Please enter date deposit.">
                <b-datepicker v-model="form.date_deposit"
                              :class="{ 'is-invalid': form.errors.has('date_deposit') }"
                              placeholder="Click to select...">
                </b-datepicker>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Time Deposit"
                    description="Please enter time deposit.">
                <b-form-input v-model="form.time_deposit"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('time_deposit') }"
                              type="time"
                              class="input-container"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Reference No"
                    description="Please enter reference no.">
                <b-form-input v-model="form.ref_no"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('ref_no') }"
                              type="text"
                              class="input-container"
                              :maxlength="70"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Cash Deposit"
                    description="Please enter cash deposit.">
                <b-form-input v-model="form.cash_deposit"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('cash_deposit') }"
                              type="number"
                              class="input-container"
                              :maxlength="18"
                              v-on:change="computeTotal"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <fieldset class="border p-2 mt-4">
                <b-button class="mb-2"
                          title="Add New Row"
                          variant="outline-primary"
                          @click="addDeposit">
                    <i class="fa fa-plus"></i>
                </b-button>

                <legend class="w-auto text-primary font-weight-bold">Check Deposit</legend>
                <table class="table table-hover full-width">
                    <thead>
                    <tr>
                        <th width="40%">Bank</th>
                        <th width="25%">Check No</th>
                        <th width="25%">Amount</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(deposit, index) in form.check_deposit" :key="index">
                        <td width="50%">
                            <b-form-select v-model="deposit.bank_id"
                                           :options="bank_opt"
                                           class="input-container"
                                           required>
                                <template slot="first">
                                    <option value selected disabled>-- Please select an option --</option>
                                </template>
                            </b-form-select>
                        </td>
                        <td width="20%">
                            <b-form-input v-model="deposit.check_no"
                                          type="number"
                                          min="0"
                                          class="input-container text-right"
                                          required>
                            </b-form-input>
                        </td>
                        <td width="20%">
                            <b-form-input v-model="deposit.amount"
                                          type="number"
                                          min="0"
                                          class="input-container text-right"
                                          v-on:change="computeTotal"
                                          required>
                            </b-form-input>
                        </td>
                        <td width="10%">
                            <button type="button" class="btn btn-outline-danger"
                                    @click="deleteDeposit(index, deposit)"><i class="fa fa-trash-o"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-right">Total Check Deposit</td>
                        <td class="text-right">{{ form.total_check_deposit }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Total Deposit</td>
                        <td class="text-right">{{ form.total_deposit }}</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </fieldset>

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
            'data'
        ],
        data() {
            return {
                form: new Form({
                    id: 0,
                    bank_id: '',
                    bank_account_id: '',
                    date_deposit: '',
                    time_deposit: '',
                    ref_no: '',
                    cash_deposit: '',
                    check_deposit: [],
                    total_check_deposit: 0.0,
                    total_deposit: 0.0,
                }),
                bank_opt: [{}],
                bank_account_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/ap/utils/get_banks'),
            ]).then(axios.spread(function (bank) {
                component.bank_opt = bank.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            getBankAccounts() {
                const component = this;

                this.form.bank_id = $('#form_bank_filter').val();
                axios.get(`/api/ap/utils/get_bank_accounts/${this.form.bank_id}`)
                    .then(function (response) {
                        component.form.bank_account_id = '';
                        component.bank_account_opt = response.data;
                    });
            },
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/bank-deposit/${id}`)
                    .then(function (response) {
                        component.form.fill(response.data);
                        component.$root.$emit('bv::show::modal', 'form_modal');
                    })
                    .catch(function (error) {
                    })
                    .finally(function () {
                    })
            },
            addDeposit() {
                this.form.check_deposit.push({
                    bank_id: '',
                    check_no: '',
                    amount: '',
                });
            },
            deleteDeposit(index, deposit) {
                var idx = this.form.check_deposit.indexOf(deposit);

                if (idx > -1) {
                    this.form.check_deposit.splice(idx, 1);
                }

                this.computeTotal();
            },
            computeTotal() {
                const component = this;
                let total_check_amount = 0.0;
                let total_amount = 0.0;

                $.each(this.form.check_deposit, function (index, value) {
                    const amount = parseFloat(value.amount);
                    if (!isNaN(amount)) {
                        total_check_amount += amount;
                    }
                });

                if (!isNaN(parseFloat(component.form.cash_deposit))) {
                    total_amount = total_check_amount + parseFloat(component.form.cash_deposit);
                } else {
                    total_amount = total_check_amount;
                }

                component.form.total_check_deposit = total_check_amount.toFixed(2);
                component.form.total_deposit = total_amount.toFixed(2);
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
                        await this.form.patch(`/api/ap/bank-deposit/${this.id}`) : await this.form.post('/api/ap/bank-deposit');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-bank-deposit');
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
                this.id ? form_modal.text('Edit Bank Deposit') : form_modal.text('Add New Bank Deposit');
            }
        }
    }
</script>

<style scoped>

</style>