export default {
    items: [
        {
            name: 'Dashboard',
            url: '/dashboard',
            icon: 'icon-speedometer',
            badge: {
                variant: 'primary',
                text: 'NEW',
            },
        },
        {
            title: true,
            name: 'Modules',
            class: '',
            wrapper: {
                element: '',
                attributes: {},
            },
        },
        // {
        //   name: 'Colors',
        //   url : '/theme/colors',
        //   icon: 'icon-drop',
        // },
        // {
        //   name: 'Typography',
        //   url : '/theme/typography',
        //   icon: 'icon-pencil',
        // },
        // {
        //   title  : true,
        //   name   : 'Components',
        //   class  : '',
        //   wrapper: {
        //     element   : '',
        //     attributes: {},
        //   },
        // },
        {
            name: 'Accounts Payable',
            url: '/ap',
            color: 'font-weight-bold',
            children: [
                {
                    name: 'Transact',
                    icon: 'fa fa-exchange',
                    color: 'text-green font-weight-bold',
                    children: [
                        {
                            name: 'Check Payment Request',
                            url: '/ap/check-payment-request',
                        },
                        {
                            name: 'Monthly Payments',
                            url: '/ap/monthly-payment',
                        },
                        {
                            name: 'Recurring Payments',
                            url: '/ap/recurring-payment',
                        },
                    ],
                },
                {
                    name: 'Lookup',
                    icon: 'fa fa-search',
                    color: 'text-yellow font-weight-bold',
                    children: [
                        {
                            name: 'Banks',
                            url: '/ap/bank',
                        },
                        {
                            name: 'Checks',
                            url: '/ap/check',
                        },
                    ]
                }
            ],
        },
        {
            name: 'Financial Management',
            url: '/financial',
            color: 'font-weight-bold',
            children: [
                {
                    name: 'Lookup',
                    icon: 'fa fa-search',
                    color: 'text-yellow font-weight-bold',
                    children: [
                        {
                            name: 'Account Category',
                            url: '/financial/account-category',
                        },
                        {
                            name: 'Chart of Accounts',
                            url: '/financial/chart-of-account',
                        },
                    ]
                }
            ],
        },
        {
            name: 'Requisition Management',
            url: '/requisition',
            color: 'font-weight-bold',
            children: [
                {
                    name: 'Lookup',
                    icon: 'fa fa-search',
                    color: 'text-yellow font-weight-bold',
                    children: [
                        {
                            name: 'Currency',
                            url: '/requisition/currency',
                        },
                        {
                            name: 'Payment Term',
                            url: '/requisition/payment-term',
                        },
                        {
                            name: 'Supplier Classification',
                            url: '/requisition/supplier-classification',
                        },
                        {
                            name: 'Supplier',
                            url: '/requisition/supplier',
                        },
                    ],
                }
            ],

        },
        //   {
        //     name    : 'BaseModel',
        //     url     : '/base',
        //     icon    : 'icon-puzzle',
        //     children: [
        //       {
        //         name: 'Breadcrumbs',
        //         url : '/base/breadcrumbs',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Cards',
        //         url : '/base/cards',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Carousels',
        //         url : '/base/carousels',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Collapses',
        //         url : '/base/collapses',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Forms',
        //         url : '/base/forms',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Jumbotrons',
        //         url : '/base/jumbotrons',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'List Groups',
        //         url : '/base/list-groups',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Navs',
        //         url : '/base/navs',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Paginations',
        //         url : '/base/paginations',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Popovers',
        //         url : '/base/popovers',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Progress Bars',
        //         url : '/base/progress-bars',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Switches',
        //         url : '/base/switches',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Tables',
        //         url : '/base/tables',
        //         icon: 'icon-puzzle',
        //       },
        //       {
        //         name: 'Tooltips',
        //         url : '/base/tooltips',
        //         icon: 'icon-puzzle',
        //       },
        //     ],
        //   },
        //   {
        //     name    : 'Buttons',
        //     url     : '/buttons',
        //     icon    : 'icon-cursor',
        //     children: [
        //       {
        //         name: 'Standard Buttons',
        //         url : '/buttons/standard-buttons',
        //         icon: 'icon-cursor',
        //       },
        //       {
        //         name: 'Button Groups',
        //         url : '/buttons/button-groups',
        //         icon: 'icon-cursor',
        //       },
        //       {
        //         name: 'Dropdowns',
        //         url : '/buttons/dropdowns',
        //         icon: 'icon-cursor',
        //       },
        //       {
        //         name: 'Social Buttons',
        //         url : '/buttons/social-buttons',
        //         icon: 'icon-cursor',
        //       },
        //     ],
        //   },
        //   {
        //     name    : 'Icons',
        //     url     : '/icons',
        //     icon    : 'icon-star',
        //     children: [
        //       {
        //         name : 'Flags',
        //         url  : '/icons/flags',
        //         icon : 'icon-star',
        //         badge: {
        //           variant: 'success',
        //           text   : 'NEW',
        //         },
        //       },
        //       {
        //         name : 'Font Awesome',
        //         url  : '/icons/font-awesome',
        //         icon : 'icon-star',
        //         badge: {
        //           variant: 'secondary',
        //           text   : '4.7',
        //         },
        //       },
        //       {
        //         name: 'Simple Line Icons',
        //         url : '/icons/simple-line-icons',
        //         icon: 'icon-star',
        //       },
        //     ],
        //   },
        //   {
        //     name: 'Charts',
        //     url : '/charts',
        //     icon: 'icon-pie-chart',
        //   },
        //   {
        //     name    : 'Notifications',
        //     url     : '/notifications',
        //     icon    : 'icon-bell',
        //     children: [
        //       {
        //         name: 'Alerts',
        //         url : '/notifications/alerts',
        //         icon: 'icon-bell',
        //       },
        //       {
        //         name: 'Badges',
        //         url : '/notifications/badges',
        //         icon: 'icon-bell',
        //       },
        //       {
        //         name: 'Modals',
        //         url : '/notifications/modals',
        //         icon: 'icon-bell',
        //       },
        //     ],
        //   },
        //   {
        //     name : 'Widgets',
        //     url  : '/widgets',
        //     icon : 'icon-calculator',
        //     badge: {
        //       variant: 'primary',
        //       text   : 'NEW',
        //     },
        //   },
        //   {
        //     name : 'Loading',
        //     url  : '/loading',
        //     icon : 'icon-reload',
        //     badge: {
        //       variant: 'danger',
        //       text   : 'HOT',
        //     },
        //   },
    ],
}
