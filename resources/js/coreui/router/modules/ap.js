// Views - Components
import WithholdingTax from '@/views/modules/ap/withholding_tax'
import Branch from '@/views/modules/ap/branch'
import Bank from '@/views/modules/ap/bank'
import BankAccount from '@/views/modules/ap/bank_account'
import Check from '@/views/modules/ap/check'
import RecurringPayment from '@/views/modules/ap/recurring_payment'
import RecurringPaymentDistribution from '@/views/modules/ap/recurring_payment_distribution'
import MonthlyPayment from '@/views/modules/ap/monthly_payment/default'
import MonthlyPaymentReview from '@/views/modules/ap/monthly_payment/review'
import MonthlyPaymentRecommend from '@/views/modules/ap/monthly_payment/recommend'
import CheckPaymentRequest from '@/views/modules/ap/check_payment_request'
import BankDeposit from '@/views/modules/ap/bank_deposit'

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
        {
            path: 'withholding-tax',
            name: 'WithholdingTax',
            component: WithholdingTax,
        },
        {
            path: 'branch',
            name: 'Branch',
            component: Branch,
        },
        {
            path: 'bank',
            name: 'Bank',
            component: Bank,
        },
        {
            path: 'bank-account/:bank_id',
            name: 'BankAccount',
            component: BankAccount,
            props: true,
        },
        {
            path: 'check',
            name: 'Check',
            component: Check,
            props: true,
        },
        {
            path: 'recurring-payment',
            name: 'RecurringPayment',
            component: RecurringPayment,
        },
        {
            path: 'recurring-payment-distribution/:recurring_payment_id',
            name: 'RecurringPaymentDistribution',
            component: RecurringPaymentDistribution,
            props: true,
        },
        {
            path: 'monthly-payment',
            name: 'MonthlyPayment',
            component: MonthlyPayment,
        },
        {
            path: 'monthly-payment-review',
            name: 'MonthlyPaymentReview',
            component: MonthlyPaymentReview,
        },
        {
            path: 'monthly-payment-recommend',
            name: 'MonthlyPaymentRecommend',
            component: MonthlyPaymentRecommend,
        },
        {
            path: 'check-payment-request',
            name: 'CheckPaymentRequest',
            component: CheckPaymentRequest,
        },
        {
          path: 'bank-deposit',
          name: 'BankDeposit',
          component: BankDeposit,
        },
    ],
}
