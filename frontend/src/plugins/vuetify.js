import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
  theme: {
    options: {
      customProperties: true
    },
    themes: {
      light: {
        shifl: '#0171A1',
        th: '#819FB2',
        labelcolor: '#819FB2'
      }
    }
  }
});
