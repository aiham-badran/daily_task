<template>
  <layout>
    <template v-slot:topMenu>
      <li class="nav-item" :title="$t('taskBoard.createTasks')" data-toggle="modal" @click="create">
        <i class="fas fa-plus"></i>
        <span :class="{ 'sr-only': true }">
          {{
          $t("taskBoard.createTasks")
          }}
        </span>
      </li>
      <li
        class="nav-item editTask"
        :title="$t('taskBoard.editTask')"
        data-toggle="modal"
        @click="edit"
        :class="{ disabled: !selectedTask }"
      >
        <i class="fas fa-pen"></i>
        <span :class="{ 'sr-only': true }">{{ $t("taskBoard.editTask") }}</span>
      </li>
      <li
        class="nav-item deltetdTask"
        :title="$t('taskBoard.deleteTask')"
        :class="{ disabled: !selectedTask }"
        data-toggle="modal"
        @click="deleteTask"
      >
        <i class="fa fa-trash"></i>
        <span :class="{ 'sr-only': true }">
          {{
          $t("taskBoard.deleteTask")
          }}
        </span>
      </li>
    </template>
    <breadcrumb :pages="pages"></breadcrumb>
    <div class="tasksInBoard">
      <task
        ref="taskComp"
        v-for="(task, index) in tasks"
        :key="index"
        v-on:selection="selectedTask = index"
        v-on:alert="showAlert"
        :task="task"
      ></task>
      <div class="afterLastTask"></div>
    </div>
    <new-task v-on:create="addTask" :id="categoryId"></new-task>
    <eidt-task ref="editModal" v-on:edit="updateTask" :update="update" :id="categoryId"></eidt-task>
    <delete-task v-on:remove="removeTask"></delete-task>
    <choose :categories="categories" @setCategroy="setCategroy" />
    <!-- choose for choose categories -->
    <alert ref="alert"></alert>
    <!-- alert -->
  </layout>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import breadcrumb from "../components/breadcrumb.vue";
