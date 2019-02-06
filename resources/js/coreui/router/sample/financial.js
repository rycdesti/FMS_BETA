// Views - Components
import AccountCategory from '@/views/sample/financial/account_category'
import ChartOfAccount from '@/views/sample/financial/chart_of_account'

export default {
    path: 'financial',
    redirect: '/dashboard',
    name: 'Financial Management',
    component: {
        render(c) {
            return c('router-view')
        }
    },
    children: [
        {
            path: 'account-category',
            name: 'Account Category',
            component: AccountCategory,
        },
        {
            path: 'chart-of-account',
            name: 'Chart of Account',
            component: ChartOfAccount,
        },
    ],
}
