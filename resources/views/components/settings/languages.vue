<template>
  <section class="language-options">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3 class="h1">{{ $t("lang.titleCard") }}</h3>
      </div>
      <!-- header tab -->
      <div class="card-body p-5">
        <p>{{ $t("lang.descriptionText") }}</p>
        <div>
          <label for="arabic" :class="{ active: swatchLanguage == 'ar' }">
            <input
              type="radio"
              name="lang"
              id="arabic"
              v-model="swatchLanguage"
              value="ar"
              :checked="swatchLanguage == 'ar'"
            />
            <span>العربية</span>
          </label>
        </div>
        <!-- arabic radio -->
        <!-- --  -->
        <div>
          <label for="english" :class="{ active: swatchLanguage == 'en' }">
            <input
              type="radio"
              name="lang"
              id="english"
              v-model="swatchLanguage"
              value="en"
              :checked="swatchLanguage == 'en'"
            />
            <span>English</span>
          </label>
        </div>
        <!-- english radio -->
        <!-- -- -->
        <div>
          <button
            :disabled="$root.$i18n.locale == swatchLanguage"
            class="btn btn-primary"
            @click="swatchLanguages()"
          >{{ $t("lang.saveButton") }}</button>
        </div>
        <!-- button for save setting  -->
      </div>
      <!-- content tab -->
    </div>
    <!-- styling fro languages options: useing card in bt4 -->
  </section>
</template>

<script>
export default {
  created: function () {
    if (localStorage.getItem("settings"))
      this.swatchLanguage = JSON.parse(
        localStorage.getItem("settings")
      ).language.setting_value;
    else this.swatchLanguage = this.$root.$i18n.locale;
  },
  props: ["userId"],
  name: "languages",
  data() {
    return {
      swatchLanguage: null,
    };
  },
  methods: {
    swatchLanguages() {
      let log = console.log;
      let data = {
        key: "language",
        user_id: this.userId,
        value: this.swatchLanguage,
      };
      axios
        .put("settings/update", data)
        .then((response) => {
          if (localStorage.getItem("settings")) {
            let lang = JSON.parse(localStorage.getItem("settings"));
            Vue.set(lang.language, "setting_value", this.swatchLanguage);
            localStorage.setItem("settings", JSON.stringify(lang));
            console.log();
          }
          this.$router.push({
            path: `/${this.swatchLanguage}/setting`,
          });
        })
        .catch((error) => error.response);
    },
  },
};
</script>

<i18n>
{
  "en":{
    "lang": {
      "titleCard": "Swatch Languages",
      "saveButton": "Save",
      "descriptionText": "Choose the application display language."
    }
  },
  "ar":{
    "lang": {
      "titleCard": "التبديل بين اللغات",
      "saveButton": "حفظ",
      "descriptionText": "إختيار لغة عرض التطبيق"
    }
  }
}
</i18n>
