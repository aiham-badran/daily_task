<template>
  <section class="taskContainer" :style="'--color-cat:' + task.category.category_color + 'b8'">
    <!-- <time class="time" :class="{ active: taskActive }" datetime="12-10-2020 12:6">
      <i class="far fa-clock"></i>
      {{ time }}
    </time>-->
    <section class="task" :class="{ active: taskActive }" @click="activeTask">
      <header>
        <div class="cat">
          <i :class="task.category.category_symbol"></i>
          {{ task.category.category_name }}
        </div>
        <div class="state active" v-if="completed" @click="completedTask">
          <i class="fas fa-clipboard-check"></i>
          {{ $t("tasks.completed") }}
        </div>
        <div class="state" v-else @click="completedTask">
          <i class="fas fa-clipboard"></i>
          {{ $t("tasks.Uncomplete") }}
        </div>
      </header>
      <section class="taskBody" ref="taskBody">
        <div class="title">{{ task.task_text }}</div>
        <step
          v-for="(step, index) in steps"
          :key="index + 1"
          v-on:selection="selectedStep = index"
          v-on:alert="sendAlert"
          :steps="step"
          :task_id="task.id"
          ref="step"
        ></step>
      </section>
      <footer>
        <div class="tools">
          <i class="fas fa-plus" aria-hidden="true" :title="$t('tasks.addStep')" @click="addStep()"></i>
          <i
            class="fa fa-trash"
            :class="{ displayed: isSeleted }"
            aria-hidden="true"
            :title="$t('tasks.removeStep')"
            @click="removeStep()"
          ></i>
        </div>
      </footer>
    </section>
  </section>
</template>

<script>
import step from "./step.vue";
export default {
  name: "task",
  components: {
    step,
  },
  props: ["task"],
  data() {
    return {
      selected: "",
      taskActive: false,
    };
  },
  methods: {
    addStep: function () {
      let ob = {
        step_text: "Write Step",
      };
      let stepsKeys = Object.keys(this.task.step),
        afterLastKeys;
      if (stepsKeys.length > 0) {
        afterLastKeys =
          parseInt(stepsKeys[stepsKeys.length - 1].match(/[0-9]+/g)[0]) + 1;
      } else afterLastKeys = 1;
      Vue.set(this.task.step, `${afterLastKeys}`, ob);
    },
    removeStep: function () {
      if (!this.isSeleted) {
        let d = {
          id: this.selectedStep,
        };
        axios
          .delete("step/deleted", {
            data: d,
          })
          .then((response) => {
            return response;
          })
          .then((response) => {
            let messages = ["Delete Step", response.data.message, true];
            this.sendAlert(...messages);
            Vue.delete(this.task.step, d.id);
          })
          .catch((error) => {
            let messages = ["Delete Step", error.response.data.message, false];
            this.sendAlert(...messages);
          });
      }
    },
    activeTask: function (e) {
      this.$emit("selection");
      this.setAllTasksFalse(e.target);
      this.taskActive = true;
    },
    setAllTasksFalse: function (target) {
      let children = target.closest(".tasksInBoard").children,
        len = children.length;
      for (let i = 0; i < len - 1; i++) {
        children[i].__vue__.taskActive = false;
      }
    },
    sendAlert: function (header, messsage, status) {
      this.$emit("alert", header, messsage, status);
    },
    completedTask: function () {
      axios
        .put("task/completed", { completed: !this.completed, id: this.task.id })
        .then((response) => response.data)
        .then((data) => {
          let messages = ["Task Stutas", data.message, true];
          this.sendAlert(...messages);
          this.completed = !this.completed;
        })
        .catch((error) => {
          let messages = ["Task Stutas", error.response.data.message, false];
          this.sendAlert(...messages);
        });
    },
  },
  computed: {
    time: function () {
      const time = new Date(this.task.task_at);
      let m = time.getMinutes();
      if (m < 10) m = `0${m}`;
      let h = time.getHours();
      return `${h}:${m}`;
    },
    completed: {
      get: function () {
        return this.task.task_completed;
      },
      set: function (state) {
        this.task.task_completed = state;
      },
    },
    steps: {
      get: function () {
        return this.task.step;
      },
    },
    selectedStep: {
      get: function () {
        return this.selected;
      },
      set: function (step) {
        this.selected = step;
      },
    },
    isSeleted: function () {
      if (
        this.selected != "" &&
        this.selected != undefined &&
        this.selected != null
      )
        return false;
      else return true;
    },
  },
};
</script>

<i18n>
{
  "en":{
    "tasks":{
      "completed":"completed",
      "Uncomplete":"Uncomplete",
      "addStep":"Add Step",
      "removeStep":"Remove Step"
    }
  },
  "ar":{
    "tasks":{
      "completed":"منجزة",
      "Uncomplete":"غير منجزة",
      "addStep":"إضافة خطوة",
      "removeStep":"خذف خطوة"
    }
  }
}
</i18n>
