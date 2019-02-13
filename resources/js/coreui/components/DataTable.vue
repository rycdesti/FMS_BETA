<template>
    <div>
        <table :id="id" class="table">
            <thead>
            <tr>
                <th scope="col" v-for="header in headers">
                    {{ header }}
                </th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div v-if="isLoading" class="spinner-container full-width">
            <Circle10/>
            <div class="spinner-body">
                Loading
            </div>
        </div>
    </div>
</template>

<script>
    import 'datatables.net-bs4'
    import 'datatables.net-bs4/css/dataTables.bootstrap4.css'
    import 'datatables.net-buttons-bs4'
    import 'datatables.net-responsive-bs4'
    import Circle10 from "vue-loading-spinner/src/components/Circle10";

    // import store from '~/store'

    export default {
        name: 'Datatable',
        components: {
            Circle10,
        },
        props: {
            id: {
                type: String,
                required: true
            },
            headers: {
                type: Array,
                required: true,
            },
            url: {
                type: String,
                required: true
            },
            columns: {
                type: Array,
                default: function () {
                    return []
                }
            },
            buttons: {
                type: Array,
                default: function () {
                    return [
                       'excel', 'pdf'
                    ]
                }
            },
        },
        data() {
            return {
                isLoading: true,
            }
        },
        mounted() {
            const component = this;

            const table = $('#' + this.id);
            table.DataTable({
                responsive: true,
                dom: "<'row'<'col-md-6'l><'col-md-6'Bf>>" +
                    "<'row'<'col-md-6'><'col-md-6'>>" +
                    "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>",
                serverSide: true,
                processing: true,
                stateSave: true,
                autoWidth: false,
                ajax: {
                    url: this.url,
                    beforeSend: function (request) {
                        request.setRequestHeader('Request-Type', 'Datatable')
                        // request.setRequestHeader('Authorization', 'Bearer ' + store.getters['auth/token'])
                    },
                },
                columns: this.columns,
                buttons: this.buttons,
                initComplete: function () {
                    component.isLoading = false;
                }
            });
            table.DataTable().draw(false);

        }
    }
</script>
<style>
    div.dt-buttons {
        float: right;
        margin-left: 10px;
    }

    .table {
        width: 100%;
    }
</style>
