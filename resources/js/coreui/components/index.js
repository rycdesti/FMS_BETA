import Breadcrumb from './Breadcrumb.vue'
import Button from './Button'
import Callout from './Callout.vue'
import DataTable from './DataTable.vue'
import Footer from './Footer.vue'
import Header from './Header/Header.vue'
import Sidebar from './Sidebar/Sidebar.vue'
import Switch from './Switch.vue'
import { HasError, AlertError, AlertSuccess, AlertErrors } from 'vform'
import Vue from 'vue'

export {
  Breadcrumb,
  Callout,
  Footer,
  Header,
  Sidebar,
  Switch,
}

Vue.component(DataTable.name, DataTable)
Vue.component(Button.name, Button)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertSuccess.name, AlertSuccess)
Vue.component(AlertErrors.name, AlertErrors)