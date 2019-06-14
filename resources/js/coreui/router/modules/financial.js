// Views - Components
import AccountCategory from '@/views/modules/financial/account_category'
import ChartOfAccount from '@/views/modules/financial/chart_of_account'

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
            name: 'AccountCategory',
            component: AccountCategory,
        },
        {
            path: 'chart-of-account',
            name: 'ChartOfAccount',
            component: ChartOfAccount,
        },
    ],
}
