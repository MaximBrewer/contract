<template>
  <div class="d-md-flex">
    <div class="mr-auto mb-3 d-md-flex">
      <div class="dropdown mr-1">
        <button
          class="btn btn-secondary dropdown-toggle"
          type="button"
          id="dropdownMenuButton"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          {{ __("создать шаблон моего договора") }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <router-link
            class="dropdown-item"
            v-for="type in contractTypes"
            :key="'type.' + type.id"
            :to="'/personal/contracts/create/' + type.id"
            >{{ type.name }}</router-link
          >
        </div>
      </div>
      <router-link class="btn btn-primary" :to="'/personal/contracts/get'">{{
        __("договор со мной")
      }}</router-link>
    </div>
    <div class="form-group mb-3">
      <router-link class="btn btn-success" :to="'/personal/contracts'">{{
        __("Предложенные мной")
      }}</router-link>
      <router-link
        class="btn btn-primary"
        :to="'/personal/contracts/reciever'"
        >{{ __("Предложенные мне") }}</router-link
      >
    </div>
  </div>
</template>
<script>
export default {
  data: function () {
    return {
      contractTypes: window.contractTypes,
    };
  },
};
</script>
