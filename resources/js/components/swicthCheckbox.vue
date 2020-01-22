<template>
  <div
    class="checkbox-toggle"
    role="checkbox"
    @keydown="toggle"
    @click.stop="toggle"
    tabindex="0"
    :aria-checked="toggled"
  >
    <div class="checkbox-slide" :class="classes">
      <div class="checkbox-switch" :class="classes"></div>
    </div>
  </div>
</template>
<script>
export default {
  computed: {
    classes() {
      return {
        checked: this.toggled,
        unchecked: !this.toggled,
        disabled: this.disabled
      };
    }
  },
  data() {
    return {
      toggled: !!this.value
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
    }
  },
  props: {
    disabled: {
      type: Boolean,
      default: false
    },
    value: {
      type: Number,
      default: 0
    }
  }
};
</script>
