<template>
  <div class="calenderTable">
    <div class="dateForShow row">
      <div class="priv col-1" @click="previousMonth">
        <i class="arrow fas fa-angle-left"></i>
      </div>
      <!-- prve month arrow -->
      <!-- -- -->
      <div class="head col-9 col-md-10">
        <div class="months">
          <span @click="stateOfMonthList = !stateOfMonthList">
            {{
            $t(`months[${month - 1}]`)
            }}
          </span>
          <ul class="options list-unstyled row" :class="{ open: stateOfMonthList }">
            <li
              v-for="index in 12"
              :key="index"
              :class="{ active: index == month }"
              @click="chooseMonth(index)"
              class="col-4"
            >{{ $t(`months[${index - 1}]`) }}</li>
          </ul>
        </div>
        <span class="year">{{ yaer }}</span>
      </div>
      <!-- head: has month and yaer -->
      <!-- -- -->
      <div class="next col-1" @click="nextMonth">
        <i class="arrow fas fa-angle-right"></i>
      </div>
      <!-- next month arrow -->
      <!-- -- -->
    </div>
    <!-- dateForShow : show and chooses view in table calender -->
    <!-- -- -->
    <div class="calenderHeader">
      <div class="row">
        <div
          v-for="index in 7"
          :key="index"
          class="col-2 headerDayCell"
        >{{ $t(`week[${index - 1}]`) }}</div>
      </div>
    </div>
    <!-- calenderHeader class -->
    <!-- -- -->
    <div class="calenderBody">
      <div class="row">
        <template>
          <div class="col-2 dayCell" v-for="day in firstDayInMonth" :key="'day' + day">-</div>
        </template>
        <!-- empty cell in start -->
        <!-- -- -->
        <template>
          <div
            class="col-2 dayCell"
            v-for="(day, index) in dayInMonth"
            :key="index"
            :class="{ dayCellActive: checkToday(day) }"
            @click="chooseDay($event, day), popover($event)"
            @dblclick="poneTaskBoard(day)"
          >
            <div class="date">{{ day }}</div>
            <!-- date class  -->

            <div class="dayInfo" v-if="tasksAnalysis">
              <div class="tasks" title="tasks">
                <i class="far fa-clipboard" v-if="tasksAnalysis[day]">
                  <span>{{tasksAnalysis[day]}}</span>
                </i>
              </div>
              <!-- tasks class -->
              <!-- -- -->
            </div>
            <!-- day info class -->
            <!-- -- -->
          </div>
          <!-- col-2 dayCell calsses -->
          <!-- -- -->
        </template>
        <!-- cell the currently month   -->
        <!-- -- -->
        <template>
          <div class="col-2 dayCell" v-for="end in numberOfCellInLast" :key="'end' + end">-</div>
        </template>
        <!-- empty cell in end -->
        <!-- -- -->
      </div>
      <!-- row class -->
      <!-- -- -->
    </div>
    <!-- calenderBody class -->
    <!-- -- -->
  </div>
  <!-- calenderTable calss -->
  <!-- -- -->
</template>

