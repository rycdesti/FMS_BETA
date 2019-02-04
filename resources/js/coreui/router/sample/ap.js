// Views - Components
import Bank from '@/views/sample/ap/bank/index'
import BankAccount from '@/views/sample/ap/bank_account/index'
import Check from '@/views/sample/ap/check/index'

export default {
    path: 'ap',
    redirect: '/ap/monthly-payment',
    name: 'Accounts Payable',
    component: {
        render(c) {
            return c('router-view')
        }
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
            name: 'Banks',
            component: Bank,
        },
        {
            path: 'bank-account/:bank_id',
            name: 'Bank Accounts',
            component: BankAccount,
            props: true,
        },
        {
            path: 'check/:bank_account_id',
            name: 'Checks',
            component: Check,
            props: true
        },

    ],
}
