<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Type Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Type Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Type</button>
                                <button class="btn btn-primary">Import Type</button>
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
                                        <th>Type</th>
                                        <th>Is Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(type,i) in types" :key="i" v-if="types.length" >
                                        <td>{{type.type_id}}</td>
                                        <td>{{type.type_name}}</td>
                                        <td>{{type.isAvailable | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(type,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(type,i)">Delete</button>
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
        <div class="modal fade" id="typeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="typeModalLabel" >{{!editMode ? "Add" : "Edit"}} Type</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createType">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Type Name</label>
                      <input class="form-control" id="type_name" type="text" name="type_name" v-model="data.type_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="type_name_error"></div>
                    </div>
                    <div class="form-group" v-if="editMode">
                      <label class="control-label">Is Available</label>
                      <select class="form-control" name="isAvailable" v-model="data.isAvailable.value">
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

         <div class="modal fade" id="deleteTypeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteTypeModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteTypeModalLabel" >Delete Type</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this type 
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
    name:'type',
    components:{
        Layout
    },
    async mounted() {
        await this.getTypes()
    },
    methods:{
        ...mapMutations({
            setTypes: 'type/setTypes',
            setType: 'type/setType',
            unsetType: 'type/unsetType',
            setIndex: 'type/setIndex',
            unsetIndex: 'type/unsetIndex',
            setEditMode: 'type/setEditMode',
            setData: 'type/setData',
            xsetdata: 'type/xsetdata',
            ysetdata: 'type/ysetdata',
            resetError: 'type/resetError',
        }),
        ...mapActions({
            getTypes:'type/getTypes',
            deleteType:'type/deleteType',
            createType:'type/createType',
            showModal:'type/showModal',
            hideModal:'type/hideModal',
            showDeleteModal:'type/showDeleteModal',
            hideDeleteModal:'type/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setType(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetType()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteType();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setType(data)
            await this.setIndex(index)
            await this.xsetdata([['type_name','isAvailable'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetType()
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
            editMode: state => state.type.editMode,
            types: state => state.type.types,
            type: state => state.type.type,
            isLoading: state => state.type.isLoading,
            data: state => state.type.data,
            errors: state => state.type.errors,
        })
    }
}
</script>