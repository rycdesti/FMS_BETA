<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Supplier Name"
                    description="Please enter supplier name.">
                <b-form-input v-model="form.name"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('name') }"
                              type="text"
                              class="input-container"
                              :maxlength="65"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Supplier Cheque Name"
                    description="Please enter supplier cheque name.">
                <b-form-input v-model="form.check_name"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('check_name') }"
                              type="text"
                              class="input-container"
                              :maxlength="65"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Tax Identification Number"
                    description="Please enter tax identification number.">
                <b-form-input v-model="form.tin"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('tin') }"
                              type="number"
                              class="input-container"
                              :maxlength="18"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Supplier Classification"
                    description="Please select supplier classification.">
                <b-form-select v-model="form.supplier_classification_id"
                               :options="supplier_classification_opt"
                               :class="{ 'is-invalid': form.errors.has('supplier_classification_id') }"
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
                    label="Currency"
                    description="Please select currency.">
                <b-form-select v-model="form.currency_id"
                               :options="currency_opt"
                               :class="{ 'is-invalid': form.errors.has('currency_id') }"
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
                    label="Address"
                    description="Please enter address.">
                <b-form-input v-model="form.address"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('address') }"
                              type="text"
                              class="input-container"
                              :maxlength="50"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <div class="row">
                <div class="col-lg-4">
                    <!-- start: title -->
                    <!--label="Title"-->
                    <b-form-fieldset
                            label="Country"
                            description="Please select country.">
                        <b-form-select v-model="form.country"
                                       :class="{ 'is-invalid': form.errors.has('country') }"
                                       name="country"
                                       id="countryId"
                                       class="input-container countries order-alpha">
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
                            label="State"
                            description="Please select state.">
                        <b-form-select v-model="form.state"
                                       :class="{ 'is-invalid': form.errors.has('state') }"
                                       name="state"
                                       id="stateId"
                                       class="input-container states order-alpha"
                                       :maxlength="150">
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
                            label="City"
                            description="Please select city.">
                        <b-form-select v-model="form.city"
                                       :class="{ 'is-invalid': form.errors.has('city') }"
                                       name="city"
                                       id="cityId"
                                       class="input-container cities order-alpha"
                                       :maxlength="150">
                            <template slot="first">
                                <option value selected disabled>-- Please select an option --</option>
                            </template>
                        </b-form-select>
                        <has-error :form="form" field="name"/>
                    </b-form-fieldset>
                    <!-- end: title -->
                </div>
            </div>

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Zip Code"
                    description="Please enter zip code.">
                <b-form-input v-model="form.zip_code"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('zip_code') }"
                              type="text"
                              class="input-container"
                              :maxlength="10"></b-form-input>
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
            'data'
        ],
        data() {
            return {
                form: new Form({
                    id: 0,
                    name: '',
                    check_name: '',
                    tin: '',
                    supplier_classification_id: '',
                    currency_id: '',
                    address: '',
                    country: '',
                    state: '',
                    city: '',
                    zip_code: '',
                }),
                supplier_classification_opt: [{}],
                currency_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/requisition/utils/get_supplier_classification'),
                axios.get('/api/requisition/utils/get_currency')
            ]).then(axios.spread(function (supplier_classification, currency) {
                component.supplier_classification_opt = supplier_classification.data;
                component.currency_opt = currency.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        updated() {
            this.$root.$country_state_city.load(this);
        },
        beforeDestroy() {
            this.$root.$country_state_city.unload(this);
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/requisition/supplier/${id}`)
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
                        await this.form.patch(`/api/requisition/supplier/${this.id}`) : await this.form.post('/api/requisition/supplier');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-supplier');
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
                this.id ? form_modal.text('Edit Chart of Account') : form_modal.text('Add New Chart of Account');
            }
        }
    }
</script>

<style scoped>

</style>