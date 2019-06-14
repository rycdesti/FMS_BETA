// Views - Components
import Currency from '@/views/modules/requisition/currency'
import PaymentTerm from '@/views/modules/requisition/payment_term'
import SupplierClassification from '@/views/modules/requisition/supplier_classification'
import Supplier from '@/views/modules/requisition/supplier'
import SupplierContact from '@/views/modules/requisition/supplier_contact'

export default {
    path: 'requisition',
    redirect: '/dashboard',
    name: 'Requisition',
    component: {
        render(c) {
            return c('router-view')
        },
    },
    children: [
        {
            path: 'currency',
            name: 'Currency',
            component: Currency,
        },
        {
            path: 'payment-term',
            name: 'PaymentTerm',
            component: PaymentTerm,
        },
        {
            path: 'supplier-classification',
            name: 'SupplierClassification',
            component: SupplierClassification,
        },
        {
            path: 'supplier',
            name: 'Supplier',
            component: Supplier,
        },
        {
            path: 'supplier-contact/:supplier_id',
            name: 'SupplierContact',
            component: SupplierContact,
            props: true,
        },
    ],
}
