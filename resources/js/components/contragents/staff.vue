<template>
  <section id="staff" class="container">
    <div class="table-responsive my-3" id="users" v-if="users.length">
      <div class="h2 text-center">Сотрудники</div>
      <table class="table table-bordered">
        <thead>
          <tr class="text-center">
            <th class="align-middle">#</th>
            <th class="align-middle">{{ __("Почта") }}</th>
            <th class="align-middle">{{ __("Имя") }}</th>
            <th class="align-middle">{{ __("Разрешено участвовать") }}</th>
            <th class="align-middle">{{ __("Администратор") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in users" :key="index">
            <td class="text-center">{{ user.id }}</td>
            <td class="text-center">{{ user.email }}</td>
            <td class="text-center">{{ user.name }}</td>
            <td class="text-center">
              <switch-checkbox-on :value="true"></switch-checkbox-on>
            </td>
            <td class="text-center">
              <switch-checkbox-on :value="true"></switch-checkbox-on>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
import switchCheckboxOn from "./switchCheckboxOn";
export default {
  components: {
    switchCheckboxOn: switchCheckboxOn,
  },
  data: function () {
    return { users: [] };
  },
  created: function () {
    var app = this;
    axios
      .get("/web/v1/staff")
      .then(function (res) {
        app.users = res.data;
      })
      .catch(function (err) {
        app.errors = {};
        loader.hide();
      });
  },
};
</script>