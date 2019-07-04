<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Supplier"
                    description="Please select supplier.">
                <b-form-select v-model="form.supplier_id"
                               :options="supplier_opt"
                               :class="{ 'is-invalid': form.errors.has('supplier_id') }"
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
                    label="Bank"
                    description="Please select bank.">
                <b-form-select v-model="form.bank"
                               :options="bank_opt"
                               id="form_bank_filter"
                               class="input-container mb-2"
                               @change="filterBankAccounts"
                               :class="{ 'is-invalid': form.errors.has('bank') }">
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
                    v-if="form.bank"
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
            <b-form-fieldset>
                <b-form-checkbox v-model="form.is_duration"
                                 class="input-container"
                                 value="Y"
                                 unchecked-value="N">With Duration
                </b-form-checkbox>
            </b-form-fieldset>
            <!-- end: title -->

            <div class="row" v-if="form.is_duration === 'Y'">
                <!-- start: title -->
                <!--label="Title"-->
                <div class="col-lg-4">
                    <b-form-fieldset
                            label="Duration From"
                            description="Please enter duration from.">
                        <b-datepicker v-model="form.duration_from"
                                      :class="{ 'is-invalid': form.errors.has('duration_from') }"
                                      placeholder="Click to select...">
                        </b-datepicker>
                        <has-error :form="form" field="name"/>
                    </b-form-fieldset>
                </div>
                <!-- end: title -->

                <!-- start: title -->
                <!--label="Title"-->
                <div class="col-lg-4">
                    <b-form-fieldset
                            label="Duration To"
                            description="Please enter duration to.">
                        <b-datepicker v-model="form.duration_to"
                                      :class="{ 'is-invalid': form.errors.has('duration_to') }"
                                      :disabled-dates="{ to: form.duration_from }"
                                      placeholder="Click to select...">
                        </b-datepicker>
                        <has-error :form="form" field="name"/>
                    </b-form-fieldset>
                </div>
                <!-- end: title -->
            </div>

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Remarks"
                    description="Please enter remarks.">
                <b-form-textarea v-model="form.remarks"
                                 :class="{ 'is-invalid': form.errors.has('remarks') }"
                                 class="input-container"
                                 :maxlength="150"
                                 :rows="6"
                                 :max-rows="6">
                </b-form-textarea>
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
                              class="input-container"
                              :maxlength="18"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- FREQUENCY -->
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Frequency"
                    description="Please select frequency.">
                <b-form-select v-model="form.frequency"
                               :options="frequency_opt"
                               :class="{ 'is-invalid': form.errors.has('frequency') }"
                               class="input-container">
                    <template slot="first">
                        <option value selected disabled>-- Please select an option --</option>
                    </template>
                </b-form-select>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <div v-if="form.frequency">
                <!-- WEEKLY DURATION -->
                <div v-if="form.frequency === 'W'">
                    <!-- start: title -->
                    <!--label="Title"-->
                    <b-form-group
                            label="Day of the Week"
                            description="Please select day of the week.">
                        <b-form-radio-group v-model="form.frequency_type.W.weekday"
                                            :options="week_opt"
                                            :class="{ 'is-invalid': form.errors.has('frequency_type.W.weekday') }"
                                            stacked>
                        </b-form-radio-group>
                    </b-form-group>
                    <has-error :form="form" field="name"/>
                    <!-- end: title -->
                </div>

                <!-- MONTHLY DURATION -->
                <div v-else-if="form.frequency === 'M'">
                    <!-- start: title -->
                    <!--label="Title"-->
                    <b-form-fieldset
                            label="Day of the Month"
                            description="Please select day of the month.">
                        <b-form-select v-model="form.frequency_type.M.day"
                                       :options="days_opt"
                                       :class="{ 'is-invalid': form.errors.has('frequency_type.M.day') }">
                            <template slot="first">
                                <option value selected disabled></option>
                            </template>
                        </b-form-select>
                        <has-error :form="form" field="name"/>
                    </b-form-fieldset>
                    <!-- end: title -->
                </div>

                <!-- QUARTERLY DURATION -->
                <div v-else-if="form.frequency === 'Q'">
                    <div v-for="(quarter, key) in quarter_opt">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                :label="quarter"
                                :description="'Please enter ' + quarter.toLowerCase() + '.'">
                            <b-datepicker v-model="form.frequency_type.Q[key]"
                                          :class="{ 'is-invalid': form.errors.has('frequency_type.Q.' + key) }"
                                          placeholder="Click to select...">
                            </b-datepicker>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>
                </div>

                <!-- SEMESTRAL DURATION -->
                <div v-else-if="form.frequency === 'S'">
                    <div v-for="(semester, key) in semester_opt">
                        <!-- start: title -->
                        <!--label="Title"-->
                        <b-form-fieldset
                                :label="semester"
                                :description="'Please enter ' + semester.toLowerCase() + '.'">
                            <b-datepicker v-model="form.frequency_type.S[key]"
                                          :class="{ 'is-invalid': form.errors.has('frequency_type.S.' + key) }"
                                          placeholder="Click to select...">
                            </b-datepicker>
                            <has-error :form="form" field="name"/>
                        </b-form-fieldset>
                        <!-- end: title -->
                    </div>
                </div>

                <!-- ANNUAL DURATION -->
                <div v-else-if="form.frequency === 'A'">
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- start: title -->
                            <!--label="Title"-->
                            <b-form-fieldset
                                    label="Month"
                                    description="Please select month.">
                                <b-form-select v-model="form.frequency_type.A.month"
                                               :options="month_opt"
                                               :class="{ 'is-invalid': form.errors.has('frequency_type.A.month') }">
                                    <template slot="first">
                                        <option value selected disabled></option>
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
                                    label="Day of the Month"
                                    description="Please select day of the month.">
                                <b-form-select v-model="form.frequency_type.A.day"
                                               :options="days_opt"
                                               :class="{ 'is-invalid': form.errors.has('frequency_type.A.day') }">
                                    <template slot="first">
                                        <option value selected disabled></option>
                                    </template>
                                </b-form-select>
                                <has-error :form="form" field="name"/>
                            </b-form-fieldset>
                            <!-- end: title -->
                        </div>
                    </div>
                </div>
            </div>

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
                selected: 1,
                form: new Form({
                    id: 0,
                    bank: '',
                    bank_account_id: '',
                    supplier_id: '',
                    document_no: '',
                    is_duration: 'N',
                    duration_from: '',
                    duration_to: '',
                    remarks: '',
                    amount: '',
                    frequency: '',
                    frequency_type: {
                        W: {weekday: ''},
                        M: {day: ''},
                        Q: {Q1: '', Q2: '', Q3: '', Q4: ''},
                        S: {SEM1: '', SEM2: '', SUMMER: '',},
                        A: {month: '', day: ''}
                    }
                }),
                bank_opt: [{}],
                bank_account_opt: [{}],
                supplier_opt: [{}],
                frequency_opt: [{}],
                week_opt: [{}],
                days_opt: [{}],
                quarter_opt: [{}],
                semester_opt: [{}],
                month_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/ap/utils/get_supplier'),
                axios.get('/api/ap/utils/get_frequency'),
                axios.get('/api/ap/utils/get_banks'),
            ]).then(axios.spread(function (supplier, frequency, bank) {
                component.supplier_opt = supplier.data;
                component.frequency_opt = frequency.data.frequency;
                component.week_opt = frequency.data.week;
                component.days_opt = frequency.data.days;
                component.quarter_opt = frequency.data.quarter;
                component.semester_opt = frequency.data.semester;
                component.month_opt = frequency.data.month;
                component.bank_opt = bank.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            filterBankAccounts(){
                const component = this;

                this.form.bank = $('#form_bank_filter').val();
                axios.get(`/api/ap/utils/get_bank_accounts/${this.form.bank}`)
                    .then(function (response) {
                        component.bank_account_opt = response.data;
                    });
            },
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/recurring-payment/${id}`)
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
                        await this.form.patch(`/api/ap/recurring-payment/${this.id}`) : await this.form.post('/api/ap/recurring-payment');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-recurring-payment');
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
                this.id ? form_modal.text('Edit Recurring Payment') : form_modal.text('Add New Recurring Payment');
            }
        }
    }
</script>

<style scoped>

</style>