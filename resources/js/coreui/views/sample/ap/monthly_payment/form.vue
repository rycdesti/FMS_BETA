<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <fieldset class="border p-2">
                <legend class="w-auto text-primary font-weight-bold">Supplier Information</legend>
                <!-- start: title -->
                <!--label="Title"-->
                <b-form-fieldset
                        label="Pay To">
                    <b-form-input v-model="form.supplier.check_name"
                                  autocomplete="off"
                                  type="text"
                                  class="input-container"
                                  disabled></b-form-input>
                </b-form-fieldset>
                <!-- end: title -->

                <!-- start: title -->
                <!--label="Title"-->
                <b-form-fieldset
                        label="Address">
                    <b-form-input v-model="form.supplier.address"
                                  autocomplete="off"
                                  type="text"
                                  class="input-container"
                                  disabled></b-form-input>
                </b-form-fieldset>
                <!-- end: title -->

                <!-- start: title -->
                <!--label="Title"-->
                <b-form-fieldset
                        label="Bank"
                        description="Please select bank.">
                    <b-form-select v-model="form.voucher.bank_account_id"
                                   :options="bank_opt"
                                   :class="{ 'is-invalid': form.errors.has('voucher.bank_account_id') }"
                                   id="bank_account_id"
                                   class="input-container"
                                   @change="getChecks">
                        <template slot="first">
                            <option value selected disabled>-- Please select an option --</option>
                        </template>
                    </b-form-select>
                    <has-error :form="form" field="name"/>
                </b-form-fieldset>
                <!-- end: title -->

                <div class="row" v-if="form.voucher.bank_account_id">
                    <div class="col-lg-4">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                label="Check Number"
                                description="Please select check number.">
                            <b-form-select v-model="form.voucher.check_id"
                                           :options="check_opt"
                                           :class="{ 'is-invalid': form.errors.has('voucher.check_id') }"
                                           class="input-container">
                                <template slot="first">
                                    <option value selected disabled>-- Please select an option --</option>
                                </template>
                            </b-form-select>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>

                    <div class="col-lg-4">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                label="Check Date"
                                description="Please enter check date.">
                            <b-datepicker v-model="form.voucher.check_date"
                                          :class="{ 'is-invalid': form.errors.has('voucher.check_date') }"
                                          placeholder="Click to select...">
                            </b-datepicker>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                label="Document Type"
                                description="Please select document type.">
                            <b-form-select v-model="form.voucher.document_type"
                                           :options="document_type_opt"
                                           :class="{ 'is-invalid': form.errors.has('voucher.document_type') }"
                                           class="input-container">
                                <template slot="first">
                                    <option value selected disabled>-- Please select an option --</option>
                                </template>
                            </b-form-select>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>

                    <div class="col-lg-4">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                label="Document Number"
                                description="Please enter document number.">
                            <b-form-input v-model="form.voucher.document_no"
                                          autocomplete="off"
                                          :class="{ 'is-invalid': form.errors.has('voucher.document_no') }"
                                          type="text"
                                          class="input-container"
                                          :maxlength="30"></b-form-input>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>
                </div>

                <!-- start: title -->
                <!--label="Title"-->
                <b-form-fieldset
                        label="Amount">
                    <b-form-input v-model="form.voucher.amount"
                                  autocomplete="off"
                                  type="number"
                                  class="input-container"
                                  disabled></b-form-input>
                    <has-error :form="form" field="name"/>
                </b-form-fieldset>
                <!-- end: title -->

                <!-- start: title -->
                <!--label="Title"-->
                <b-form-fieldset
                        label="Explanation"
                        description="Please enter explanation.">
                    <b-form-textarea v-model="form.voucher.explanation"
                                     :class="{ 'is-invalid': form.errors.has('voucher.explanation') }"
                                     class="input-container"
                                     :maxlength="150"
                                     :rows="6"
                                     :max-rows="6">
                    </b-form-textarea>
                    <has-error :form="form" field="name"/>
                </b-form-fieldset>
                <!-- end: title -->
            </fieldset>

            <fieldset class="border p-2 mt-4">
                <b-button class="mb-2"
                          title="Add New Row"
                          variant="outline-primary"
                          @click="addDistribution">
                    <i class="fa fa-plus"></i>
                </b-button>

                <legend class="w-auto text-primary font-weight-bold">Distribution</legend>
                <table class="table table-hover full-width">
                    <thead>
                    <tr>
                        <th width="40%">Account Number</th>
                        <th width="25%">Debit</th>
                        <th width="25%">Credit</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(distribution, index) in form.recurring_payment_distributions" :key="index">
                        <td width="50%">
                            <b-form-select v-model="distribution.chart_of_account_id"
                                           :options="chart_account_opt"
                                           class="input-container">
                                <template slot="first">
                                    <option value selected disabled>-- Please select an option --</option>
                                </template>
                            </b-form-select>
                        </td>
                        <td width="20%">
                            <div v-if="distribution.typical_balance === 'D'">
                                <b-form-input :value="distribution.amount"
                                              type="number"
                                              min="0"
                                              class="input-container text-right"
                                              @change="val => { distribution.amount = val }"
                                              v-on:change="recomputeChange(distribution, 'D')">
                                </b-form-input>
                            </div>
                            <div v-else-if="distribution.typical_balance === ''">
                                <b-form-input :value="distribution.amount"
                                              type="number"
                                              min="0"
                                              class="input-container text-right"
                                              @change="val => { distribution.amount = val }"
                                              v-on:change="recomputeChange(distribution, 'D')">
                                </b-form-input>
                            </div>
                        </td>
                        <td width="20%">
                            <div v-if="distribution.typical_balance === 'C'">
                                <b-form-input :value="distribution.amount"
                                              type="number"
                                              min="0"
                                              class="input-container text-right"
                                              @change="val => { distribution.amount = val }"
                                              v-on:change="recomputeChange(distribution, 'C')">
                                </b-form-input>
                            </div>
                            <div v-else-if="distribution.typical_balance === ''">
                                <b-form-input :value="distribution.amount"
                                              type="number"
                                              min="0"
                                              class="input-container text-right"
                                              @change="val => { distribution.amount = val }"
                                              v-on:change="recomputeChange(distribution, 'C')">
                                </b-form-input>
                            </div>
                        </td>
                        <td width="10%">
                            <button type="button" class="btn btn-outline-danger"
                                    @click="deleteDistribution(index, distribution)"><i class="fa fa-trash-o"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="text-right">Total</td>
                        <td class="text-right">{{ form.debit_total }}</td>
                        <td class="text-right">{{ form.credit_total }}</td>
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
            'data',
        ],
        data() {
            return {
                form: new Form({
                    supplier: {
                        check_name: '',
                        address: '',
                    },
                    voucher: {
                        id: '',
                        date: '',
                        recurring_payment_id: '',
                        status: '',
                        bank_account_id: '',
                        check_id: '',
                        old_check_id: '',
                        check_date: '',
                        document_type: '',
                        document_no: '',
                        amount: '',
                        explanation: '',
                        voucher_distributions: [{}]
                    },
                    recurring_payment_distributions: [{}],
                    debit_total: 0.0,
                    credit_total: 0.0,
                }),
                bank_opt: [{}],
                check_opt: [{}],
                document_type_opt: [{}],
                chart_account_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/ap/utils/get_banks'),
                axios.get('/api/ap/utils/get_document_type'),
                axios.get('/api/financial/utils/get_chart_account'),
            ]).then(axios.spread(function (bank, document_type, chart_account) {
                component.bank_opt = bank.data;
                component.document_type_opt = document_type.data;
                component.chart_account_opt = chart_account.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                const date = id.split("&")[1];

                axios.get(`/api/ap/monthly-payment/${id}`)
                    .then(function (response) {
                        component.check_opt = undefined;
                        component.form.fill(response.data);
                        if (component.form.voucher === null) {
                            component.form.voucher = {
                                date: date,
                                recurring_payment_id: response.data.id,
                                status: '',
                                bank_account_id: '',
                                check_id: '',
                                check_date: '',
                                document_type: '',
                                document_no: '',
                                amount: response.data.amount,
                                explanation: '',
                            };
                        } else {
                            component.form.voucher.old_check_id = component.form.voucher.check_id;
                            component.form.recurring_payment_distributions = component.form.voucher.voucher_distributions;
                            component.getVoucherChecks(component.form.voucher.bank_account_id + '&' + component.form.voucher.check_id);
                        }
                        component.$root.$emit('bv::show::modal', 'form_modal');
                        component.computeTotal();
                    })
                    .catch(function (error) {
                    })
                    .finally(function () {
                    })
            },
            getChecks() {
                const component = this;

                axios.get(`/api/ap/utils/get_checks/${$('#bank_account_id').val()}`)
                    .then(function (response) {
                        component.check_opt = response.data;
                    })
            },
            getVoucherChecks(bank_account_id) {
                const component = this;

                axios.get(`/api/ap/utils/get_voucher_checks/${bank_account_id}`)
                    .then(function (response) {
                        component.check_opt = response.data;
                    })
            },
            addDistribution() {
                this.form.recurring_payment_distributions.push({
                    chart_of_account_id: '',
                    typical_balance: '',
                    amount: '',
                });
            },
            deleteDistribution(index, distribution) {
                var idx = this.form.recurring_payment_distributions.indexOf(distribution);

                if (idx > -1) {
                    this.form.recurring_payment_distributions.splice(idx, 1);
                }

                this.computeTotal();
            },
            recomputeChange(distribution, typical_balance) {
                if (distribution.amount <= 0 || distribution.amount === '' || distribution.amount === '0') {
                    distribution.typical_balance = '';
                } else {
                    distribution.typical_balance = typical_balance;
                }

                this.computeTotal();
            },
            computeTotal() {
                const component = this;
                let debit_amount = 0.0;
                let credit_amount = 0.0;

                $.each(this.form.recurring_payment_distributions, function (index, value) {
                    if (value.typical_balance === 'D') {
                        const distribution_amount = parseFloat(value.amount);
                        if (!isNaN(distribution_amount)) {
                            debit_amount += distribution_amount;
                        }
                    } else {
                        const distribution_amount = parseFloat(value.amount);
                        if (!isNaN(distribution_amount)) {
                            credit_amount += distribution_amount;
                        }
                    }
                });

                component.form.debit_total = debit_amount.toFixed(2);
                component.form.credit_total = credit_amount.toFixed(2);
            },
            formSubmit: async function () {
                const is_update = !!this.form.voucher.id;
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
                        await this.form.patch(`/api/ap/monthly-payment/${this.form.voucher.id}`) : await this.form.post('/api/ap/monthly-payment');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-monthly-payment');
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

                if (this.form.voucher.status === 'O') {
                    form_modal.text('Review Check Voucher')
                } else if (this.form.voucher.status === 'R') {
                    form_modal.text('Recommend Check Voucher')
                } else if (this.form.voucher.status === 'F') {
                    form_modal.text('Approve Check Voucher')
                } else {
                    form_modal.text('Create Check Voucher')
                }
            }
        }
    }
</script>

<style scoped>

</style>