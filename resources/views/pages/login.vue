<template>
  <section class="login d-flex justify-content-center align-items-center">
    <section class="container">
      <section class="row">
        <div class="logInImage col-6 d-none d-md-block">
          <img
            class="embed-responsive"
            src="/storage/images/login/todoList-by-freepik.com.jpg"
            alt="todoList-by-freepik.com.jpg"
          />
        </div>
        <!-- logInImage -->
        <div class="logInForm col-12 col-md-6">
          <div class="card">
            <div class="card-header text-center">
              <h1>{{$t("login.logInHeaderText")}}</h1>
            </div>
            <!-- card-header -->
            <div class="card-body">
              <div class="form-group">
                <div class="loginInput logInUser">
                  <label for="username" class="sr-only">{{$t("login.inputEmail")}}</label>
                  <i class="fas fa-user"></i>
                  <input
                    type="text"
                    class="form-control"
                    v-model="email"
                    :placeholder="$t('login.inputEmail')"
                  />
                </div>
                <!-- logInUser -->
                <div class="loginInput logInPass">
                  <label for="password" class="sr-only">{{$t('login.inputPassword')}}</label>
                  <i class="fas fa-unlock"></i>
                  <input
                    type="password"
                    class="form-control"
                    v-model="password"
                    :placeholder="$t('login.inputPassword')"
                  />
                </div>
                <!-- logInPass -->
                <div class="logInBtn">
                  <button
                    class="btn btn-primary btn-block btn-lg"
                    @click="logIn"
                  >{{$t('login.singInBtn')}}</button>
                </div>
                <!-- logInBtn -->
              </div>
              <!-- form-group -->
            </div>
            <!-- card-body -->
            <div class="card-footer" v-show="errors">
              <template v-for="(error,index) in errors">
                <alert alertStatus="alert-danger" :key="index">
                  <template v-slot:alertHead>{{index}}</template>
                  <ul class="list-unstyled">
                    <template v-if="errorIsArray(error)">
                      <li v-for="(text,number) in error" :key="number">{{text}}</li>
                    </template>
                    <template v-else>
                      <li>{{error}}</li>
                    </template>
                  </ul>
                </alert>
              </template>
            </div>
            <!-- card footer -->
          </div>
          <!-- card -->
        </div>
        <!-- logInForm -->
      </section>
      <!-- row -->
    </section>
    <!-- container -->
    <alert ref="alert"></alert>
    <!-- alert -->
  </section>
  <!-- login -->
</template>

<script>
import { mapActions } from "vuex";
import alert from "../components/alert.vue";

export default {
  name: "login",
  components: {
    alert,
  },
  data() {
    return {
      email: "",
      password: "",
      errors: null,
    };
  },
  methods: {
    ...mapActions({
      singIn: "auth/singIn",
      InitializeAlert: "alert/InitializeAlert",
    }),
    logIn: function () {
      const logInData = {
        email: this.email,
        password: this.password,
      };
      this.singIn(logInData)
        .then(() => this.$router.push({ name: "todyBoard" }))
        .catch((error) => {
          let message,
            messages = [],
            status = "log in Error";
          if (error.status != 500) {
            for (let text in error.errorText) {
              messages[text] = error.errorText[text];
            }
            let mess = ["Login Error", messages, "alert-danger"];
            this.showAlert(mess);
          } else {
            message =
              "The server is experiencing some problems. Please try again later or contact the server administrator to solve the problem";
            let mess = ["Server Error", message, "alert-danger"];
            this.showAlert(mess);
          }
        });
    },
    errorIsArray: (error) => {
      if (Array.isArray(error) || typeof error == "object") return true;
      else return false;
    },
    showAlert: async function (messages) {
      await this.InitializeAlert(messages);
      this.$refs.alert.show();
    },
  },
  computed: {},
};
</script>

<i18n>
{
  "en":{
    "login":{
      "logInHeaderText":"Daily Task - My Account",
      "inputEmail":"Eamil Address",
      "inputPassword":"Password",
      "singInBtn":"Sing in"
    }
  },
  "ar":{
    "login":{
      "logInHeaderText":"حسابي - المهام اليومية",
      "inputEmail":"البريدي الألكتروني",
      "inputPassword":"كلمة السر ",
      "singInBtn":"تسجيل الدخول"
    }
  }
}
</i18n>
