<template>
  <nav class="navbar navbar-dark navbar-expand-xl topMenu">
    <div class="btn-group">
      <button
        type="button"
        class="btn"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <div class="userAvatarImage">
          <img :src="profileIamge" alt="avatar" class="img-thumbnail" />
        </div>
        <!-- userAvatarImage -->
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <!-- btn -->
      <ul class="dropdown-menu">
        <span class="userName">{{profile.data.username}}</span>
        <div class="dropdown-divider"></div>
        <router-link class="dropdown-item" :to="{name: 'profile'}">
          <i class="fas fa-id-card-alt"></i>
          {{$t("global.profile")}}
        </router-link>
        <!-- profile -->
        <router-link class="dropdown-item" :to="{name: 'todyBoard'}">
          <i class="far fa-clipboard"></i>
          {{$t("topMenu.todyBoard")}}
        </router-link>
        <!-- profile -->
      </ul>
      <!-- dropdown-menu -->
    </div>
    <!-- btn-group -->
    <ul class="navbar-nav">
      <li class="nav-item SideBar" @click="openSideBar()" title="Open SideBar">
        <i class="fas fa-bars"></i>
        <span :class="{'sr-only':true}">Open SideBar</span>
      </li>
      <!-- for Open SideBar in small screen  -->
      <slot></slot>
    </ul>
    <!-- navbar-nav -->
  </nav>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "topMenu",
  beforeMount: function () {
    this.screen = window.innerWidth;
  },
  data() {
    return {
      screen: 576,
      image: process.env.MIX_APP_URL + "/storage/",
      actionIcon: {
        SideBar: {
          iClass: "fas fa-bars",
          spanText: "Open SideBar",
          event: "openSideBar()",
        },
        addStep: {
          iClass: "fas fa-plus-square",
          spanText: "Add Step",
          event: "openSideBar()",
        },
        deleteTask: {
          iClass: "fas fa-trash-alt",
          spanText: "Delete ",
          event: "openSideBar()",
        },
        editTask: {
          iClass: "fas fa-edit",
          spanText: "Eidt ",
          event: "openSideBar()",
        },
        repeatTask: {
          iClass: "fas fa-sync",
          spanText: "Repeat ",
          event: "openSideBar()",
        },
        startTask: {
          iClass: "fas fa-play",
          spanText: "Start To Do ",
          event: "openSideBar()",
        },
      },
    };
  },
  methods: {
    openSideBar: function () {
      this.$root.$store.commit("toggleSideBar");
    },
  },
  computed: {
    ...mapGetters({
      profile: "auth/profile",
    }),
    profileIamge: function () {
      if (this.profile.data.personal.image) {
        return this.image + this.profile.data.personal.image;
      }
      return this.image + "images/default/profile.png";
    },
  },
};
</script>

<i18n>
{
  "en":{
    "topMenu":{
      "todyBoard":"Today's tasks"
    }
  },
  "ar":{
    "topMenu":{
      "todyBoard":"مهام اليوم"
    }
  }
}
</i18n>
