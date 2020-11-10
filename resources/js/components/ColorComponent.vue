<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Color Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Color Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Color</button>
                                <button class="btn btn-primary">Import Color</button>
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
                                        <th>Color</th>
                                        <th>Hexcode</th>
                                        <th>Is Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(color,i) in colors" :key="i" v-if="colors.length" >
                                        <td>{{color.color_id}}</td>
                                        <td>{{color.color_name}}</td>
                                        <td>{{color.color_hexcode}}</td>
                                        <td>{{color.isAvailable | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(color,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(color,i)">Delete</button>
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
        <div class="modal fade" id="colorModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="colorModalLabel" >{{!editMode ? "Add" : "Edit"}} Color</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createColor">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Color Name</label>
                      <input class="form-control" id="color_name" type="text" name="color_name" v-model="data.color_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="color_name_error"></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Color Hexcode</label>
                      <input class="form-control" id="color_hexcode" type="text" name="color_hexcode" v-model="data.color_hexcode.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="color_hexcode_error"></div>
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

         <div class="modal fade" id="deleteColorModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteColorModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteColorModalLabel" >Delete Color</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this color 
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
    name:'color',
    components:{
        Layout
    },
    async mounted() {
        await this.getColors()
    },
    methods:{
        ...mapMutations({
            setColors: 'color/setColors',
            setColor: 'color/setColor',
            unsetColor: 'color/unsetColor',
            setIndex: 'color/setIndex',
            unsetIndex: 'color/unsetIndex',
            setEditMode: 'color/setEditMode',
            setData: 'color/setData',
            xsetdata: 'color/xsetdata',
            ysetdata: 'color/ysetdata',
            resetError: 'color/resetError',
        }),
        ...mapActions({
            getColors:'color/getColors',
            deleteColor:'color/deleteColor',
            createColor:'color/createColor',
            showModal:'color/showModal',
            hideModal:'color/hideModal',
            showDeleteModal:'color/showDeleteModal',
            hideDeleteModal:'color/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setColor(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetColor()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteColor();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setColor(data)
            await this.setIndex(index)
            await this.xsetdata([['color_name', 'color_hexcode','isAvailable'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetColor()
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
            editMode: state => state.color.editMode,
            colors: state => state.color.colors,
            color: state => state.color.color,
            isLoading: state => state.color.isLoading,
            data: state => state.color.data,
            errors: state => state.color.errors,
        })
    }
}
</script>