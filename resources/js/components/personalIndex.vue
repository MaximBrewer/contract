<template>
  <section>
    <h1>{{ _('Cabinet') }}</h1>
  </section>
</template>
<script>
export default {
  data() {
    return {};
  },
  methods: {
    listenForBroadcast(survey_id) {
      Echo.join("survey." + survey_id)
        .here(users => {
          this.users_viewing = users;
          this.$forceUpdate();
        })
        .joining(user => {
          if (this.checkIfUserAlreadyViewingSurvey(user)) {
            this.users_viewing.push(user);
            this.$forceUpdate();
          }
        })
        .leaving(user => {
          this.removeViewingUser(user);
          this.$forceUpdate();
        });
    }
  },
  mounted() {
    
  }
};
</script>