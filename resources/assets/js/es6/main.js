'use strict'
import Vue from 'vue'
import VueYouTubeEmbed from 'vue-youtube-embed'
import VuePreload from 'vue-preload'
//import { alert, aside, typeahead } from 'vue-strap'

Vue.use(VueYouTubeEmbed)

Vue.use(VuePreload)
// with options
Vue.use(VuePreload, {
  // show the native progress bar
  // put <preloading></preloading> in your root component
  showProgress: true,
  // excutes when click
  onStart() {},
  // excutes when use .end() and after .setState()
  onEnd() {},
  // excutes when prefetching the state
  onPreLoading() {},
})