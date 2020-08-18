import router from "../routes/index";
import store from "./store";
let log = console.log;

axios.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response.status != 401) {
      return new Promise((resolve, reject) => {
        reject(error);
      });
    } else if (
      error.response.data.status == "401-1" ||
      error.response.data.status == "401-0"
    ) {
      router.push({ name: "login" });
      return new Promise((resolve, reject) => {
        reject(error);
      });
    } else if (error.response.data.status == "401-3") {
      return new Promise((resolve, reject) => {
        store.commit("auth/setToken", error.response.data.new_token);
        axios
          .get(error.config.url)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    }
  }
);

function refreshToken() {
  return new Promise((resolve, reject) => {
    axios
      .post("refresh")
      .then((response) => {
        store.commit("auth/setToken", response.data.access_token);
        resolve(response.data.access_token);
      })
      .catch((error) => reject(error));
  });
}
