<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Sms Setting Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Sms Setting Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Sms Setting</button>
                                <button class="btn btn-primary">Import Sms Setting</button>
                            </div>
                            <div class="ml-auto p-2 bd-highlight">
                                <button class="btn btn-primary mr-2" >PDF</button>
                                <button class="btn btn-primary mr-2" >CSV</button>
                                <button class="btn btn-primary mr-2" >Print</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="sampleTable">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Twilio sid</th>
                                        <th>Twilio Auth Token</th>
                                        <th>Twilio Number</th>
                                        <th>Is Default</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(setting,i) in smsSettings" :key="i" v-if="smsSettings.length" >
                                        <td>{{setting.sms_setting_id}}</td>
                                        <td>{{setting.sms_twilio_account_sid}}</td>
                                        <td>{{setting.sms_twilio_auth_token}}</td>
                                        <td>{{setting.sms_twilio_number}}</td>
                                        <td>{{setting.isDefault | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(setting,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(setting,i)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         </main>

         <!-- Modal -->
        <div class="modal fade" id="smsSettingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="smsSettingModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smsSettingModalLabel" >{{!editMode ? "Add" : "Edit"}} Sms Setting</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createSmsSetting">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Twilio Account sid</label>
                      <input class="form-control" id="sms_twilio_account_sid" type="text" name="sms_twilio_account_sid" v-model="data.sms_twilio_account_sid.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="sms_twilio_account_sid_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Twilio Auth Token</label>
                      <input class="form-control" id="sms_twilio_auth_token" type="text" name="sms_twilio_auth_token" v-model="data.sms_twilio_auth_token.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="sms_twilio_auth_token_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Twilio Auth Token</label>
                      <input class="form-control" id="sms_twilio_number" type="text" name="sms_twilio_number" v-model="data.sms_twilio_number.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="sms_twilio_number_error"></div>
                    </div>
                    
                    <div class="form-group" v-if="editMode">
                      <label class="control-label">Is Available</label>
                      <select class="form-control" name="isDefault" v-model="data.isDefault.value">
                        <option value="1" >True</option>
                        <option value="0">False</option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="closeEditModal">Close</button>
                  <button type="submit" class="btn btn-success">{{!editMode ? "Create" : "Update"}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>

         <div class="modal fade" id="deleteSmsSettingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteSmsSettingModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteSmsSettingModalLabel" >Delete Sms Setting</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this sms setting 
                      <!-- {{category}} -->
                       ?</h3>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-sm"  @click="confirmDelete">Delete</button>
                  <button type="button" class="btn btn-secondary btn-sm" @click="closeDeleteModal">Close</button>
                </div>
            </div>
          </div>
        </div>




    </div>
</template>
<script>
import {mapActions,mapGetters,mapMutations,mapState} from 'vuex'
import Layout from './layouts/LayoutComponent'
import router from '../routes'
export default {
    name:'smsSetting',
    components:{
        Layout
    },
    async mounted() {
        await this.getSmsSettings()
    },
    methods:{
        ...mapMutations({
            setSmsSettings: 'smsSetting/setSmsSettings',
            setSmsSetting: 'smsSetting/setSmsSetting',
            unsetSmsSetting: 'smsSetting/unsetSmsSetting',
            setIndex: 'smsSetting/setIndex',
            unsetIndex: 'smsSetting/unsetIndex',
            setEditMode: 'smsSetting/setEditMode',
            setData: 'smsSetting/setData',
            xsetdata: 'smsSetting/xsetdata',
            ysetdata: 'smsSetting/ysetdata',
            resetError: 'smsSetting/resetError',
        }),
        ...mapActions({
            getSmsSettings:'smsSetting/getSmsSettings',
            deleteSmsSetting:'smsSetting/deleteSmsSetting',
            createSmsSetting:'smsSetting/createSmsSetting',
            showModal:'smsSetting/showModal',
            hideModal:'smsSetting/hideModal',
            showDeleteModal:'smsSetting/showDeleteModal',
            hideDeleteModal:'smsSetting/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setSmsSetting(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetSmsSetting()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteSmsSetting();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setSmsSetting(data)
            await this.setIndex(index)
            await this.xsetdata([[
              'sms_twilio_account_sid',
              'sms_twilio_auth_token',
              'sms_twilio_number',
              'isDefault'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetSmsSetting()
            await this.unsetIndex()
            await this.setEditMode(!this.editMode)
            await this.ysetdata([])
            await this.resetError()
            await this.hideModal()
        },
        async showAddModal(){
            await this.setEditMode(false)
            await this.showModal()
        }
    },
    computed:{
        ...mapState({
            editMode: state => state.smsSetting.editMode,
            smsSettings: state => state.smsSetting.smsSettings,
            smsSetting: state => state.smsSetting.smsSetting,
            isLoading: state => state.smsSetting.isLoading,
            data: state => state.smsSetting.data,
            errors: state => state.smsSetting.errors,
        })
    }
}
</script>