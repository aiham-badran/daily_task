<template>
  <layout>
    <breadcrumb :pages="pages"></breadcrumb>
    <!-- setting page -->
    <section class="settings">
      <div class="row">
        <div class="col-12 col-lg-4">
          <div class="list-group" id="setting-list" role="tablist">
            <router-link
              class="list-group-item list-group-item-action"
              role="link"
              :to="{name:'profile'}"
            >
              <i class="fas fa-id-card-alt"></i>
              {{$t("setting.options.profile")}}
            </router-link>
            <a
              class="list-group-item list-group-item-action"
              id="languages"
              data-toggle="list"
              href="#languages-options"
              role="tab"
              aria-controls="languages"
            >
              <i class="fas fa-language"></i>
              {{$t("setting.options.lang")}}
            </a>
            <!-- item in lsit gruop : languages -->
          </div>
          <!-- setting list has class list-gruop -->
        </div>
        <!-- class col-4 -->
        <!-- -- -->
        <div class="col-12 col-lg-8">
          <div class="tab-content" id="setting-content">
            <div class="tab-pane fade" id="languages-options">
              <languages-setting :userId="this.profile.data.id"></languages-setting>
            </div>
            <!-- tab for languages options -->
          </div>
          <!-- div has setting options -->
        </div>
        <!-- class col-8 -->
      </div>
      <!-- class row -->
    </section>
    <!-- class viewAria -->
  </layout>
</template>

<script>
import { mapGetters } from "vuex";
import layout from "./layout.vue";
import languagesSetting from "../components/settings/languages.vue";
import breadcrumb from "../components/breadcrumb.vue";
export default {
  name: "setting",
  components: {
    languagesSetting,
    layout,
    breadcrumb,
  },
  data() {
    return {
      langs: ["ar", "en"],
      pages: {
        setting: {
          name: "global.setting",
          icon: "fas fa-sliders-h",
          active: true,
        },
      },
    };
  },
  computed: {
    ...mapGetters({
      profile: "auth/profile",
    }),
  },
};
</script>

<style>
</style>

<i18n>
{
  "en":{
    "setting": {
      "options": {
        "lang": "Language Options",
        "profile":"Profile"
      }
    }
  },
  "ar":{
    "setting": {
      "options": {
        "lang": "اعدادات اللغة",
        "profile":"الملف الشخصي"
      }
    }
  }
}
</i18n>

