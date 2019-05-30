<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal2" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Code"
                    description="Please enter account code.">
                <b-form-input v-model="form.acct_code" disabled
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('acct_code') }"
                              type="text"
                              class="input-container"
                              :maxlength="20"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Account Number"
                    description="Please enter account number.">
                <b-form-input v-model="form.acct_no" disabled
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('acct_no') }"
                              type="text"
                              class="input-container"
                              :maxlength="20"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Beginning Balance"
                    description="Please enter beginning balance.">
                <b-form-input v-model="form.beginning_balance"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('beginning_balance') }"
                              type="number"
                              class="input-container"
                              :maxlength="18"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Beginning Balance as of"
                    description="Please enter as of.">
                    <b-datepicker
                            v-model="form.as_of"
                            :class="{ 'is-invalid': form.errors.has('as_of') }"
                            placeholder="Click to select...">
                    </b-datepicker>
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
                    acct_no: '',
                    beginning_balance: '',
                    as_of: '',
                }),
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            this.$root.$on('beginning_bal', (id) => {
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
                        component.$root.$emit('bv::show::modal', 'form_modal2');
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
                        await this.form.patch(`/api/ap/bank-account/${this.id}/update_beginning_bal`) : await this.form.post('/api/ap/bank-account');

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
            },
            formClose() {
                this.$root.$emit('bv::hide::modal', 'form_modal2');
            },
            formTitle() {
                const form_modal2 = $('#form_modal2').find('.modal-title');
                form_modal2.text('Beginning Balance');
            }
        }
    }
</script>

<style scoped>

</style>