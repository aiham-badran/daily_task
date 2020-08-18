<template>
  <layout>
    <template v-slot:topMenu>
      <li
        class="nav-item"
        title="Create Categories"
        data-toggle="modal"
        data-target="#crateCategories"
      >
        <i class="fas fa-plus"></i>
        <span :class="{ 'sr-only': true }">Create Categories</span>
      </li>
      <li
        class="nav-item editCategories"
        title="Edit Categories"
        :class="{ disabled: !selecteCategories }"
        @click="editModal"
      >
        <i class="fas fa-pen"></i>
        <span :class="{ 'sr-only': true }">Edit Categories</span>
      </li>
      <li
        class="nav-item deltetdCategories"
        title="Delete Categories"
        :class="{ disabled: !selecteCategories }"
        @click="deleteModal"
      >
        <i class="fa fa-trash"></i>
        <span :class="{ 'sr-only': true }">Delete Categories</span>
      </li>
    </template>
    <breadcrumb :pages="pages"></breadcrumb>
    <section class="categoriesPage">
      <header class="row text-center">
        <div class="col-4 col-md-1">{{ $t("categories.header.catName") }}</div>
        <div class="col-12 col-md-9 headerDesc">{{ $t("categories.header.catDesc") }}</div>
        <div class="col-8 col-md-2">{{ $t("categories.header.countTask") }}</div>
      </header>
      <section class="cateData" ref="cateDataEl">
        <div
          class="bodyRow row"
          v-for="(cat, index) in categories"
          :key="index"
          ref="categories"
          @click="activeCategories($event, index)"
        >
          <div class="col-9 col-md-2" :style="'--icon-color:' + cat.category_color">
            <i :class="cat.category_symbol"></i>
            {{ cat.category_name }}
          </div>
          <div class="col-12 col-md-8 bodyDesc">{{ cat.category_description }}</div>
          <div class="col-3 col-md-2 text-center">{{ getCountTask(index) }}</div>
        </div>
      </section>
    </section>
    <!--  -->
    <create-categories v-on:add="addCate"></create-categories>
    <!-- create modal -->
    <delete-categories v-on:remove="removeCate"></delete-categories>
    <!-- delete modal -->
    <edit-categories v-on:edit="EditCate" :categories="cateSeleted" ref="editCategory"></edit-categories>
    <!-- edit modal -->
    <alert ref="alert"></alert>
    <!-- alert -->
  </layout>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import layout from "./layout.vue";
import breadcrumb from "../components/breadcrumb.vue";
import createCategories from "../components/categories/newCate.vue";
import editCategories from "../components/categories/editCate.vue";
import deleteCategories from "../components/categories/deleteCate.vue";
import alert from "../components/alert.vue";

export default {
  name: "categories",
  components: {
    layout,
    breadcrumb,
    createCategories,
    deleteCategories,
    editCategories,
    alert,
  },
  data() {
    return {
      selected: "",
      pages: {
        calendar: {
          name: "global.calendar",
          icon: "far fa-calendar-alt",
          link: { name: "calendar" },
          active: false,
        },
        categories: {
          name: "global.categories",
          icon: "fas fa-clipboard-list",
          active: true,
        },
      },
    };
  },
  async created() {
    if (Object.keys(this.categories).length == 0) {
      await this.fetchCategory();
    }
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
      fetchCategory: "category/fetchCategory",
    }),
    activeCategories: function (e, index) {
      let categories = this.$refs.categories;
      categories.map((category) => category.classList.remove("active"));
      let target = e.target.closest(".bodyRow");
      target.classList.add("active");
      this.selecteCategories = index;
    },
    getCountTask: function (index) {
      return this.taskCount[index];
    },
    addCate: async function (cate) {
      await axios
        .post("category/create", cate)
        .then((response) => {
          let mess = [
            "Create Category",
            response.data.message,
            "alert-success",
          ];
          Vue.set(
            this.categories,
            response.data.category.id,
            response.data.category
          );
          this.showAlert(mess);
        })
        .catch((error) => {
          let mess = [
            "Create Category",
            error.response.data.category_name,
            "alert-danger",
          ];
          this.showAlert(mess);
        });
    },
    editModal: function () {
      if (this.selected != "" && this.selected != undefined) {
        this.$refs.editCategory.setCategories;
        $("#editCategories").modal("show");
      }
    },
    EditCate: async function (cate) {
      await axios
        .put("category/update", cate)
        .then((response) => {
          let mess = ["Edit Category", response.data.message, "alert-success"];
          Vue.set(this.categories, this.selected, cate);
          this.showAlert(mess);
        })
        .catch((error) => {
          let mess = [
            "Edit Category",
            error.response.data.category_name,
            "alert-danger",
          ];
          this.showAlert(mess);
        });
    },
    deleteModal: function () {
      if (this.selected != "" && this.selected != undefined)
        $("#deleteCategories").modal("show");
    },
    removeCate: async function () {
      await axios
        .delete("category/deleted", {
          params: { id: this.categories[this.selected].id },
        })
        .then((response) => {
          let mess = [
            "Delete Category",
            response.data.message,
            "alert-success",
          ];
          Vue.delete(this.categories, this.selected);
          this.showAlert(mess);
        })
        .catch((error) => {
          let mess = [
            "Delete Category",
            error.response.data.message,
            "alert-danger",
          ];
          this.showAlert(mess);
        });
    },
    showAlert: async function (messages) {
      await this.InitializeAlert(messages);
      this.$refs.alert.show();
    },
  },
  computed: {
    ...mapGetters({
      categories: "category/getCategories",
      taskCount: "category/getCountTaskInCate",
    }),
    selecteCategories: {
      get: function () {
        return this.selected;
      },
      set: function (e) {
        this.selected = e;
      },
    },
    cateSeleted: function () {
      if (this.categories) {
        if (this.categories[this.selected] != undefined)
          return this.categories[this.selected];
      }
    },
  },
};
</script>

<i18n>
{
  "en":{
    "categories":{
      "header":{
        "catName":"Categories",
        "catDesc":"Categories Description",
        "countTask":"Count Of Task"
      }
    }
  },
  "ar":{
      "categories":{
      "header":{
        "catName":"القسم",
        "catDesc":"وصف القسم",
        "countTask":"عدد المهام"
      }
    }
  }
}
</i18n>
