<template>
  <section class="modal-container profileImg">
    <div
      class="modal fade"
      id="profileIamge"
      tabindex="-1"
      role="dialog"
      aria-labelledby="profileIamgeLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profileIamgeLabel">
              <i class="far fa-images"></i>
              {{$t("profileIamge.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('profileIamge.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-4">
            <div class="row flex-column-reverse">
              <div class="imageView col-6 col-md-3">
                <img class="img-thumbnail rounded" :src="image" alt />
              </div>
              <div class="imageFile col-12 col-md-9 d-flex justify-content-around">
                <div class="custom-file">
                  <span class="newImage" for="newImage">{{$t("profileIamge.chooseImage")}}</span>
                  <form enctype="multipart/form-data" style="display:none">
                    <input
                      class="custom-file-input"
                      type="file"
                      id="newImage"
                      ref="file"
                      style="display:none"
                      @change="uplodeImage"
                    />
                  </form>
                  <label
                    class="btn btn-primary btnUpldeIamge"
                    for="newImage"
                  >{{$t("profileIamge.UplodeIamgeBtn")}}</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="sendImage"
            >{{$t("profileIamge.btnUpdate")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  name: "changeImage",
  props: ["user"],
  data() {
    return {
      image: "",
    };
  },
  created() {
    this.setImage;
  },
  methods: {
    ...mapActions({
      InitializeAlert: "alert/InitializeAlert",
    }),
    sendImage: function () {
      const theRoot = this;
      let file = new FormData();
      file.append("image", this.$refs.file.files[0]);
      file.append("currentImage", this.profile.data.personal.image);
      file.append("id", this.user);
      axios
        .post("auth/profile/image", file)
        .then(async (response) => {
          this.profile.data.personal.image = response.data.imageUrl;
          await theRoot.InitializeAlert([
            "Profile Image",
            response.data.message,
            "alert-success",
          ]);
          this.$emit("sendAlert");
          file = null;
          this.$refs.file.value = "";
          $("#profileIamge").modal("hide");
        })
        .catch(async (error) => {
          await this.InitializeAlert([
            this.$t("profileIamge.titelInHeader"),
            error.response.data.image,
            "alert-danger",
          ]);
          theRoot.$emit("sendAlert");
        });
    },
    uplodeImage() {
      this.image = URL.createObjectURL(this.$refs.file.files[0]);
    },
  },
  computed: {
    ...mapGetters({
      profile: "auth/profile",
    }),
    setImage: function () {
      this.image =
        process.env.MIX_APP_URL +
        "/storage/" +
        this.profile.data.personal.image;
    },
  },
};
</script>

<i18n>
{
  "en":{
    "profileIamge":{
      "titelInHeader":"Profile Image",
      "btnClose":"Close",
      "btnUpdate":"Edit",
      "chooseImage":"Choose Image for Profile",
      "UplodeIamgeBtn":"Uplode Iamge"
    }
  },
  "ar":{
      "profileIamge":{
      "titelInHeader":" صورة الملف الشخصي",
      "btnClose":"إغلاق",
      "btnUpdate":"تعديل",
      "chooseImage":"اختار صورة الملف الشخصي",
      "UplodeIamgeBtn":"تحميل الصورة"
    }
  }
}
</i18n>
