<template>
  <section class="modal-container">
    <div
      class="modal fade"
      id="crateTask"
      tabindex="-1"
      role="dialog"
      aria-labelledby="crateTaskLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="crateTaskLabel">
              <i class="fas fa-file-medical"></i>
              {{$t("addTaskModal.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('addTaskModal.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="category">
              <div>{{$t('addTaskModal.Category')}}</div>
              <div v-if="categoryExist" @click="chooseCate">{{$t('addTaskModal.CategoryNone')}}</div>
              <div v-else @click="chooseCate">
                <i :class="getCategory.category_symbol"></i>
                <span>{{getCategory.category_name}}</span>
              </div>
            </div>
            <div>
              <label class="col-form-label" for="descTask">{{$t("addTaskModal.decsTask")}}</label>
              <textarea
                id="descTask"
                v-model="desc"
                class="form-control inputType"
                cols="30"
                rows="5"
                :placeholder="$t('addTaskModal.decsTaskHolder')"
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="create"
              :disabled="btnDisabled"
              data-dismiss="modal"
            >{{$t("addTaskModal.btnCreate")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  name: "newTask",
  props: ["id"],
  data() {
    return {
      desc: "",
      cateId: 0,
    };
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
    }),
    create: function () {
      let ob = {
        task_text: this.desc,
        categories_id: this.getCategory.id,
      };
      this.$emit("create", ob);
      this.desc = "";
      $("#crateTask").modal("hide");
    },
    chooseCate: function () {
      $("#chooseCategory").modal("show");
      this.$emit("theCreate");
    },
  },
  computed: {
    ...mapGetters({
      categories: "category/getCategories",
      getCategoryById: "category/getCategoryById",
    }),
    btnDisabled: function () {
      if (this.desc.length > 10 && !this.categoryExist) return false;
      else return true;
    },
    categoryExist: function () {
      if (
        this.categories[this.cateId] != null &&
        this.categories[this.cateId] != undefined
      ) {
        if (
          this.cateId != 0 &&
          Object.keys(this.categories[this.cateId]).length > 0
        )
          return false;
      }
      return true;
    },
    getCategory: function () {
      return this.getCategoryById(this.cateId);
    },
  },
  watch: {
    id: function () {
      this.cateId = this.id;
    },
  },
};
</script>


<i18n>
{
  "en":{
    "addTaskModal":{
      "decsTask": "Description Task",
      "btnClose" : "close",
      "decsTaskHolder": "Add task details (no character limit, required)",
      "titelInHeader": "Create Task",
      "btnCreate": "Create",
      "Category":"Category",
      "CategoryNone":"None"
    }
  },
  "ar":{
    "addTaskModal":{
      "decsTask": "وصف المهمة",
      "btnClose" : "إغلاق",
      "decsTaskHolder": "(إضافة المهمة ( مطلوب, لايوجد حد معين",
      "titelInHeader": "إنشاء المهمة",
      "btnCreate": "إنشاء",
      "Category":"القسم",
      "CategoryNone":"فارغ"
    }
  }
}
</i18n>
