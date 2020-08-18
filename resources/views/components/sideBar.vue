<template>
  <div class="sideBar text-center py-2" :class="{openSideBar:stateSideBar}">
    <i
      class="fas fa-times closeSideBar"
      :class="{showIconClose:stateSideBar}"
      @click="toggleSideBar()"
    ></i>
    <div class="logo">
      <router-link :to="{name : 'home'}">
        <p class="logoText">Tasks</p>
      </router-link>
    </div>
    <div class="links">
      <div class="mainLinks">
        <div
          v-for="(val,name,index) in actionIcon"
          :key="index"
          class="action"
          :class="name"
          :title="$t(val.spanText)"
        >
          <router-link :to="val.link">
            <i :class="val.iClass"></i>
            <span>{{$t(val.spanText)}}</span>
          </router-link>
        </div>
      </div>
      <div class="footerLinks">
        <div class="action setting" :title="$t(footerIcon.setting.spanText)">
          <router-link :to="footerIcon.setting.link">
            <i :class="footerIcon.setting.iClass"></i>
            <span>{{$t(footerIcon.setting.spanText)}}</span>
          </router-link>
        </div>
        <div class="action signOut" @click="signOut" :title="$t(footerIcon.signOut.spanText)">
          <i :class="footerIcon.signOut.iClass"></i>
          <span>{{$t(footerIcon.signOut.spanText)}}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
export default {
  name: "side-bar",
  data() {
    return {
      actionIcon: {
        calendar: {
          iClass: "far fa-calendar-alt",
          spanText: "global.calendar",
          link: { name: "calendar" }
        },
        categories: {
          iClass: "fas fa-clipboard-list",
          spanText: "global.categories",
          link: { name: "categories" }
        },
        deleted: {
          iClass: "fas fa-trash",
          spanText: "global.trash",
          link: { name: "trashBin" }
        }
      },
      footerIcon: {
        setting: {
          iClass: "fas fa-sliders-h",
          spanText: "global.setting",
          link: { name: "setting" }
        },
        signOut: {
          iClass: "fas fa-sign-out-alt",
          spanText: "global.logout",
          link: "#"
        }
      }
    };
  },
  methods: {
    ...mapMutations(["toggleSideBar"]),
    ...mapActions({ logout: "auth/logout" }),
    signOut: function() {
      this.logout().then(() => this.$router.replace({ name: "login" }));
    }
  },
  computed: {
    ...mapGetters(["stateSideBar"])
  }
};
</script>
