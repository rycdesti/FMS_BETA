// Views - Components
import Bank from '@/views/sample/ap/bank'
import BankAccount from '@/views/sample/ap/bank_account'
import Check from '@/views/sample/ap/check'

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
        // {
        //     path     : 'recurring-payment',
        //     name     : 'Manage Recurring Payments',
        //     component: ChartOfAccount,
        // },
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
