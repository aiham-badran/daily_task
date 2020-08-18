import Vue from "vue";
import VueRouter from "vue-router";
import Store from "../setup/store";
import { loadLanguageAsync, i18n } from "../setup/i18n-setup";
import taskBoard from "../../views/pages/taskBoard.vue";
import Calendar from "../../views/pages/calendar.vue";
import Settings from "../../views/pages/setting.vue";
import Categories from "../../views/pages/categories.vue";
import trashBin from "../../views/pages/trashBin.vue";
import login from "../../views/pages/login.vue";
import profile from "../../views/pages/profile.vue";

const date = new Date();
let month = date.getMonth() + 1;
const currentlyDate = month + "-" + date.getDate() + "-" + date.getFullYear();

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    redirect: `/${i18n.locale}/login`,
  },
  {
    path: "/:lang",
    component: {
      render(c) {
        return c("router-view");
      },
      name: "langs",
    },
    children: [
      {
        path: "/",
        redirect: `board/${currentlyDate}`,
        name: "todyBoard",
      },
      {
        path: "board/:taskBoard",
        component: taskBoard,
        name: "taskBoard",
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: "calendar",
        component: Calendar,
        name: "calendar",
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: "setting",
        component: Settings,
        name: "setting",
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: "categories",
        component: Categories,
        name: "categories",
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: "trashBin",
        component: trashBin,
        name: "trashBin",
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: "login",
        component: login,
        name: "login",
        beforeEnter: (to, from, next) => {
          if (Store.getters["auth/authentication"]) {
            next({
              name: "todyBoard",
              params: { lang: `${i18n.locale}` },
            });
          } else next();
        },
      },
      {
        path: "profile",
        component: profile,
        name: "profile",
        meta: {
          requiresAuth: true,
        },
      },
    ],
  },
];

const router = new VueRouter({
  mode: "history",
  routes,
});

router.beforeEach((to, from, next) => {
  let theLang = JSON.parse(localStorage.getItem("settings")).language
      .setting_value,
    lang;
  if (theLang) {
    if (to.params.lang != theLang) {
      next({ path: `/${theLang}/${to.name}` });
    }
    lang = theLang;
  } else lang = to.params.lang;
  loadLanguageAsync(lang).then(() => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      if (!Store.getters["auth/authentication"]) {
        next({
          name: "login",
          params: { lang: `${i18n.locale}` },
        });
      } else next();
    } else next();
  });
});

export default router;
