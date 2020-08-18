<template>
  <section class="modal-container chooseCategoryModal">
    <div
      class="modal fade"
      id="chooseCategory"
      tabindex="-1"
      role="dialog"
      aria-labelledby="chooseCategoryLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="chooseCategoryLabel">
              <i class="fas fa-clipboard-list"></i>
              {{ $t("chooseCate.titelInHeader") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('chooseCate.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="categories" ref="categories">
              <div
                @click="chooseThis($event, category.id)"
                class="theCate d-flex align-items-center"
                v-for="(category, index) in categories"
                :key="index"
              >
                <i :class="category!=undefined?category.category_symbol:''"></i>
                <span>{{ category!=undefined?category.category_name:'' }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              :disabled="isChoose"
              data-dismiss="modal"
              @click="sendId"
            >{{ $t("chooseCate.btnEdit") }}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "choose_category",
  props: ["categories"],
  data() {
    return {
      cateId: 0,
    };
  },
  mounted() {
    $("#chooseCategory").on("show.bs.modal	", function () {
      this.style.zIndex = 999999;
    });
    $("#chooseCategory").on("hide.bs.modal", function () {
      this.style.zIndex = -1;
    });
  },
  methods: {
    chooseThis: function (e, id) {
      let cates = this.$refs.categories.children,
        cate;
      for (cate of cates) cate.classList.remove("active");
      e.target.closest(".theCate").classList.add("active");
      this.cateId = id;
    },
    sendId: function () {
      this.$emit("setCategroy", this.cateId);
    },
  },
  computed: {
    isChoose: function () {
      if (this.cateId > 0) return false;
      return true;
    },
  },
};
</script>

<i18n>
{
  "en":{
    "chooseCate":{
      "btnClose" : "close",
      "titelInHeader": "Choose Category",
      "btnEdit": "Choose"
    }
  },
  "ar":{
    "chooseCate":{
      "btnClose" : "إغلاق",
      "titelInHeader": "اختيار القسم",
      "btnEdit": "اختيار"
    }
  }
}
</i18n>
