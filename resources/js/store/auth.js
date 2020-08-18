import alert from "./alert";

const auth = {
  namespaced: true,
  state: {
    token: null,
    userProfile: null,
  },
  getters: {
    authentication: (state) => state.token && state.userProfile,
    profile: (state) => state.userProfile,
  },
  mutations: {
    setToken: (state, token) => (state.token = token),
    setUserProfile: (state, profile) => (state.userProfile = profile),
  },
  actions: {
    async singIn({ dispatch }, logInData) {
      await axios
        .post(`auth/login`, {
          email: logInData.email,
          password: logInData.password,
        })
        .then((response) => {
          return dispatch("attempt", response.data.access_token);
        })
        .catch((err) => {
          return dispatch("errors", err.response);
        });
    },
    logout({ commit }) {
      return axios.get("auth/logout").then(() => {
        commit("setToken", null);
        commit("setUserProfile", null);
      });
    },
    async attempt({ dispatch, commit, state }, token) {
      //-
      if (token) commit("setToken", token);
      //-
      if (!state.token) return;
      -(await axios
        .get("auth/profile")
        .then((response) => commit("setUserProfile", response.data))
        .catch((err) => dispatch("errors", err.response)));
    },
    async updateProfile({ dispatch }, data) {
      await axios
        .put("auth/profile/update", data)
        .then((response) => {
          let messages = [
            "Update Profile",
            response.data.message,
            "alert-success",
          ];
          this.dispatch("alert/InitializeAlert", messages);
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    errors({ commit }, error) {
      commit("setToken", null);
      commit("setUserProfile", null);
      return new Promise((resolve, reject) => {
        reject({
          status: error.status,
          errorText: error.data,
        });
      });
    },
  },
};

export default auth;