<script>
import { mapMutations } from "vuex";
export default {
  name: "calender-table",
  /**
   * add event to document
   * set currently month and year for calender table
   *
   */
  created: function () {
    // add event in documcnt
    document.addEventListener("click", this.clickOnDocument);
    let currentDate = new Date();
    this.month = currentDate.getMonth() + 1;
    this.yaer = currentDate.getFullYear();
    let goDate = this.month + "-" + currentDate.getDate() + "-" + this.yaer;
    this.currentDate = goDate;
    this.setDateForBoard(goDate);
    this.TaskCountInMonth();
  },
  data() {
    return {
      yaer: null,
      month: null,
      currentDate: null,
      stateOfMonthList: false,
      tasksAnalysisDay: undefined,
      tasksAnalysis: 0,
    };
  },

  methods: {
    ...mapMutations(["setDateForBoard"]),
    /**
     * Delete the specified class from the same class group of items
     * @param {String} selector :  The common class between all the elements from which we want to delete a specific class
     * @param {String} removeCalss : The class to be deleted
     * @void
     */
    removeAcitve: function (selector, removeCalss) {
      let dayInfoClass = document.querySelectorAll(selector);
      dayInfoClass.forEach((dayInfo) => dayInfo.classList.remove(removeCalss));
    },
    clickOnDocument: function (event) {
      if (!event.target.closest("div.dayCell")) {
        this.removeAcitve(".dayInfo", "active");
      }
      //   let head = document.querySelector(".head");
      //   if (!(head == e.target || head.contains(e.target))) {
      //     this.opneChooseMonth = false;
      //   }
      //   if (!e.target.classList.contains("dayCell")) this.popover(e);
    },
    /**
     * checkToday : check day in table if the day is current day return trure
     */
    checkToday(day) {
      if (day == new Date().getDate()) return true;
    },
    /**
     * popover: Display tasks statistics in a popover box on small screens
     */
    popover: function (event) {
      let info = event.target.children[1];
      if (info != undefined) {
        if (info.classList.contains("active")) {
          info.classList.remove("active");
        } else {
          this.removeAcitve(".dayInfo", "active");
          info.classList.add("active");
        }
      } else {
        this.removeAcitve(".dayInfo", "active");
      }
    },
    /**
     * chooseDay: active cell chooes in table
     */
    chooseDay: function (event, day) {
      // if target has class dayCell or the target is chlild in it
      let targets = event.target.closest("div.dayCell");

      if (!targets.classList.contains("dayCellActive")) {
        this.removeAcitve(".dayCell", "dayCellActive");
        targets.classList.add("dayCellActive");
      }

      let goDate = this.month + "-" + day + "-" + this.yaer;

      if (this.checkDayNotOld(goDate)) this.setDateForBoard(goDate);
      else this.setDateForBoard(undefined);
    },
    chooseMonth: function (index) {
      this.removeAcitve(".dayCell", "dayCellActive");
      this.month = index;
      this.stateOfMonthList = false;
      this.setDateForBoard(undefined);
      this.TaskCountInMonth();
    },
    nextMonth: function () {
      this.removeAcitve(".dayCell", "dayCellActive");
      if (this.month < 12) this.month++;
      else {
        this.month = 1;
        this.yaer += 1;
      }
      this.setDateForBoard(undefined);
      this.TaskCountInMonth();
    },
    previousMonth: function () {
      this.removeAcitve(".dayCell", "dayCellActive");
      if (this.month > 1) this.month--;
      else {
        this.month = 12;
        this.yaer -= 1;
      }
      this.setDateForBoard(undefined);
      this.TaskCountInMonth();
    },
    poneTaskBoard: function (day) {
      let date = this.month + "-" + day + "-" + this.yaer;
      if (checkDayNotOld(date))
        this.$router.push({ name: "taskBoard", params: { taskBoard: date } });
    },
    TaskCountInMonth() {
      let countDay = new Date(this.yaer, this.month, 0).getDate(),
        data = {
          startDate: `${this.yaer}-${this.month}-1`,
          endDate: `${this.yaer}-${this.month}-${countDay}`,
        };
      axios
        .get("task/Tasks-count", { params: data })
        .then((response) => response.data)
        .then((data) => (this.tasksAnalysis = data))
        .catch((error) => console.log(error.response));
    },
    checkDayNotOld: function (date) {
      let oldTasks = false;
      let theDate = new Date(date);
      if (this.tasksAnalysis) {
        if (this.tasksAnalysis[theDate.getDate()]) oldTasks = true;
      }
      if (
        theDate.getTime() >= new Date(this.currentDate).getTime() ||
        oldTasks
      ) {
        console.log(true);
        return true;
      } else return false;
    },
  },
  computed: {
    /**
     * dayInMonth - get : return count of day in month
     */
    dayInMonth: {
      get: function () {
        return new Date(this.yaer, this.month, 0).getDate();
      },
      set: function (m) {
        this.month = m;
      },
    },
    firstDayInMonth: {
      get: function () {
        return new Date(this.yaer, this.month - 1, 1).getDay();
      },
    },
    numberOfCellInLast: function () {
      var last = 35 - this.firstDayInMonth - this.dayInMonth;
      if (last > 0) return last;
      else return 7 - Math.abs(last);
    },
  },
};
</script>

<i18n>
{
  "en":{
    "months":["January","February","March","April","May","June","July","August","September","October","November","December"],
    "week": ["sun", "mon", "tue", "wed", "thu", "fri", "sat"],
    "Analysis":{
      "tasks": "Tasks",
      "completed":"Completed",
      "uncomplete":"Uncompleted"
    }
  },
  "ar":{
    "months":["يناير" , "فبراير" , "مارس" , "أبريل" , "مايو" , "يونيو" , "يوليو" , "أغسطس" , "سبتمبر" , "أكتوبر" , "نوفمبر" , "ديسمبر"],
    "week": ["الاحد"," الاثنين"," الثلاثاء"," الاربعاء" ,"الخميس", "الجمعة", "السبت"],
    "Analysis":{
      "tasks": "المهام",
      "completed":"المنجز",
      "uncomplete":"الغير منجز"
    }
  }
}
</i18n>

/* */
