<template>
  <section class="modal-container cateModal">
    <div
      class="modal fade"
      id="editCategories"
      tabindex="-1"
      role="dialog"
      aria-labelledby="EditCategoriesLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="EditCategoriesLabel">
              <i class="fas fa-clipboard-list"></i>
              {{$t("editCatekModal.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('editCatekModal.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="cateNameInput">
              <label
                class="col-form-label cateName"
                for="cateName"
              >{{$t("editCatekModal.nameCate")}}</label>
              <input
                type="text"
                v-model="cateName"
                class="form-control inputType"
                form="cateName"
                :placeholder="$t('editCatekModal.nameCateHolder')"
              />
            </div>
            <div class="cateDsecInput">
              <label
                class="col-form-label cateDesc"
                for="cateDesc"
              >{{$t("editCatekModal.decsCate")}}</label>
              <textarea
                id="cateDesc"
                v-model="cateDesc"
                class="form-control inputType"
                cols="30"
                rows="5"
                :placeholder="$t('editCatekModal.decsCateHolder')"
              ></textarea>
            </div>
            <div class="cateColorIcon">
              <div class="cateColors">
                <label
                  class="col-form-label cateDesc"
                  for="cateDesc"
                >{{$t("editCatekModal.colorCate")}}</label>
                <VSwatches swatches="text-advanced" v-model="color"></VSwatches>
              </div>
              <div class="cateIcons">
                <label
                  class="col-form-label cateDesc"
                  for="cateDesc"
                >{{$t("editCatekModal.iconCate")}}</label>
                <div class="dropdown">
                  <button class="dropdownBtn" data-toggle="dropdown">
                    <i v-if="chooseSymbol" :class="chooseSymbol"></i>
                    <i v-else class="far fa-times-circle"></i>
                  </button>
                  <div class="dropdown-menu boxIcon">
                    <div class="iconRow">
                      <div
                        class="iconEl dropdown-item"
                        v-for="(icon,index) in icons"
                        :key="index"
                        @click="chooseSymbol=icon"
                      >
                        <i :class="icon"></i>
                        <span class="sr-only">{{index}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              :class="{disabled:cateNameEmpty}"
              @click="update"
            >{{$t("editCatekModal.btnCreate")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import VSwatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.css";
export default {
  name: "newCategories",
  components: {
    VSwatches
  },
  props: ["categories"],
  data() {
    return {
      color: "#27496d",
      symbol: "",
      cateDesc: "",
      cateName: "",
      icons: {
        none: "far fa-times-circle",
        home: "fas fa-home",
        briefcase: "fas fa-briefcase",
        shopping: "fas fa-shopping-basket",
        comments: "fas fa-comments",
        users: "fas fa-users",
        running: "fas fa-running",
        reader: "fas fa-book-reader",
        rolling: "fas fa-suitcase-rolling",
        swimming: "fas fa-swimming-pool",
        utensils: "fas fa-utensils",
        gamepad: "fas fa-gamepad",
        file: "fas fa-file-word",
        gift: "fas fa-gift",
        laptop: "fas fa-laptop"
      }
    };
  },
  methods: {
    update: function() {
      let ob = {
        id: this.categories.id,
        category_name: this.cateName,
        category_description: this.cateDesc,
        category_color: this.color,
        category_symbol: this.symbol
      };
      this.$emit("edit", ob);
      $("#editCategories").modal("hide");
    }
  },
  computed: {
    chooseSymbol: {
      get: function() {
        return this.symbol;
      },
      set: function(symbol) {
        if (!symbol.indexOf("far fa-times-circle")) this.symbol = "";
        else this.symbol = symbol;
      }
    },
    cateNameEmpty: function() {
      if (
        this.cateName == "" &&
        this.cateName == undefined &&
        this.cateName == " "
      )
        return true;
      else return false;
    },
    setCategories: function() {
      this.cateName = this.categories.category_name;
      this.cateDesc = this.categories.category_description;
      this.color = this.categories.category_color;
      this.symbol = this.categories.category_symbol;
    }
  }
};
</script>


<i18n>
{
  "en":{
    "editCatekModal":{
      "titelInHeader":"Edit Categories",
      "btnClose":"Close",
      "nameCate":"Categories Name",
      "nameCateHolder":"Categories Name",
      "decsCate":"Categories Description",
      "decsCateHolder":"Categories Description",
      "btnCreate":"Edit",
      "colorCate":"choose the Color",
      "iconCate":"choose the Symbol"
    }
  },
  "ar":{
      "editCatekModal":{
        "titelInHeader":"تعديل القسم",
        "btnClose":"إغلاق",
        "nameCate":"اسم القسم",
        "nameCateHolder":"اسم القسم يجب أن يكون غير مكرر",
        "decsCate":"وصف القسم",
        "decsCateHolder":"وصف الهدف من القسم - اختياري",
        "btnCreate":"تعديل",
        "colorCate":"أختيار لون للقسم",
        "iconCate":"رمز يعبر عن القسم"
    }
  }
}
</i18n>
