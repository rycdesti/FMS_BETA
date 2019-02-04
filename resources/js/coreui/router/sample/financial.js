// Views - Components
import AccountCategory from '@/views/sample/financial/account_category/index'
import ChartOfAccount from '@/views/sample/financial/chart_of_account/index'

export default {
  path     : 'financial',
  redirect : '/financial/account-category',
  name     : 'Financial Management',
  component: { render (c) { return c('router-view') } },
  children : [
    {
      path     : 'account-category',
      name     : 'Account Category',
      component: AccountCategory,
    },
    {
      path     : 'chart-of-account',
      name     : 'Chart of Account',
      component: ChartOfAccount,
    },
  ],
}
