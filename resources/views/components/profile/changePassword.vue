<template>
  <section class="modal-container password">
    <div
      class="modal fade"
      id="profilePassword"
      tabindex="-1"
      role="dialog"
      aria-labelledby="profilePasswordLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profilePasswordLabel">
              <i class="fas fa-unlock"></i>
              {{$t("changePassword.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('changePassword.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="oldPassword">
              <label for="oldPass">{{$t("changePassword.oldPassword")}}</label>
              <input
                class="form-control inputType"
                type="password"
                id="oldPass"
                v-model="oldPassword"
              />
            </div>
            <div class="newPassword">
              <label for="newPass">{{$t("changePassword.newPassword")}}</label>
              <input
                class="form-control inputType"
                type="password"
                id="newPass"
                v-model="newPassword"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              :class="{disabled:emptyPassword}"
              @click="changePassword"
            >{{$t("changePassword.btnUpdate")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "changePassword",
  props: ["user"],
  data() {
    return {
      oldPassword: "",
      newPassword: ""
    };
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert"
    }),
    changePassword: function() {
      if (!this.emptyPassword) {
        let pass = {
          id: this.user,
          oldPassword: this.oldPassword,
          newPassword: this.newPassword
        };
        axios
          .put("auth/profile/password", pass)
          .then(response => {
            let mess = [
              "Changed Password",
              response.data.message,
              "alert-success"
            ];
            this.responseAction(mess);
            $("#profilePassword").modal("hide");
          })
          .catch(error => {
            let mess = [
              "Changed Password",
              error.response.data.Message,
              "alert-danger"
            ];
            this.responseAction(mess);
          });
      }
    },
    responseAction: async function(messages) {
      await this.InitializeAlert(messages);
      this.oldPassword = "";
      this.newPassword = "";

      this.$emit("sendAlert");
    }
  },
  computed: {
    emptyPassword: function() {
      if (this.oldPassword == "" || this.newPassword == "") return true;
      else return false;
    }
  }
};
</script>

<i18n>
{
  "en":{
    "changePassword":{
      "titelInHeader":"Changed Password",
      "btnClose":"Close",
      "btnUpdate":"Edit Password",
      "newPassword":"New Password",
      "oldPassword":"Current Password"
    }
  },
  "ar":{
      "changePassword":{
      "titelInHeader":" تعديل كلمة السر",
      "btnClose":"إغلاق",
      "btnUpdate":"تعديل كلمة السر",
      "newPassword":"كملة السر الجديدة",
      "oldPassword":"كلمة السر الحالية"
    }
  }
}
</i18n>
