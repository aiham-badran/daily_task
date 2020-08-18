<template>
  <layout>
    <template v-slot:topMenu>
      <li
        class="nav-item"
        :title="$t('trashBin.return')"
        :class="{ disabled: !selecteItme }"
        @click="returnTask"
      >
        <i class="fas fa-undo"></i>
        <span :class="{ 'sr-only': true }">Return Task</span>
      </li>
      <li
        class="nav-item deltetdCategories"
        :title="$t('trashBin.delete')"
        :class="{ disabled: !selecteItme }"
        @click="removeTask"
      >
        <i class="fa fa-trash"></i>
        <span :class="{ 'sr-only': true }">Delete Task</span>
      </li>
    </template>
    <breadcrumb :pages="pages"></breadcrumb>
    <section class="trashBin">
      <header class="row text-center">
        <div class="col-4 col-md-1">{{ $t("trashBin.header.catName") }}</div>
        <div class="col-12 col-md-9 headerDesc">{{ $t("trashBin.header.task") }}</div>
        <div class="col-8 col-md-2">{{ $t("trashBin.header.date") }}</div>
      </header>
      <section class="trashData" ref="trashDataEl">
        <div
          class="bodyRow row"
          v-for="(item, index) in trashItem"
          :key="index"
          ref="trashItems"
          @click="activeItmes($event, index)"
        >
          <div class="col-9 col-md-2" :style="'--icon-color:' + item.category.category_color">
            <i :class="item.category.category_symbol"></i>
            {{ item.category.category_name }}
          </div>
          <div class="col-12 col-md-8 bodyDesc">{{ item.task_text }}</div>
          <div class="col-3 col-md-2 text-center">{{ DeletedAt(item.deleted_at) }}</div>
        </div>
      </section>
    </section>
    <alert ref="alert"></alert>
    <!-- alert -->
  </layout>
</template>

<script>
import { mapActions } from "vuex";
import layout from "./layout.vue";
import breadcrumb from "../components/breadcrumb.vue";
import alert from "../components/alert.vue";

export default {
  name: "trashBin",
  components: {
    layout,
    breadcrumb,
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
          name: "global.trash",
          icon: "fas fa-trash",
          active: true,
        },
      },
      trashItem: {},
    };
  },
  created() {
    axios
      .get("task/trash")
      .then((response) => response.data)
      .then((data) => {
        this.trashItem = data;
      })
      .catch((error) => error.response);
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
    }),
    activeItmes: function (e, index) {
      let trashItems = this.$refs.trashItems;
      trashItems.map((item) => item.classList.remove("active"));
      let target = e.target.closest(".bodyRow");
      target.classList.add("active");
      this.selecteItme = index;
    },
    removeTask: function () {
      if (!this.selecteItme) return;

      let theId = this.selecteItme,
        ob = {
          id: this.trashItem[theId].id,
          destroy: true,
        };
      axios
        .delete("task/deleted", {
          data: ob,
        })
        .then((response) => response.data)
        .then((data) => {
          this.showAlert("Deleted Task", data.message, true);
          Vue.delete(this.trashItem, theId);
        })
        .catch((error) =>
          this.showAlert("Deleted Task", error.response.data.message, false)
        );
      this.selecteItme = "";
    },
    returnTask: function () {
      if (!this.selecteItme) return;
      let theId = this.selecteItme,
        ob = {
          id: this.trashItem[theId].id,
        };
      axios
        .put("task/restore", ob)
        .then((response) => response.data)
        .then((data) => {
          this.showAlert("Return Task", data.message, true);
          Vue.delete(this.trashItem, theId);
        })
        .catch((error) =>
          this.showAlert("Return Task", error.response.data.message, false)
        );
      this.selecteItme = "";
    },
    DeletedAt: function (date) {
      let theDate = new Date(date);
      return `${theDate.getFullYear()}-${
        theDate.getMonth() + 1
      }-${theDate.getDate()}`;
    },
    showAlert: async function (header, message, status) {
      let statusMessag, messages;
      if (status == true) statusMessag = "alert-success";
      else statusMessag = "alert-danger";
      messages = [header, message, statusMessag];
      await this.InitializeAlert(messages);
      this.$refs.alert.show();
    },
  },
  computed: {
    selecteItme: {
      get: function () {
        //console.log(this.trashItem[this.selected].id);
        return this.selected;
      },
      set: function (e) {
        this.selected = e;
      },
    },
  },
};
</script>

<i18n>
{
  "en":{
    "trashBin":{
      "header":{
        "catName":"Categories",
        "task":"Task",
        "date":"Deleted at"
      },
      "return":"Return Task",
      "delete":"Delete Task"
    }
  },
  "ar":{
      "trashBin":{
      "header":{
        "catName":"القسم",
        "task":"المهمة",
        "date":"حذفت في"
      },
      "return":"استرجاع المهمة",
      "delete":"حذف المهمة"
    }
  }
}
</i18n>
