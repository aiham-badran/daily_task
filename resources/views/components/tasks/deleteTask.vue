

<template>
  <section class="modal-container deleteModal">
    <div
      class="modal fade"
      id="deleteModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="deleteTaskLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteTaskLabel">
              <i class="fa fa-trash"></i>
              {{$t("removeTaskModal.titelInHeader")}}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              :title="$t('removeTaskModal.btnClose')"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>{{$t("removeTaskModal.removedMessage")}}</div>
            <div
              class="softDelete"
              :class="{active:softRemoveState}"
              @change="softRemove = !softRemove"
            >
              <label
                class="col-form-label"
                for="checkboxRemove"
                :title="$t('removeTaskModal.softRemoveDscr')"
              >
                <input type="checkbox" id="checkboxRemove" ref="checkboxRemove" />
                {{$t("removeTaskModal.softRemove")}}
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              data-dismiss="modal"
              @click="removed"
            >{{ $t("removeTaskModal.btnReomve")}}</button>
            <button
              type="button"
              class="btn btn-danger"
              data-dismiss="modal"
            >{{ $t("removeTaskModal.btnClose")}}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "deleteTask",
  props: ["update"],
  data() {
    return {
      softRemove: false
    };
  },
  methods: {
    removed: function() {
      this.$emit("remove", this.$refs.checkboxRemove.checked);
      this.$refs.checkboxRemove.checked = false;
      this.softRemoveState = false;
    }
  },
  computed: {
    softRemoveState: {
      get: function() {
        return this.softRemove;
      },
      set: function(state) {
        this.softRemove = state;
      }
    }
  }
};
</script>


<i18n>
{
  "en":{
    "removeTaskModal":{
      "btnClose" : "close",
      "titelInHeader": "Reomve Task",
      "btnReomve": "Reomve",
      "removedMessage":"Would you like to delete this task from the task pane (it will be sent to the Recycle Bin except in case you want to permanently delete it here)",
      "softRemove":"Permanently Delete",
      "softRemoveDscr":"It will not be sent to the Recycle Bin and will never be able to recover"
    }
  },
  "ar":{
    "removeTaskModal":{
      "btnClose" : "إغلاق",
      "titelInHeader": "حذف المهمة",
      "btnReomve": "حذف",
      "removedMessage":" هل تريد حذف المهمة من لوحة المهام (سوف يتم إرسال المهمة إلى سلة المحذوفات الا إذا كنت ترغب بحذفها بشكل دائم",
      "softRemove":"حذف نهائي",
      "softRemoveDscr":"سوف لن ترسل إلى سلة المحذوفات و لكن يتم حذفها بشكل نهائي"
    }
  }
}
</i18n>
