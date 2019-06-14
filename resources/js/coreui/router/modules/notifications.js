// Views - Notifications
import Alerts from '@/views/modules/notifications/Alerts'
import Badges from '@/views/modules/notifications/Badges'
import Modals from '@/views/modules/notifications/Modals'

export default {
  path     : 'notifications',
  redirect : '/notifications/alerts',
  name     : 'Notifications',
  component: { render (c) { return c('router-view') } },
  children : [
    {
      path     : 'alerts',
      name     : 'Alerts',
      component: Alerts,
    },
    {
      path     : 'badges',
      name     : 'Badges',
      component: Badges,
    },
    {
      path     : 'modals',
      name     : 'Modals',
      component: Modals,
    },
  ],
}
