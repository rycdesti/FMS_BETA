<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-card
        header-tag="header"
        footer-tag="footer"
      >
        <div slot="header">
          <i class="fa fa-align-justify" /> <strong>Bootstrap Tooltips</strong> <small><code>v-b-tooltip</code> directive</small>
          <div class="card-actions">
            <a
              href="https://bootstrap-vue.js.org/docs/components/tooltip"
              target="_blank"
            >
              <small class="text-muted">
                docs
              </small>
            </a>
          </div>
        </div>
        <b-row>
          <b-col cols="6">
            <div class="text-center my-3">
              <b-btn
                v-b-tooltip.hover
                title="I am a tooltip!"
              >
                Hover Me
              </b-btn>
            </div>
          </b-col>
          <b-col cols="6">
            <div class="text-center my-3">
              <b-btn
                id="tooltipButton-2"
                variant="primary"
              >
                Button
              </b-btn>
              <b-tooltip
                show
                target="tooltipButton-2"
              >
                I start open
              </b-tooltip>
            </div>
          </b-col>
        </b-row>
      </b-card>
      <b-card
        header-tag="header"
        footer-tag="footer"
      >
        <div slot="header">
          <i class="fa fa-align-justify" /> <strong>Tooltips</strong> <small><code>b-tooltip</code> component</small>
        </div>
        <b-row>
          <b-col
            md="6"
            class="py-4 text-center"
          >
            <b-btn
              id="exButton1"
              variant="outline-success"
            >
              Live chat
            </b-btn>
          </b-col>
          <b-col
            md="6"
            class="py-4 text-center"
          >
            <b-btn
              id="exButton2"
              variant="outline-success"
            >
              Html chat
            </b-btn>
          </b-col>
        </b-row>

        <!-- Tooltip title specified via prop title -->
        <b-tooltip
          target="exButton1"
          title="Online!"
        />

        <!-- HTML title specified via default slot -->
        <b-tooltip
          target="exButton2"
          placement="bottom"
        >
          Hello <strong>World!</strong>
        </b-tooltip>
      </b-card>
      <b-card
        header-tag="header"
        footer-tag="footer"
      >
        <div slot="header">
          <i class="fa fa-align-justify" /> <strong>Tooltips</strong> <small><code>show</code> prop</small>
        </div>
        <div class="text-center">
          <b-btn
            id="tooltipButton-1"
            variant="primary"
          >
            I have a tooltip
          </b-btn>
          <br><br>
          <b-btn @click="show = !show">
            Toggle Tooltip
          </b-btn>

          <b-tooltip
            :show.sync="show"
            target="tooltipButton-1"
            placement="top"
          >
            Hello <strong>World!</strong>
          </b-tooltip>
        </div>
      </b-card>
      <b-card
        header-tag="header"
        footer-tag="footer"
      >
        <div slot="header">
          <i class="fa fa-align-justify" /> <strong>Tooltips</strong> <small><code>open close</code> events</small>
        </div>
        <div class="d-flex flex-column text-md-center">
          <div class="p-2">
            <b-btn
              id="tooltipButton-showEvent"
              variant="primary"
            >
              I have a tooltip
            </b-btn>
          </div>
          <div class="p-2">
            <b-btn
              class="px-1"
              @click="onOpen"
            >
              Open
            </b-btn>
            <b-btn
              class="px-1"
              @click="onClose"
            >
              Close
            </b-btn>
          </div>

          <b-tooltip
            ref="tooltip"
            target="tooltipButton-showEvent"
          >
            Hello <strong>World!</strong>
          </b-tooltip>
        </div>
      </b-card>
      <b-card
        header-tag="header"
        footer-tag="footer"
      >
        <div slot="header">
          <i class="fa fa-align-justify" /> <strong>Tooltips</strong> <small><code>enable disable</code> events</small>
        </div>
        <div class="d-flex flex-column text-md-center">
          <div class="p-2">
            <b-btn
              id="tooltipButton-disable"
              variant="primary"
            >
              I have a tooltip
            </b-btn>
          </div>
          <div class="p-2">
            <b-btn @click="disabled = !disabled">
              {{ disabled ? 'Active' : 'Inactive' }} Tooltip by prop
            </b-btn>

            <b-btn @click="disableByRef">
              {{ disabled ? 'Active' : 'Inactive' }} Tooltip by $ref event
            </b-btn>

            <b-tooltip
              ref="tooltip2"
              :disabled.sync="disabled"
              target="tooltipButton-disable"
            >
              Hello <strong>World!</strong>
            </b-tooltip>
          </div>
        </div>
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Tooltips',
  data () {
    return {
      show    : true,
      disabled: false,
    }
  },
  methods: {
    onOpen () {
      this.$refs.tooltip.$emit('open')
    },
    onClose () {
      this.$refs.tooltip.$emit('close')
    },
    disableByRef () {
      if (this.disabled)
        this.$refs.tooltip2.$emit('enable')
      else
        this.$refs.tooltip2.$emit('disable')
    },
  },
}
</script>

<style scoped>

</style>
