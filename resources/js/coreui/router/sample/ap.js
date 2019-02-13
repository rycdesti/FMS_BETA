// Views - Components
import Bank from '@/views/sample/ap/bank'
import BankAccount from '@/views/sample/ap/bank_account'
import Check from '@/views/sample/ap/check'
import RecurringPayment from '@/views/sample/ap/recurring_payment'

export default {
    path: 'ap',
    redirect: '/dashboard',
    name: 'Accounts Payable',
    component: {
        render(c) {
            return c('router-view')
        },
    },
    children: [
        // {
        //     path     : 'monthly-payment',
        //     name     : 'Manage Monthly Payments',
        //     component: AccountCategory,
        // },
        {
            path: 'recurring-payment',
            name: 'Recurring Payment',
            component: RecurringPayment,
        },
        {
            path: 'bank',
            name: 'Bank',
            component: Bank,
        },
        {
            path: 'bank-account/:bank_id',
            name: 'Bank Account',
            component: BankAccount,
            props: true,
        },
        {
            path: 'check/:bank_account_id',
            name: 'Check',
            component: Check,
            props: true,
        },

    ],
}
