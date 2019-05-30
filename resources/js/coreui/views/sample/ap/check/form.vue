<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Bank Account"
                    description="Please select bank account.">
                <b-form-select v-model="form.bank_account_id"
                               :options="bank_opt"
                               class="input-container mb-2"
                               :class="{ 'is-invalid': form.errors.has('bank_account_id') }"
                               @change="filter">
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
                    label="Check Sequence From"
                    description="Please enter check sequence from.">
                <b-form-input v-model="form.check_from"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('check_from') }"
                              type="number"
                              class="input-container"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Check Sequence To"
                    description="Please enter check sequence to.">
                <b-form-input v-model="form.check_to"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('check_to') }"
                              type="number"
                              class="input-container"
                              :min="form.check_from"></b-form-input>
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
        ],
        data() {
            return {
                form: new Form({
                    bank_account_id: '',
                    check_from: '',
                    check_to: '',
                }),
                bank_opt: [{}],
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            axios.get('/api/ap/utils/get_banks')
                .then(function (response) {
                    component.bank_opt = response.data;
                });
        },
        methods: {
            formSubmit: async function () {
                let result = await this.$swal.fire({
                    title: 'Add New Record',
                    text: 'Do you really want to add this record?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#20a8d8',
                    cancelButtonColor: '#f86c6b',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                });

                if (result.value) {
                    const response = await this.form.post('/api/ap/check');

                    if (response.data.success) {
                        this.$swal.fire(
                            'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-check');
                            table.DataTable().draw(true);
                        });
                    }
                }
            },
            formReset() {
                this.form.reset();
                this.form.errors.clear();
            },
            formClose() {
                this.$root.$emit('bv::hide::modal', 'form_modal');
            },
            formTitle() {
                const form_modal = $('#form_modal').find('.modal-title');
                form_modal.text('Add New Check');
            }
        }
    }
</script>

<style scoped>

</style>