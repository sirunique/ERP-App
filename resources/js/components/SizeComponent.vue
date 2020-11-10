<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Size Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Size Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Size</button>
                                <button class="btn btn-primary">Import Size</button>
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
                                        <th>Size</th>
                                        <th>Short Name</th>
                                        <th>Is Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(size,i) in sizes" :key="i" v-if="sizes.length" >
                                        <td>{{size.size_id}}</td>
                                        <td>{{size.size_name}}</td>
                                        <td>{{size.size_short_name}}</td>
                                        <td>{{size.isAvailable | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(size,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(size,i)">Delete</button>
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
        <div class="modal fade" id="sizeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="sizeModalLabel" >{{!editMode ? "Add" : "Edit"}} Size</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createSize">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Size Name</label>
                      <input class="form-control" id="size_name" type="text" name="size_name" v-model="data.size_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="size_name_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Size Short Name</label>
                      <input class="form-control" id="size_short_name" type="text" name="size_short_name" v-model="data.size_short_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="size_short_name_error"></div>
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

         <div class="modal fade" id="deleteSizeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteSizeModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteSizeModalLabel" >Delete Size</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this size 
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
    name:'size',
    components:{
        Layout
    },
    async mounted() {
        await this.getSizes()
    },
    methods:{
        ...mapMutations({
            setSizes: 'size/setSizes',
            setSize: 'size/setSize',
            unsetSize: 'size/unsetSize',
            setIndex: 'size/setIndex',
            unsetIndex: 'size/unsetIndex',
            setEditMode: 'size/setEditMode',
            setData: 'size/setData',
            xsetdata: 'size/xsetdata',
            ysetdata: 'size/ysetdata',
            resetError: 'size/resetError',
        }),
        ...mapActions({
            getSizes:'size/getSizes',
            deleteSize:'size/deleteSize',
            createSize:'size/createSize',
            showModal:'size/showModal',
            hideModal:'size/hideModal',
            showDeleteModal:'size/showDeleteModal',
            hideDeleteModal:'size/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setSize(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetSize()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteSize();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setSize(data)
            await this.setIndex(index)
            await this.xsetdata([['size_name', 'size_short_name','isAvailable'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetSize()
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
            editMode: state => state.size.editMode,
            sizes: state => state.size.sizes,
            size: state => state.size.size,
            isLoading: state => state.size.isLoading,
            data: state => state.size.data,
            errors: state => state.size.errors,
        })
    }
}
</script>