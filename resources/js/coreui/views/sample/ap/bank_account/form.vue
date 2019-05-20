<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Bank Address"
                    description="Please enter bank address.">
                <b-form-input v-model="form.bank_address"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('bank_address') }"
                              type="text"
                              name="bank_address"
                              class="input-container"
                              :maxlength="50"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Code"
                    description="Please enter account code.">
                <b-form-input v-model="form.acct_code"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('acct_code') }"
                              type="text"
                              name="acct_code"
                              class="input-container"
                              :maxlength="50"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Number"
                    description="Please enter account number.">
                <b-form-input v-model="form.acct_no"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('acct_no') }"
                              type="text"
                              name="acct_no"
                              class="input-container"
                              :maxlength="50"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Type"
                    description="Please select account type.">
                <b-form-select v-model="form.acct_type"
                               :options="acct_type_opt"
                               :class="{ 'is-invalid': form.errors.has('acct_type') }"
                               name="acct_type"
                               class="input-container"
                               :maxlength="50">
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
                    label="Currency"
                    description="Please select currency.">
                <b-form-select v-model="form.currency_id"
                               :options="currency_opt"
                               :class="{ 'is-invalid': form.errors.has('currency_id') }"
                               name="currency_id"
                               class="input-container"
                               :maxlength="50">
                    <template slot="first">
                        <option value selected disabled>-- Please select an option --</option>
                    </template>
                </b-form-select>
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
            'bank_id',
        ],
        data() {
            return {
                form: new Form({
                    id: 0,
                    bank_id: this.bank_id,
                    bank_address: '',
                    acct_code: '',
                    acct_no: '',
                    acct_type: '',
                    currency_id: '',
                }),
                acct_type_opt: [{}],
                currency_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/ap/utils/get_acct_type'),
                axios.get('/api/ap/utils/get_currency'),
            ]).then(axios.spread(function (acct_type, currency) {
                component.acct_type_opt = acct_type.data;
                component.currency_opt = currency.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/bank-account/${id}`)
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
                        await this.form.patch(`/api/ap/bank-account/${this.id}`) : await this.form.post('/api/ap/bank-account');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-bank-account');
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
                this.id ? form_modal.text('Edit Bank Account') : form_modal.text('Add New Bank Account');
            }
        }
    }
</script>

<style scoped>

</style>