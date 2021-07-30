require('./bootstrap');

import VModal from 'vue-js-modal'
Vue.use(VModal)

Vue.component('board', require('./components/Board.vue').default);

const app = new Vue({
    el: '#app',
});
