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

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Payment Term"
                    description="Please select payment term.">
                <b-form-select v-model="form.payment_term_id"
                               :options="payment_term_opt"
                               :class="{ 'is-invalid': form.errors.has('payment_term_id') }"
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
                    label="Particulars"
                    description="Please enter particulars.">
                <b-form-textarea v-model="form.particulars"
                                 :class="{ 'is-invalid': form.errors.has('particulars') }"
                                 class="input-container"
                                 :maxlength="500"
                                 :rows="6"
                                 :max-rows="6">
                </b-form-textarea>
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
                    supplier_id: '',
                    amount: '',
                    payment_term_id: '',
                    particulars: '',
                }),
                supplier_opt: [{}],
                payment_term_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });

            axios.all([
                axios.get('/api/ap/utils/get_supplier'),
                axios.get('/api/ap/utils/get_payment_terms'),
            ]).then(axios.spread(function (supplier, payment_term) {
                component.supplier_opt = supplier.data;
                component.payment_term_opt = payment_term.data;
            }));

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/ap/check-payment-request/${id}`)
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
                        await this.form.patch(`/api/ap/check-payment-request/${this.id}`) : await this.form.post('/api/ap/check-payment-request');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-check-payment-request');
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
                this.id ? form_modal.text('Edit Check Payment Request') : form_modal.text('Add New Check Payment Request');
            }
        }
    }
</script>

<style scoped>

</style>