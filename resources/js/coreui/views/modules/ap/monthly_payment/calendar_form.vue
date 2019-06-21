<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.print.css " rel="stylesheet"
      type="text/css" media="print"/>

<template>
  <b-modal centered id="calendar_form_modal" size="xl"
           title="Monthly Payments Calendar"
           hide-footer class="modal-primary" no-close-on-backdrop>

    <transition name="fade">
      <div v-if="show" id="load-overlay" style="padding-top: 500px;">
        <div class="h-100 w-100">
          <Circle10 class="w-100"/>
          <div class="spinner-body text-white text-center mt-3">
            <h4>Loading</h4>
          </div>
        </div>
      </div>
    </transition>

    <transition name="bounce">
      <div v-if="hasNoPayments" id="empty-overlay" class="h-100 w-100">
        <h1 class="text-secondary text-center w-100" style="margin-top: 150px">No Available Payments</h1>
      </div>
    </transition>

    <FullCalendar
      id="fullCalendar"
      style="height: 120%"
      ref="fullCalendar"
      event-text-color="white"
      event-limit="true"
      event-limit-text="more payables"
      default-view="dayGridMonth"
      :content-height="contentHeight"
      :plugins="calendarPlugins"
      :events="payments"
      :datesRender="handleDateRender"
      @eventClick="handleEventClick"
    />

  </b-modal>
</template>

<style>
  #load-overlay {
    position: fixed;
    display: block;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 2;
  }

  #empty-overlay {
    position: absolute;
    display: block;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s;
  }

  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
  {
    opacity: 0;
  }

  .bounce-enter-active {
    animation: bounce-in .3s;
  }

  .bounce-leave-active {
    animation: bounce-in 0s reverse;
  }

  @keyframes bounce-in {
    0% {
      transform: scale(0);
    }
    50% {
      transform: scale(1.5);
    }
    100% {
      transform: scale(1);
    }
  }
</style>

<style lang='scss'>
  @import '~@fullcalendar/core/main.css';
  @import '~@fullcalendar/daygrid/main.css';
</style>

<script>
  import FullCalendar from '@fullcalendar/vue'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import Circle10 from "vue-loading-spinner/src/components/Circle10";

  export default {
    name: 'MonthlyPayment',
    props: [
      'table_filter',
    ],
    components: {
      FullCalendar,
      Circle10
    },
    data() {
      return {
        calendarPlugins: [dayGridPlugin],
        renderedDates: [],
        payments: [],
        contentHeight: 0,
        show: true,
        hasNoPayments: false,
        shouldRender: false,
      }
    },
    created() {
    },
    mounted() {
      const component = this;
      this.$root.$on('calendar', function (a) {
        component.renderedDates = [];
        component.payments = [];

        let table_filter = component.table_filter;

        component.show = true;
        component.hasNoPayments = false;
        component.renderedDates.push(table_filter.date_filter);
        component.reloadCalendar(table_filter.frequency_filter, table_filter.status_filter, table_filter.date_filter);
      });

      this.$root.$on('bv::modal::hide', function() {
        console.log('Calendar closed');
      });
    },

    methods: {
      handleEventClick(arg) {
        let event = arg.event;
        console.log(event.title + " - " + moment(event.start).format('MM/DD/YYYY'));
      },

      handleDateRender(arg) {
        let table_filter = this.table_filter;
        let date = moment(arg.view.title).format('YYYY-M');

        console.log(this.renderedDates);
        console.log(date);

        if (!this.renderedDates.includes(date)) {
          console.log('From API');
          console.log('Fetching ' + arg.view.title);

          this.show = true;
          this.renderedDates.push(date);
          this.reloadCalendar(table_filter.frequency_filter, table_filter.status_filter, date);
        } else {
          console.log('From Cache');
          console.log('Date is already rendered');

          let paymentsCount = this.checkIfDateHasPayments(date);

          if (paymentsCount === 0) {
            console.log('Date has no payments');
            this.hasNoPayments = true;
          } else {
            console.log('Date has payments');
            this.hasNoPayments = false;
          }
        }

        // $('#load-overlay').show();
        // console.log(arg.view.title);
        // this.reloadCalendar(table_filter.frequency_filter, table_filter.status_filter, date);
      },

      handleEventRender(arg) {
        this.hasNoPayments = false;
        console.log('Payments rendered');
      },

      checkIfDateHasPayments(selectedDate) {
        return this.payments.filter(function (event) {
          let eventDate = moment(event.date).format('YYYY-M');
          if (eventDate === selectedDate) {
            return event;
          }
        }).length;
      },

      reloadCalendar(frequency_filter, status_filter, date_filter) {
        const component = this;

        $.ajax({
          method: 'GET',
          url: '/api/ap/monthly-payment',
          data: {
            frequency_filter: frequency_filter,
            status_filter: status_filter,
            date_filter: date_filter,
          },
          success: function (response) {
            response.forEach(function (item) {
              component.payments.push(item);
            });

            let paymentsCount = component.checkIfDateHasPayments(date_filter);
            console.log('Payments count: ' + paymentsCount);

            if (paymentsCount === 0) {
              component.hasNoPayments = true;
            } else {
              let calendarApi = component.$refs.fullCalendar.getApi();
              calendarApi.render();
              component.hasNoPayments = false;
            }

            console.log('Payments fetched');
            component.show = false;
          },
          error: function (error) {
            console.log(error);
          }
        })
      },
    },
  }
</script>
