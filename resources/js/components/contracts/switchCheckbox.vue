<template>
  <div
    class="checkbox-toggle"
    role="checkbox"
    @click.stop="toggle"
    tabindex="0"
    :aria-checked="toggled"
    style="width: auto"
  >
    <div class="checkbox-slide" :class="classes">
      <div class="checkbox-switch" :class="classes"></div>
    </div>
  </div>
</template>
<script>
export default {
  watch: {
    value: function (val) {
      this.toggled = !!val;
    },
  },
  computed: {
    classes() {
      return {
        checked: this.toggled,
        unchecked: !this.toggled,
        disabled: this.disabled,
      };
    },
  },
  data() {
    return {
      toggled: !!this.value,
    };
  },
  methods: {
    toggle(e) {
      if (this.disabled || e.keyCode === 9) {
        // not if disabled or tab is pressed
        e.stop();
      }
      this.toggled = !this.toggled;
      this.$emit("input", this.toggled * 1);
    },
  },
  props: {
    value: {
      type: Number,
      default: 0,
    },
  },
};
</script>
