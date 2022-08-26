import Vue from 'vue'

import EncodingComposition from './Encoding-Composition'
import EncodedComposition from './Encoded-Composition'
import Dashboard from './Dashboard'
import Users from './Users'
import ChangePassword from './ChangePassword'
import Allocation from './Allocation'

Vue.component('encoding-composition', EncodingComposition);
Vue.component('encoded-composition', EncodedComposition);
Vue.component('dashboard', Dashboard);
Vue.component('users', Users);
Vue.component('change-password', ChangePassword);
Vue.component('allocation', Allocation);