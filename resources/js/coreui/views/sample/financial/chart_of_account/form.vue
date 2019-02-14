<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
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
                    label="Description"
                    description="Please enter description.">
                <b-form-input v-model="form.description"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('description') }"
                              type="text"
                              name="description"
                              class="input-container"
                              :maxlength="50"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Category"
                    description="Please select account category.">
                <b-form-select v-model="form.account_category_id"
                               :options="acct_category_opt"
                               :class="{ 'is-invalid': form.errors.has('account_account_category_id') }"
                               name="account_category_id"
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
                    label="Posting Type"
                    description="Please select posting type.">
                <b-form-select v-model="form.posting_type"
                               :options="posting_type_opt"
                               :class="{ 'is-invalid': form.errors.has('posting_type') }"
                               name="posting_type"
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
                    label="Typical Balance"
                    description="Please select typical balance.">
                <b-form-select v-model="form.typical_balance"
                               :options="typical_balance_opt"
                               :class="{ 'is-invalid': form.errors.has('typical_balance') }"
                               name="typical_balance"
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
            'data'
        ],
        data() {
            return {
                form: new Form({
                    id: 0,
                    acct_code: '',
                    description: '',
                    account_category_id: '',
                    posting_type: '',
                    typical_balance: '',
                }),
                acct_category_opt: [{}],
                posting_type_opt: [{}],
                typical_balance_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.all([
                axios.get('/api/financial/utils/get_acct_category'),
                axios.get('/api/financial/utils/get_posting_type'),
                axios.get('/api/financial/utils/get_typical_balance'),
            ]).then(axios.spread(function (acct_category, posting_type, typical_balance) {
                component.acct_category_opt = acct_category.data;
                component.posting_type_opt = posting_type.data;
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

                axios.get(`/api/financial/chart-of-account/${id}`)
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
                        await this.form.patch(`/api/financial/chart-of-account/${this.id}`) : await this.form.post('/api/financial/chart-of-account');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-chart-of-account');
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