import layout from "./layout.vue";
import task from "../components/tasks/task.vue";
import newTask from "../components/tasks/newTask.vue";
import eidtTask from "../components/tasks/editTask.vue";
import deleteTask from "../components/tasks/deleteTask.vue";
import choose from "../components/categories/choose.vue";
import alert from "../components/alert.vue";
export default {
  name: "home",
  components: {
    layout,
    breadcrumb,
    task,
    newTask,
    eidtTask,
    deleteTask,
    choose,
    alert,
  },
  data() {
    return {
      categoryId: 0,
      date: this.theDate,
      selected: "",
      pages: {
        calendar: {
          name: "global.calendar",
          icon: "far fa-calendar-alt",
          link: { name: "calendar" },
          active: false,
        },
        date: {
          name: this.theDate(),
          icon: "far fa-clipboard",
          active: true,
        },
      },
      tasks: {},
    };
  },
  created: function () {
    document.addEventListener("click", this.eventDocument);
    let date = new Date(this.theDate());
    let theDate = `${date.getFullYear()}-${
      date.getMonth() + 1
    }-${date.getDate()}`;
    axios
      .post("task/tasks", { task_at: theDate })
      .then((response) => {
        if (response.data.status == 220) return;
        else this.tasks = response.data;
      })
      .catch((error) => error.response);
  },
  destroyed() {
    document.removeEventListener("click", this.eventDocument);
  },
  mounted() {
    if (Object.keys(this.categories).length == 0) {
      this.fetchCategory();
    }
    $("#crateTask").on("hide.bs.modal", () => {
      this.cateId = 0;
    });
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
      fetchCategory: "category/fetchCategory",
    }),
    eventDocument: function () {
      let task = window.event.target.closest("section.task"),
        edit = window.event.target.closest("li.editTask"),
        editModal = window.event.target.closest("section.eidtModal"),
        deleted = window.event.target.closest("li.deltetdTask"),
        deletedModal = window.event.target.closest("section.deleteModal"),
        chooseCategoryModal = window.event.target.closest(
          "section.chooseCategoryModal"
        );
      let childTask = this.$refs.taskComp;
      if (childTask != undefined) {
        if (task == null) {
          if (childTask[0] != undefined)
            childTask[0].setAllTasksFalse(childTask[0].$el);
          if (
            edit == null &&
            editModal == null &&
            deleted == null &&
            deletedModal == null &&
            chooseCategoryModal == null
          )
            this.selectedTask = "";
        }
        //
        let step = window.event.target.closest("div.step");
        if (step == null) {
          let firstChildstep = () => {
            for (let i = 0; i < childTask.length; i++)
              if (childTask[i].$refs.step != undefined)
                if (childTask[i].$refs.step[0] != undefined)
                  return childTask[i].$refs.step[0];
          };
          let childstep = firstChildstep();
          if (childstep != undefined) {
            childstep.setAllStepFalse(childstep.$el);
            for (let i = 0; i < childTask.length; i++) {
              childTask[i].selected = "";
            }
          }
        }
      }
    },
    theDate: function () {
      let date = new Date(this.$route.params.taskBoard);
      return date.toDateString();
    },
    theDateNumber: function () {
      let date = new Date(this.$route.params.taskBoard);
      return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
    },
    addTask: function (task) {
      let TaksKeys = Object.keys(this.tasks),
        afterLastKeys;
      if (TaksKeys.length > 0) {
        afterLastKeys =
          parseInt(TaksKeys[TaksKeys.length - 1].match(/[0-9]+/g)[0]) + 1;
      } else afterLastKeys = 1;
      Vue.set(task, "task_at", this.theDateNumber());
      axios
        .post("task/create", task)
        .then((response) => response.data)
        .then((data) => {
          Vue.set(this.tasks, data.task.id, data.task);
          this.showAlert("Create Task", data.message, true);
        })
        .catch((error) => {
          this.showAlert("Create Task", error.response.data.message, false);
        });
      this.categoryId = 0;
    },
    updateTask: function (task) {
      axios
        .put("task/update", task)
        .then((response) => response.data)
        .then((data) => {
          Vue.set(this.tasks, data.task.id, data.task);
          this.showAlert("Update Task", data.message, true);
        })
        .catch((error) => {
          console.log(error.response.data);
          this.showAlert("Update Task", error.response.data.message, false);
        });
      // Vue.set(this.tasks, this.selectedTask, task);
      this.selectedTask = "";
      this.categoryId = 0;
    },
    removeTask: function (checked) {
      let id = this.selectedTask;
      axios
        .delete("task/deleted", {
          data: {
            id: id,
            destroy: checked,
          },
        })
        .then((response) => response.data)
        .then((data) => {
          Vue.delete(this.tasks, id);
          this.showAlert("Delete Task", data.message, true);
        })
        .catch((error) => {
          console.log(error.response);
          this.showAlert("Delete Task", error.response.data.message, false);
        });

      this.selectedTask = "";
    },
    create: function () {
      $("#crateTask").modal("show");
      $("#crateTask").on("hide.bs.modal", () => {
        this.categoryId = 0;
      });
    },
    edit: function () {
      if (
        this.selected != "" &&
        this.selected != null &&
        this.selected != undefined
      ) {
        this.$refs.editModal.EidtData;
        $("#editTask").modal("show");
        $("#editTask").on("hide.bs.modal", () => {
          this.selectedTask = "";
          this.categoryId = 0;
        });
      }
    },
    deleteTask: function () {
      if (
        this.selected != "" &&
        this.selected != null &&
        this.selected != undefined
      ) {
        $("#deleteModal").modal("show");
        $("#deleteModal").on("hide.bs.modal", () => {
          this.selectedTask = "";
        });
      }
    },
    showAlert: async function (header, message, status) {
      let statusMessag, messages;
      if (status == true) statusMessag = "alert-success";
      else statusMessag = "alert-danger";
      messages = [header, message, statusMessag];
      await this.InitializeAlert(messages);
      this.$refs.alert.show();
    },
    setCategroy: function (id) {
      this.categoryId = id;
    },
  },
  computed: {
    ...mapGetters({
      categories: "category/getCategories",
      getCategoryById: "category/getCategoryById",
    }),
    selectedTask: {
      get: function () {
        return this.selected;
      },
      set: function (task) {
        this.selected = task;
      },
    },
    update: {
      get: function () {
        return this.tasks[this.selectedTask];
      },
    },
    setcategoryIdCreate: function () {
      this.categoryIdCreate = this.categoryId;
    },
  },
};
</script>

<i18n>
{
  "en":{
    "taskBoard":{
      "createTasks":"Create Tasks",
      "editTask":"Edit Task",
      "deleteTask":"Delete Task"
    }
  },
  "ar":{
    "taskBoard":{
      "createTasks":"إنشاء مهمة",
      "editTask":"تعديل المهمة",
      "deleteTask":"حذف المهمة"
    }
  }
}
</i18n>
