<template>
  <main class="container-fluid mainBox">
    <side-bar></side-bar>
    <top-menu>
      <slot name="topMenu"></slot>
    </top-menu>
    <section class="viewAria">
      <slot></slot>
    </section>
  </main>
</template>

<script>
import sideBar from "../components/sideBar.vue";
import topMenu from "../components/topMenu.vue";

export default {
  name: "layout",
  components: {
    sideBar,
    topMenu
  },
  data() {
    return {
      opneSideBar: false
    };
  }
};
</script>
