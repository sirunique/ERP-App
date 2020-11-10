<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Mail Setting Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Mail Setting Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Mail Setting</button>
                                <button class="btn btn-primary">Import Mail Setting</button>
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
                                        <th>Mail Host</th>
                                        <th>Mail Port</th>
                                        <th>Mail Address</th>
                                        <th>Mail Password</th>
                                        <th>Mail From Name</th>
                                        <th>Mail Encryption</th>
                                        <th>Is Default</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(setting,i) in mailSettings" :key="i" v-if="mailSettings.length" >
                                        <td>{{setting.mail_setting_id}}</td>
                                        <td>{{setting.mail_host}}</td>
                                        <td>{{setting.mail_port}}</td>
                                        <td>{{setting.mail_address}}</td>
                                        <td>{{setting.mail_password}}</td>
                                        <td>{{setting.mail_from_name}}</td>
                                        <td>{{setting.mail_encryption}}</td>
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
        <div class="modal fade" id="mailSettingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="mailSettingModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="mailSettingModalLabel" >{{!editMode ? "Add" : "Edit"}} Mail Setting</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createMailSetting">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Mail Host</label>
                      <input class="form-control" id="mail_host" type="text" name="mail_host" v-model="data.mail_host.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_host_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Mail Port</label>
                      <input class="form-control" id="mail_port" type="text" name="mail_port" v-model="data.mail_port.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_port_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Mail Address</label>
                      <input class="form-control" id="mail_address" type="text" name="mail_address" v-model="data.mail_address.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_address_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Mail Password</label>
                      <input class="form-control" id="mail_password" type="text" name="mail_password" v-model="data.mail_password.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_password_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Mail From Name</label>
                      <input class="form-control" id="mail_from_name" type="text" name="mail_from_name" v-model="data.mail_from_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_from_name_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Mail Encryption</label>
                      <input class="form-control" id="mail_encryption" type="text" name="mail_encryption" v-model="data.mail_encryption.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="mail_encryption_error"></div>
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

         <div class="modal fade" id="deleteMailSettingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteMailSettingModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteMailSettingModalLabel" >Delete Mail Setting</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this mail setting 
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
    name:'mailSetting',
    components:{
        Layout
    },
    async mounted() {
        await this.getMailSettings()
    },
    methods:{
        ...mapMutations({
            setMailSettings: 'mailSetting/setMailSettings',
            setMailSetting: 'mailSetting/setMailSetting',
            unsetMailSetting: 'mailSetting/unsetMailSetting',
            setIndex: 'mailSetting/setIndex',
            unsetIndex: 'mailSetting/unsetIndex',
            setEditMode: 'mailSetting/setEditMode',
            setData: 'mailSetting/setData',
            xsetdata: 'mailSetting/xsetdata',
            ysetdata: 'mailSetting/ysetdata',
            resetError: 'mailSetting/resetError',
        }),
        ...mapActions({
            getMailSettings:'mailSetting/getMailSettings',
            deleteMailSetting:'mailSetting/deleteMailSetting',
            createMailSetting:'mailSetting/createMailSetting',
            showModal:'mailSetting/showModal',
            hideModal:'mailSetting/hideModal',
            showDeleteModal:'mailSetting/showDeleteModal',
            hideDeleteModal:'mailSetting/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setMailSetting(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetMailSetting()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteMailSetting();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setMailSetting(data)
            await this.setIndex(index)
            await this.xsetdata([[
              'mail_host',
              'mail_port',
              'mail_address',
              'mail_password',
              'mail_from_name',
              'mail_encryption',
              'isDefault'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetMailSetting()
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
            editMode: state => state.mailSetting.editMode,
            mailSettings: state => state.mailSetting.mailSettings,
            mailSetting: state => state.mailSetting.mailSetting,
            isLoading: state => state.mailSetting.isLoading,
            data: state => state.mailSetting.data,
            errors: state => state.mailSetting.errors,
        })
    }
}
</script>