<template>
 <div>
  <div class="form-group">
   <router-link :to="{name: 'createContragent'}" class="btn btn-success">Create new Contragent</router-link>
  </div>
 
  <div class="panel panel-default">
   <div class="panel-heading">Contragents list</div>
   <div class="panel-body">
    <table class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Name</th>
       <th>Address</th>
       <th>Website</th>
       <th>Email</th>
       <th width="100">&nbsp;</th>
      </tr>
     </thead>
     <tbody>
     <tr v-for="Contragent, index in Contragents">
       <td>{{ Contragent.name }}</td>
       <td>{{ Contragent.address }}</td>
       <td>{{ Contragent.website }}</td>
       <td>{{ Contragent.email }}</td>
     <td>
     <router-link :to="{name: 'editContragent', params: {id: Contragent.id}}" class="btn btn-xs btn-default">
      Edit
    </router-link>
     <a href="#"
       class="btn btn-xs btn-danger"
       v-on:click="deleteEntry(Contragent.id, index)">
       Delete
      </a>
     </td>
     </tr>
    </tbody>
   </table>
 </div>
 </div>
 </div>
</template>
 
<script>
 export default {
 data: function () {
    return {
      Contragents: []
    }
    },
 mounted() {
    var app = this;
    axios.get('/api/v1/Contragents')
    .then(function (resp) {
    app.Contragents = resp.data;
 })
 .catch(function (resp) {
    console.log(resp);
    alert("Не удалось загрузить компании");
 });
 },
 methods: {
   deleteEntry(id, index) {
   if (confirm("Вы действительно хотите удалить компанию?")) {
     var app = this;
     axios.delete('/api/v1/Contragents/' + id)
     .then(function (resp) {
     app.Contragents.splice(index, 1);
 })
 .catch(function (resp) {
    alert("Не удалось удалить компанию");
 });
 }
 }
 }
 }
</script>