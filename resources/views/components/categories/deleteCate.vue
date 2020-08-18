<template>
  <section class="modal-container cateModal">
    <div
      class="modal fade"
      id="deleteCategories"
      tabindex="-1"
      role="dialog"
      aria-labelledby="deleteCategoriesLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteCategoriesLabel">
              <i class="fas fa-clipboard-list"></i>
              {{$t("deleteCatekModal.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('deleteCatekModal.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>{{$t("deleteCatekModal.decs")}}</div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="deleteCate"
            >{{$t("deleteCatekModal.btnDelete")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "deleteCategories",

  data() {
    return {};
  },
  methods: {
    deleteCate: function() {
      this.$emit("remove");
      $("#deleteCategories").modal("hide");
    }
  }
};
</script>


<i18n>
{
  "en":{
    "deleteCatekModal":{
      "titelInHeader":"Remove Categories",
      "btnClose":"Close",
      "decs":"Do you want to Remove the Category ?, all tasks will be permanently deleted with it",
      "btnDelete":"Remove"
    }
  },
  "ar":{
      "deleteCatekModal":{
        "titelInHeader":"حذف القسم",
        "btnClose":"إغلاق",
        "decs":"هل تريد إزالة الفئة؟ ، سيتم حذف جميع المهام نهائيًا معها",
        "btnDelete":"حذف"
    }
  }
}
</i18n>
