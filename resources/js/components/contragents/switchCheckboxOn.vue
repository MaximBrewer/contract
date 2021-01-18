<template>
  <div
    class="checkbox-toggle"
    role="checkbox"
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
  watch: {
    value: function(val) {
      this.toggled = !!val;
    }
  },
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
    }
  },
  props: {
    value: {
      type: Boolean,
      default: 0
    }
  }
};
</script>
