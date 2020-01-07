<template>
 <div>
    <div class="form-group">
       <router-link to="/" class="btn btn-default">Back</router-link>
    </div>
 
     <div class="panel panel-default">
        <div class="panel-heading">Create new Contragent</div>
        <div class="panel-body">
           <form v-on:submit="saveForm()">
             <div class="row">
                <div class="col-xs-12 form-group">
                  <label class="control-label">Contragent name</label>
                  <input type="text" v-model="Contragent.name" class="form-control">
                </div>
              </div>
             <div class="row">
                 <div class="col-xs-12 form-group">
                    <label class="control-label">Contragent address</label>
                    <input type="text" v-model="Contragent.address" class="form-control">
                  </div>
              </div>
              <div class="row">
                 <div class="col-xs-12 form-group">
                    <label class="control-label">Contragent website</label>
                    <input type="text" v-model="Contragent.website" class="form-control">
                 </div>
              </div>
              <div class="row">
                 <div class="col-xs-12 form-group">
                    <label class="control-label">Contragent email</label>
                    <input type="text" v-model="Contragent.email" class="form-control">
                 </div>
              </div>
              <div class="row">
                 <div class="col-xs-12 form-group">
                    <button class="btn btn-success">Create</button>
                 </div>
              </div>
           </form>
       </div>
     </div>
 </div>
</template>
 
<script>
 export default {
 data: function () {
    return {
      Contragent: {
      name: '',
      address: '',
      website: '',
      email: '',
    }
 }
 },
 methods: {
   saveForm() {
      event.preventDefault();
      var app = this;
      var newContragent = app.Contragent;
      axios.post('/api/v1/Contragents', newContragent)
          .then(function (resp) {
              app.$router.push({path: '/'});
           })
           .catch(function (resp) {
               console.log(resp);
               alert("Не удалось создать компанию");
            });
        }
     }
 }
</script>