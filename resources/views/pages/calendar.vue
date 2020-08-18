<template>
  <layout>
    <template v-slot:topMenu>
      <li
        @click="openBoard"
        class="nav-item"
        :class="{disabled:ifChooesDay}"
        :title="openBoardByDate"
      >
        <i class="fas fa-external-link-alt"></i>
        <span :class="{'sr-only':true}">go to chooeses day</span>
      </li>
    </template>
    <breadcrumb :pages="pages"></breadcrumb>
    <calendar-table></calendar-table>
  </layout>
</template>

<script>
import { mapGetters } from "vuex";
import layout from "./layout.vue";
import breadcrumb from "../components/breadcrumb.vue";
import calendarTable from "../components/calendarTable";
export default {
  name: "calendar",
  components: {
    calendarTable,
    layout,
    breadcrumb,
  },
  data() {
    return {
      pages: {
        calendar: {
          name: "global.calendar",
          icon: "far fa-calendar-alt",
          active: true,
        },
      },
    };
  },
  methods: {
    openBoard: function () {
      if (this.openBoardByDate != undefined) {
        this.$router.push({
          name: "taskBoard",
          params: { taskBoard: this.openBoardByDate },
        });
      }
    },
  },
  computed: {
    ...mapGetters(["openBoardByDate"]),
    ifChooesDay: function () {
      if (this.openBoardByDate == undefined) {
        return true;
      } else {
        return false;
      }
    },
  },
  watch: {},
};
</script>
