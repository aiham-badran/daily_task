import Vue from "vue";
import Vuex from "vuex";
import auth from "../store/auth.js";
import alert from "../store/alert.js";
import category from "../store/categories.js";
import setting from "../store/setting.js";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    openSideBar: false,
    openBoard: undefined,
    accessToken: null,
    url: process.env.MIX_APP_URL,
  },
  getters: {
    stateSideBar: (state) => {
      return state.openSideBar;
    },
    openBoardByDate: (state) => state.openBoard,

    getAccessToken: (state) => state.accessToken,

    getUrlApi: (state) => state.url,
  },
  mutations: {
    toggleSideBar(state) {
      state.openSideBar = !state.openSideBar;
    },
    setDateForBoard: (state, date) => {
      state.openBoard = date;
    },
    setAccessToken: (state, token) => (state.accessToken = token),
  },
  modules: {
    auth,
    alert,
    category,
    setting,
  },
});
