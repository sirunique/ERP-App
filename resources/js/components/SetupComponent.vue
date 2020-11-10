<template>
    <div>
         <section class="material-half-bg">
            <div class="cover"></div>
        </section>
        
    <section class="login-content" style="min-height: 0vh !important">
      <div class="logo">
        <h1>Vali</h1>
      </div>
      <div class="login-box" style="min-width:800px !important; min-height: 600px !important">
        <form class="login-form" v-on:submit.prevent="createSetup">
          <div class="text-danger" id="error_msg"></div>
          <!-- <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN Setup</h3> -->
          <div class="row">
             <div class="form-group col-md-4">
                <label class="control-label">Subscription Type</label>
                <input class="form-control" id="sub_type_id" type="text" name="sub_type_id" v-model="data.sub_type_id.value" v-on:change="onChange" placeholder="Subscription Type">
                <div class="text-danger" id="sub_type_id_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Name</label>
                <input class="form-control" id="business_name" type="text" name="business_name" v-model="data.business_name.value" v-on:change="onChange" placeholder="Business Name">
                <div class="text-danger" id="business_name_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Email</label>
                <input class="form-control" id="business_email" type="text" name="business_email" v-model="data.business_email.value" v-on:change="onChange" placeholder="Business Email">
                <div class="text-danger" id="business_email_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Phone</label>
                <input class="form-control" id="business_phone" type="text" name="business_phone" v-model="data.business_phone.value" v-on:change="onChange" placeholder="Business Phone">
                <div class="text-danger" id="business_phone_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Address</label>
                <input class="form-control" id="business_address" type="text" name="business_address" v-model="data.business_address.value" v-on:change="onChange" placeholder="Business Address">
                <div class="text-danger" id="business_address_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Country</label>
                <input class="form-control" id="business_country" type="text" name="business_country" v-model="data.business_country.value" v-on:change="onChange" placeholder="Business Country">
                <div class="text-danger" id="business_country_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Timezone</label>
                <input class="form-control" id="business_timezone" type="text" name="business_timezone" v-model="data.business_timezone.value" v-on:change="onChange" placeholder="Business Timezone">
                <div class="text-danger" id="business_timezone_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Default Language</label>
                <input class="form-control" id="business_default_language" type="text" name="business_default_language" v-model="data.business_default_language.value" v-on:change="onChange" placeholder="Business Timezone">
                <div class="text-danger" id="business_default_language_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">Business Currency Symbol</label>
                <input class="form-control" id="business_currency_symbol" type="text" name="business_currency_symbol" v-model="data.business_currency_symbol.value" v-on:change="onChange" placeholder="Business Timezone">
                <div class="text-danger" id="business_currency_symbol_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Fullname</label>
                <input class="form-control" id="user_fullname" type="text" name="user_fullname" v-model="data.user_fullname.value" v-on:change="onChange" placeholder="User Fullname">
                <div class="text-danger" id="user_fullname_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Phone</label>
                <input class="form-control" id="user_phone" type="text" name="user_phone" v-model="data.user_phone.value" v-on:change="onChange" placeholder="User Phone">
                <div class="text-danger" id="user_phone_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Address</label>
                <input class="form-control" id="user_address" type="text" name="user_address" v-model="data.user_address.value" v-on:change="onChange" placeholder="User Address">
                <div class="text-danger" id="user_address_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User City</label>
                <input class="form-control" id="user_city" type="text" name="user_city" v-model="data.user_city.value" v-on:change="onChange" placeholder="User City">
                <div class="text-danger" id="user_city_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Country</label>
                <input class="form-control" id="user_country" type="text" name="user_country" v-model="data.user_country.value" v-on:change="onChange" placeholder="User Country">
                <div class="text-danger" id="user_country_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Email</label>
                <input class="form-control" id="email" type="text" name="email" v-model="data.email.value" v-on:change="onChange" placeholder="User Email">
                <div class="text-danger" id="email_error"></div>
              </div>
             <div class="form-group col-md-4">
                <label class="control-label">User Password</label>
                <input class="form-control" id="password" type="text" name="password" v-model="data.password.value" v-on:change="onChange" placeholder="User Password">
                <div class="text-danger" id="password_error"></div>
              </div>

            
          </div>
          
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Setup</button>
          </div>
        </form>
       
      </div>
    </section>
    </div>
</template>

<script>
import {mapActions, mapMutations, mapState} from 'vuex'
import router from '../routes'

export default {
      name: 'setup',
      async mounted() {
            // await this.getSubTypes()
            // await this.$store.dispatch('setup/getSubTypes');
        },
        methods: {
          ...mapMutations({
            setData: 'setup/setData',
            xsetdata: 'setup/xsetdata',
            ysetdata: 'setup/ysetdata',
            resetError: 'setup/resetError',
          }),
          ...mapActions({
            createSetup:'setup/createSetup',
            getSubTypes:'setup/getSubTypes',
          }),
          async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
          },
        },
        computed:{
          ...mapState({
           data: state => state.setup.data,
            isLoading: state => state.setup.isLoading,
            errors: state => state.setup.errors,
            subTypes: state => state.setup.subTypes
          })
        }
    }

     
</script>
