<template>
  <section class="modal-container eidtModal">
    <div
      class="modal fade"
      id="editTask"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editTaskLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTaskLabel">
              <i class="fas fa-file-medical"></i>
              {{$t("editTaskModal.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('editTaskModal.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="category">
              <div>{{$t('editTaskModal.Category')}}</div>
              <div v-if="categoryExist" @click="chooseCate">{{$t('editTaskModal.CategoryNone')}}</div>
              <div v-else @click="chooseCate">
                <i :class="getCategory.category_symbol"></i>
                <span>{{getCategory.category_name}}</span>
              </div>
            </div>
            <div>
              <label class="col-form-label" for="descTask">{{$t("editTaskModal.decsTask")}}</label>
              <textarea
                id="descTask"
                v-model="desc"
                class="form-control inputType"
                cols="30"
                rows="5"
                :placeholder="$t('editTaskModal.decsTaskHolder')"
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="edit"
              :disabled="btnDisabled"
              data-dismiss="modal"
            >{{ $t("editTaskModal.btnEdit")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  name: "editTask",
  props: ["update", "id"],
  data() {
    return {
      desc: "",
      cateIdEdit: 0,
    };
  },
  mounted() {
    if (this.categories[0] != undefined) {
      this.fetchCategory();
    }
    this.cateIdEdit = this.id;
    $("#editTask").on("hide.bs.modal", () => {
      this.cateIdEdit = 0;
    });
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
      fetchCategory: "category/fetchCategory",
    }),
    edit: function () {
      let ob = {
        task_text: this.desc,
        id: this.update.id,
        categories_id: this.getCategory.id,
      };
      this.$emit("edit", ob);
    },
    chooseCate: function () {
      $("#chooseCategory").modal("show");
    },
  },
  computed: {
    ...mapGetters({
      categories: "category/getCategories",
      getCategoryById: "category/getCategoryById",
    }),
    btnDisabled: function () {
      if (this.desc.length > 1) return false;
      else return true;
    },
    EidtData: function () {
      if (this.update != undefined && this.update != "") {
        this.desc = this.update.task_text;
        this.cateIdEdit = this.update.category.id;
      }
    },
    categoryExist: function () {
      if (
        this.categories[this.cateIdEdit] != null &&
        this.categories[this.cateIdEdit] != undefined
      ) {
        if (
          this.cateIdEdit != 0 &&
          Object.keys(this.categories[this.cateIdEdit]).length > 0
        )
          return false;
      }
      return true;
    },
    getCategory: function () {
      return this.getCategoryById(this.cateIdEdit);
    },
  },
  watch: {
    id: function () {
      this.cateIdEdit = this.id;
    },
  },
};
</script>


<i18n>
{
  "en":{
    "editTaskModal":{
      "decsTask": "Description Task",
      "btnClose" : "close",
      "decsTaskHolder": "Add task details (no character limit, required)",
      "titelInHeader": "Edit Task",
      "btnEdit": "Edit",
      "Category":"Category",
      "CategoryNone":"None"
    }
  },
  "ar":{
    "editTaskModal":{
      "decsTask": "وصف المهمة",
      "btnClose" : "إغلاق",
      "decsTaskHolder": "(إضافة المهمة ( مطلوب, لايوجد حد معين",
      "titelInHeader": "تعديل المهمة",
      "btnEdit": "تعديل",
      "Category":"القسم",
      "CategoryNone":"فارغ"
    }
  }
}
</i18n>
