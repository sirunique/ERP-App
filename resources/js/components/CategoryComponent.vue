<template>
   <div>
        <Layout></Layout>
       <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Category Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Category Page</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="d-flex bd-highlight mb-3">
                <div class="p2 bd-highlight">
                    <button class="btn btn-primary mr-2" @click="showAddModal">Add Category</button>
                    <button class="btn btn-primary">Import Category</button>
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
                          <th>Category</th>
                          <th>IsAvailable</th>
                          <th>Action</th>
                        </tr>
                      </thead>  
                      <tbody class='table-content'>
                          <tr v-for="(category,i) in categories" :key="i" v-if="categories.length" >
                            <td>{{category.category_id}}</td>
                            <td>{{category.category_name}}</td>
                            <td>{{category.isAvailable | isAvailable}}</td>
                            <td>
                              <button class="btn btn-primary btn-sm mr-2" @click="editModal(category,i)">Edit</button>
                              <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(category,i)">Delete</button>
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
        <div class="modal fade" id="categoryModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel" >{{!editMode ? "Add" : "Edit"}} Category</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createCategory">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Category Name</label>
                      <input class="form-control" id="category_name" type="text" name="category_name" v-model="data.category_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="category_name_error"></div>
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

        <div class="modal fade" id="deleteCategoryModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel" >Delete Category</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this category 
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
      name:'category',
       components:{
           Layout,
       },
        // mounted:function() {
      async mounted() {
         await this.getCategories()
        // console.log(this.categories)
      },
      methods:{
         ...mapMutations({
           setCategories: 'category/setCategories',
           setData: 'category/setData',
           setCategory: 'category/setCategory',
           unsetCategory: 'category/unsetCategory',
           setIndex: 'category/setIndex',
           unsetIndex: 'category/unsetIndex',
           setEditMode: 'category/setEditMode',
           xsetdata: 'category/xsetdata',
           ysetdata: 'category/ysetdata',
           resetError: 'category/resetError',
         }),
         ...mapActions({
           getCategories: 'category/getCategories',
           deleteCategory: 'category/deleteCategory',
           createCategory: 'category/createCategory',
           showModal: 'category/showModal',
           hideModal: 'category/hideModal',
           showDeleteModal: 'category/showDeleteModal',
           hideDeleteModal: 'category/hideDeleteModal',
           
         }),
         async onChange(e){
           $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
         },
         async deleteModal(data,index){
           await this.setCategory(data)
           await this.setIndex(index)
           await this.showDeleteModal()
         },
         async closeDeleteModal(){
           await this.unsetCategory()
           await this.unsetIndex()
           await this.hideDeleteModal()
         },
         async confirmDelete(){
           await this.deleteCategory();
         },
         async editModal(data, index){
           await this.setEditMode(true)
            await this.setCategory(data)
            await this.setIndex(index)
            await this.xsetdata([['category_name','isAvailable'], data])
            await this.showModal()
         },
         async closeEditModal(){
           await this.unsetCategory()
           await this.unsetIndex()
           await this.setEditMode(!this.editMode)
           await this.ysetdata(['category_name'])
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
           editMode: state => state.category.editMode,
           categories: state => state.category.categories,
           category: state => state.category.category,
           isLoading: state => state.category.isLoading,
           data: state => state.category.data,
           errors: state => state.category.errors,
         }),
         },
     async created(){
        // await this.$store.dispatch('category/getCategories')
        // console.log(this.categories)
      }
    }
</script>
