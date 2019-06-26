<template>
    <b-modal @hide="formReset" @show="formTitle" centered id="form_modal" size="lg"
             hide-footer
             class="modal-primary" no-close-on-backdrop>
        <b-form @submit.prevent="formSubmit" @keydown="form.onKeydown($event)">
            <!-- start: title -->
            <!--label="Title"-->
            <b-form-fieldset
                    label="Payment Term Name"
                    description="Please enter payment term name.">
                <b-form-input v-model="form.payment_term_name"
                              autocomplete="off"
                              :class="{ 'is-invalid': form.errors.has('payment_term_name') }"
                              type="text"
                              class="input-container"
                              :maxlength="100"></b-form-input>
                <has-error :form="form" field="name"/>
            </b-form-fieldset>
            <!-- end: title -->

            <div class="row">
                <div class="col-md-7">
                    <fieldset class="border p-2">
                        <b-button class="mb-2"
                                  title="Add New Row"
                                  variant="outline-primary"
                                  @click="addPercentage">
                            <i class="fa fa-plus"></i>
                        </b-button>

                        <legend class="w-auto text-primary font-weight-bold">Payment Terms Breakdown</legend>
                        <table class="table table-hover full-width">
                            <thead>
                            <tr>
                                <th width="60%">Percent Code</th>
                                <th width="30%">Percent Value</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(percentage, index) in form.percentage_breakdown" :key="index">
                                <td>
                                    <div>
                                        <b-form-input v-model="percentage.percent_code"
                                                      type="text"
                                                      class="input-container">
                                        </b-form-input>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <b-form-input v-model="percentage.percent_value"
                                                      type="number"
                                                      :min="0"
                                                      :max="100"
                                                      class="input-container text-right"
                                                      v-on:change="computeTotal">
                                        </b-form-input>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger"
                                            @click="deletePercentage(index, percentage)"><i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <td class="text-right">Total</td>
                            <td class="text-right">{{ form.percentage_total }}</td>
                            </tfoot>
                        </table>
                    </fieldset>
                </div>
                <div class="col-md-5"></div>
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
            'data'
        ],
        data() {
            return {
                form: new Form({
                    id: 0,
                    payment_term_name: '',
                    percentage_breakdown: [{
                        percent_code: '',
                        percent_value: '',
                    }],
                    percentage_total: 0,
                }),
            }
        },
        created() {
        },
        mounted() {
            const component = this;

            this.$root.$on('edit', (id) => {
                component.fetchData(id);
            });
        },
        methods: {
            fetchData(id) {
                const component = this;
                component.id = id;

                axios.get(`/api/requisition/payment-term/${id}`)
                    .then(function (response) {
                        component.form.fill(response.data);
                        component.$root.$emit('bv::show::modal', 'form_modal');
                        component.computeTotal();
                    })
                    .catch(function (error) {
                    })
                    .finally(function () {
                    })
            },
            addPercentage() {
                this.form.percentage_breakdown.push({
                    percent_code: '',
                    percent_value: '',
                });
            },
            deletePercentage(index, percentage) {
                var idx = this.form.percentage_breakdown.indexOf(percentage);

                if (idx > -1) {
                    this.form.percentage_breakdown.splice(idx, 1);
                }

                this.computeTotal();
            },
            computeTotal() {
                const component = this;
                let percent_total = 0.0;

                $.each(this.form.percentage_breakdown, function (index, value) {
                    const percent_value = parseFloat(value.percent_value);
                    if (!isNaN(percent_value)) {
                        percent_total += percent_value;
                    }
                });

                component.form.percentage_total = percent_total;
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
                        await this.form.patch(`/api/requisition/payment-term/${this.id}`) : await this.form.post('/api/requisition/payment-term');

                    if (response.data.success) {
                        this.$swal.fire(
                            is_update ? 'Update Record' : 'Added New Record',
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.formClose();
                            this.formReset();

                            const table = $('#tbl-payment-term');
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
                this.id ? form_modal.text('Edit Payment Term') : form_modal.text('Add New Payment Term');
            }
        }
    }
</script>

<style scoped>

</style>