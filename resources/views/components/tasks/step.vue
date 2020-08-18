<template>
  <div class="step">
    <div class="showText" v-show="!writing" @click="chooes">{{stepsTextChange}}</div>
    <div class="addStep" v-show="writing">
      <textarea
        ref="writingStep"
        :data-id="steps.id"
        class="form-control writeStep"
        v-model="stepsText"
        cols="34"
        rows="1"
        style="height:auto"
        @keyup="wirte($event)"
        placeholder="Write the Step"
      ></textarea>
      <i class="fas fa-pen" @click="addUpdateStep"></i>
    </div>
  </div>
</template>

<script>
export default {
  name: "step",
  props: ["steps", "task_id"],
  data() {
    return {
      writing: false,
      stepsText: "",
    };
  },
  methods: {
    wirte: function (e) {
      if (e.key == "Escape") {
        this.writing = false;
        return;
      }
      if (e.ctrlKey && e.key == "Enter") {
        this.addUpdateStep();
      }
      let target = e.target;
      let offset = target.offsetHeight - target.clientHeight;
      this.setHeightForTextaria("input");
    },
    chooes: function (e) {
      this.$emit("selection");
      this.setAllStepFalse(e.target);
      this.stepsText = this.stepsTextChange;
      this.writing = true;
      setTimeout(() => {
        this.$refs.writingStep.focus();
      }, 1);
      this.setHeightForTextaria("focus");
    },
    /**
     * The goal is to deselect all selected fields
     * @object target : The element from which to start
     * @viod
     */
    setAllStepFalse: function (target) {
      let Parentlen = target.closest(".tasksInBoard").children.length;
      for (let i = 0; i < Parentlen - 1; i++) {
        let children = target.closest(".tasksInBoard").children[i].children[0]
            .children[1].children,
          len = children.length;
        for (let j = 1; j < len; j++) {
          children[j].__vue__.writing = false;
        }
      }
    },
    addUpdateStep: function () {
      this.writing = false;
      let data = {
        step_text: this.stepsText,
        tasks_id: this.task_id,
        id: this.$refs.writingStep.dataset.id,
      };
      if (this.steps.id != undefined) {
        axios
          .put("step/update", data)
          .then((response) => {
            console.log(response);
            return;
            let messages = ["Upadate Step", response.data.message, true];
            this.$emit("alert", ...messages);
            this.stepsTextChange = this.stepsText;
          })
          .catch((error) => {
            let messages = ["Upadate Step", error.response.data.message, false];
            this.$emit("alert", ...messages);
          });
      } else {
        axios
          .post("step/create", data)
          .then((response) => {
            let messages = ["Create Step", response.data.message, true];
            this.$emit("alert", ...messages);
            this.stepsTextChange = this.stepsText;
            return response.data.step.id;
          })
          .then((id) => {
            Vue.set(this.steps, "id", id);
          })
          .catch((error) => {
            let messages = ["Create Step", error.response.data.message, false];
            this.$emit("alert", ...messages);
          });
      }
    },
    setHeightForTextaria: function (eventListener) {
      let offset =
        this.$refs.writingStep.offsetHeight -
        this.$refs.writingStep.clientHeight;
      this.$refs.writingStep.addEventListener(eventListener, function (event) {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + offset + "px";
      });
    },
  },
  computed: {
    stepsTextChange: {
      get: function () {
        return this.steps.step_text;
      },
      set: function (text) {
        this.steps.step_text = text;
      },
    },
  },
};
</script>
