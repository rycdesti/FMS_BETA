<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Pay To">
                <b-form-input v-model="form.check_name"
                              autocomplete="off"
                              type="text"
                              name="check_name"
                              class="input-container"
                              disabled></b-form-input>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Address">
                <b-form-input v-model="form.address"
                              autocomplete="off"
                              type="text"
                              name="address"
                              class="input-container"
                              disabled></b-form-input>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Bank"
                    description="Please select account type.">
                <b-form-select v-model="form.bank_id"
                               :options="acct_type_opt"
                               :class="{ 'is-invalid': form.errors.has('bank_id') }"
                               name="bank"
                               class="input-container"
                               :maxlength="50">
                    <template slot="first">
                        <option value selected disabled>-- Please select an option --</option>
                    </template>
                </b-form-select>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <div class="row">
                <div class="col-lg-4">
                    <!-- start: title -->
                    <!--label="Title"-->
                    <b-form-fieldset
                            label="Check Number"
                            description="Please select check number.">
                        <b-form-select v-model="form.check_id"
                                       :options="acct_type_opt"
                                       :class="{ 'is-invalid': form.errors.has('check_id') }"
                                       name="check_number"
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
                        <b-datepicker v-model="form.check_date"
                                      :class="{ 'is-invalid': form.errors.has('check_date') }"
                                      name="check_date"
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
                        <b-form-select v-model="form.document_type"
                                       :options="acct_type_opt"
                                       :class="{ 'is-invalid': form.errors.has('document_type') }"
                                       name="document_type"
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
                        <b-form-input v-model="form.document_no"
                                      autocomplete="off"
                                      :class="{ 'is-invalid': form.errors.has('document_no') }"
                                      type="text"
                                      name="document_no"
                                      class="input-container"></b-form-input>
                        <has-error :form="form" field="name"/>
                    </b-form-fieldset>
                    <!-- end: title -->
                </div>
            </div>

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Amount">
                <b-form-input v-model="form.amount"
                              autocomplete="off"
                              type="number"
                              name="amount"
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
                <b-form-textarea v-model="form.explanation"
                                 :class="{ 'is-invalid': form.errors.has('explanation') }"
                                 name="explanation"
                                 class="input-container"
                                 :rows="6"
                                 :max-rows="6">
                </b-form-textarea>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->
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
                form: new Form({}),
                bank_opt: [{}],
                check_opt: [{}],
                document_type_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/ap/utils/get_supplier'),
                axios.get('/api/ap/utils/get_frequency'),
            ]).then(axios.spread(function (supplier, frequency) {
                component.supplier_opt = supplier.data;
                component.frequency_opt = frequency.data.frequency;
                component.week_opt = frequency.data.week;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/monthly-payment/${id}`)
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
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    const response = is_update ?
                        await this.form.patch(`/api/ap/monthly-payment/${this.id}`) : await this.form.post('/api/ap/monthly-payment');

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
                this.id ? form_modal.text('Edit Recurring Payment') : form_modal.text('Create Check Voucher');
            }
        }
    }
</script>

<style scoped>

</style>