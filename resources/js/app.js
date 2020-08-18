/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import router from "./routes/index";
import store from "./setup/store";
import { i18n } from "./setup/i18n-setup";

window.Vue = require("vue");

//-
require("./store/subscribe");
require("./setup/refreshToken");

//-
(async function loadSetting() {
  if (!localStorage.getItem("settings")) {
    await store.dispatch("setting/fetchSetting");
    localStorage.setItem(
      "settings",
      JSON.stringify(store.getters["setting/getSetting"])
    );
  }
  let lang = JSON.parse(localStorage.getItem("settings"));
  if (lang.language.setting_value) {
    // router.push({
    //   name: "langs",
    //   params: { langs: lang.language.setting_value },
    // });
  }
})();

//-
store.dispatch("auth/attempt", localStorage.getItem("token")).then(() => {
  /**
   * basic Vue app
   */
  const app = new Vue({
    el: "#app",
    router,
    store,
    i18n,
  });
});
