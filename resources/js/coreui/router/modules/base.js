// Views - Components
import Cards from '@/views/modules/base/Cards'
import Forms from '@/views/modules/base/Forms'
import Switches from '@/views/modules/base/Switches'
import Tables from '@/views/modules/base/Tables'
import Breadcrumbs from '@/views/modules/base/Breadcrumbs'
import Carousels from '@/views/modules/base/Carousels'
import Collapses from '@/views/modules/base/Collapses'
import Jumbotrons from '@/views/modules/base/Jumbotrons'
import ListGroups from '@/views/modules/base/ListGroups'
import Navs from '@/views/modules/base/Navs'
import Navbars from '@/views/modules/base/Navbars'
import Paginations from '@/views/modules/base/Paginations'
import Popovers from '@/views/modules/base/Popovers'
import ProgressBars from '@/views/modules/base/ProgressBars'
import Tooltips from '@/views/modules/base/Tooltips'

export default {
  path     : 'base',
  redirect : '/base/cards',
  name     : 'Base',
  component: { render (c) { return c('router-view') } },
  children : [
    {
      path     : 'cards',
      name     : 'Cards',
      component: Cards,
    },
    {
      path     : 'forms',
      name     : 'Forms',
      component: Forms,
    },
    {
      path     : 'switches',
      name     : 'Switches',
      component: Switches,
    },
    {
      path     : 'tables',
      name     : 'Tables',
      component: Tables,
    },
    {
      path     : 'breadcrumbs',
      name     : 'Breadcrumbs',
      component: Breadcrumbs,
    },
    {
      path     : 'carousels',
      name     : 'Carousels',
      component: Carousels,
    },
    {
      path     : 'collapses',
      name     : 'Collapses',
      component: Collapses,
    },
    {
      path     : 'jumbotrons',
      name     : 'Jumbotrons',
      component: Jumbotrons,
    },
    {
      path     : 'list-groups',
      name     : 'List Groups',
      component: ListGroups,
    },
    {
      path     : 'navs',
      name     : 'Navs',
      component: Navs,
    },
    {
      path     : 'navbars',
      name     : 'Navbars',
      component: Navbars,
    },
    {
      path     : 'paginations',
      name     : 'Paginations',
      component: Paginations,
    },
    {
      path     : 'popovers',
      name     : 'Popovers',
      component: Popovers,
    },
    {
      path     : 'progress-bars',
      name     : 'Progress Bars',
      component: ProgressBars,
    },
    {
      path     : 'tooltips',
      name     : 'Tooltips',
      component: Tooltips,
    },
  ],
}
