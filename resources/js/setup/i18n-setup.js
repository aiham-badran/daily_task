import Vue from "vue";
import VueI18n from "vue-i18n";
import axios from "axios";
import messages from "../lang/en.json";

Vue.use(VueI18n);

export const i18n = new VueI18n({
  locale: "en",
  fallbackLocale: "en",
  messages,
});

const loadedLanguages = ["en"];

function setI18nLanguage(lang) {
  i18n.locale = lang;

  axios.defaults.headers.common["Accept-language"] = lang;
  document.querySelector("html").setAttribute("lang", lang);
  if (lang == "ar") {
    document.querySelector("body").classList.add("ar-lang");
  } else {
    document.querySelector("body").classList.remove("ar-lang");
  }
  return lang;
}

export function loadLanguageAsync(lang) {
  if (i18n.locale === lang) {
    return Promise.resolve(setI18nLanguage(lang));
  }

  if (loadedLanguages.includes(lang)) {
    return Promise.resolve(setI18nLanguage(lang));
  }
  return import(`../lang/${lang}.json`).then((messages) => {
    i18n.setLocaleMessage(lang, messages.default);
    loadedLanguages.push(lang);
    return setI18nLanguage(lang);
  });
}
