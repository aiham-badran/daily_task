<template>
  <div>
    <div v-for="(alert,index) in alerts" :key="index" class="alertBox" ref="alertBox">
      <div class="alert alert-dismissible fade show" :class="alert.alertStatus" role="alert">
        <button type="button" class="close" aria-label="Close" @click="hide">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- button.close -->
        <h3
          class="alert-head"
          style="font-weight: bold; text-transform: capitalize;"
        >{{alert.alertHeader}}</h3>
        <!-- alert-head -->
        <div class="alert-body" style="font-weight: bold;">{{alert.alertMessage}}</div>
        <!-- alert body -->
      </div>
      <!-- alert -->
    </div>
    <!-- alert box -->
  </div>
  <!--  -->
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "alert",
  updated() {
    let boxs = this.$refs.alertBox,
      top = 85,
      time = 9200;
    setTimeout(() => {
      boxs.forEach((box) => {
        box.classList.remove("show");
      });
    }, time);
    boxs.forEach((box, index) => {
      if (index > 0) top += boxs[index - 1].offsetHeight;
      box.style.top = top + "px";
    });
  },
  methods: {
    hide: function (e) {
      let box = e.target.closest("div.alertBox");
      if (box.classList.contains("show")) box.classList.remove("show");
    },
    show: function () {
      let time = 0;
      this.$refs.alertBox.forEach((box) => {
        setTimeout(() => {
          box.classList.add("show");
        }, time);
        time += 500;
      });
    },
  },
  computed: {
    ...mapGetters({
      alerts: "alert/getAlerts",
    }),
  },
};
</script>
