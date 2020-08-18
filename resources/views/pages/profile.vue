<template>
  <layout>
    <template v-slot:topMenu></template>
    <breadcrumb :pages="pages"></breadcrumb>
    <section class="profile">
      <header class="profileHeader d-flex flex-column justify-content-center align-items-center">
        <div class="profileImg" @click="imageChanged" title="Change Profile Image">
          <img class="img-thumbnail rounded" :src="ProfileIamg" alt="avatar" />
        </div>
        <!-- profileImg -->
      </header>
      <!-- header -->
      <section class="profileData">
        <div class="profileDataAccount">
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.UserName")}}</label>
            <input type="text" id="username" v-model="profile.data.username" disabled />
          </div>
          <!-- profileDataField -->
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.Email")}}</label>
            <input type="text" id="username" v-model="profile.data.email" disabled />
          </div>
          <!-- profileDataField -->
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.password")}}</label>
            <div class="btnPassword">
              <button
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#profilePassword"
              >{{$t("profile.changePassword")}}</button>
            </div>
          </div>
          <!-- profileDataField -->
        </div>
        <!-- profileDataAccount -->
        <div class="profileDataPersonle">
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.FullName")}}</label>
            <input
              type="text"
              id="username"
              v-model="profile.data.personal.fullName"
              :placeholder="$t('profile.FullNameHolder')"
            />
          </div>
          <!-- profileDataField -->
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.PhoneNumber")}}</label>
            <input
              type="text"
              id="username"
              v-model="profile.data.personal.phone"
              :placeholder="$t('profile.PhoneNumberHolder')"
            />
          </div>
          <!-- profileDataField -->
          <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">{{$t("profile.Work")}}</label>
            <input
              type="text"
              id="username"
              v-model="profile.data.personal.job"
              :placeholder="$t('profile.WorkHolder')"
            />
          </div>
          <!-- profileDataField -->
          <!-- <div class="d-flex justify-content-between flex-md-row flex-column profileDataField">
            <label for="username">Address</label>
            <input type="text" id="username" placeholder="Add your Adrress" />
          </div>-->
          <!-- profileDataField -->
        </div>
        <!-- profileDataPersonle -->
        <div class="profileSaveData d-flex justify-content-end">
          <button class="btn btn-primary" @click="sendData">{{$t('profile.updateBtn')}}</button>
        </div>
        <!-- profileSaveData -->
      </section>
      <!-- profileData -->
      <changePassword :user="profile.data.id" v-on:sendAlert="showAlert" />
      <!-- changePassword -->
      <changeImage :user="profile.data.id" v-on:sendAlert="showAlert" />
      <!-- changeImage -->
    </section>
    <!-- profile -->
    <alert ref="alert"></alert>
    <!-- alert  -->
  </layout>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import layout from "./layout.vue";
import changePassword from "../components/profile/changePassword.vue";
import changeImage from "../components/profile/changeImage.vue";
import alert from "../components/alert.vue";
import breadcrumb from "../components/breadcrumb.vue";
export default {
  name: "profile",
  components: {
    layout,
    changePassword,
    changeImage,
    alert,
    breadcrumb,
  },
  data() {
    return {
      image: process.env.MIX_APP_URL + "/storage/",
      height: 0,
      pages: {
        setting: {
          name: "global.setting",
          icon: "fas fa-sliders-h",
          link: { name: "setting" },
          active: false,
        },
        categories: {
          name: "global.profile",
          icon: "fas fa-id-card-alt",
          active: true,
        },
      },
    };
  },
  methods: {
    ...mapActions({
      upadate: "auth/updateProfile",
    }),
    sendData: async function () {
      let profile_data = {
        id: this.profile.data.id,
        personal: this.profile.data.personal,
      };
      await this.upadate(profile_data);
      this.showAlert();
    },
    imageChanged: function () {
      $("#profileIamge").modal("show");
    },
    showAlert: function () {
      this.$refs.alert.show();
    },
  },
  computed: {
    ...mapGetters({
      profile: "auth/profile",
    }),
    ProfileIamg: function () {
      if (this.profile.data.personal.image) {
        console.log(this.image + this.profile.data.personal.image);
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
    "profile":{
      "UserName" : "User Name",
      "password" : "Password",
      "Email": "Emial",
      "changePassword": "Change Password",
      "FullName" : "Full Name",
      "FullNameHolder":"Add your Full Name",
      "PhoneNumber": "Phone Number",
      "PhoneNumberHolder":"Add Your Phone Number",
      "Work": "Work",
      "WorkHolder":"Add your Work",
      "updateBtn":"Update Profile"
    }
  },
  "ar":{
    "profile":{
      "UserName" : "اسم المستخدم",
      "password" : "كلمة السر",
      "Email": "البريد الالكتروني",
      "changePassword": "تغير كلمة السر",
      "FullName" : "الاسم الكامل",
      "FullNameHolder":"إضافة اسمك الكامل",
      "PhoneNumber": "رقم الهاتف",
      "PhoneNumberHolder":"إضافة رقم هاتفك",
      "Work": "العمل ",
      "WorkHolder":"إضافة عملك ",
      "updateBtn":"تحديث الملف الشخصي"
    }
  }
}
</i18n>
