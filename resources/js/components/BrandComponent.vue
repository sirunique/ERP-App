<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Brand Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Brand Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Brand</button>
                                <button class="btn btn-primary">Import Brand</button>
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
                                        <th>Brand</th>
                                        <th>Is Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(brand,i) in brands" :key="i" v-if="brands.length" >
                                        <td>{{brand.brand_id}}</td>
                                        <td>{{brand.brand_name}}</td>
                                        <td>{{brand.isAvailable | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(brand,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(brand,i)">Delete</button>
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
        <div class="modal fade" id="brandModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel" >{{!editMode ? "Add" : "Edit"}} Brand</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createBrand">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="form-group">
                      <label class="control-label">Brand Name</label>
                      <input class="form-control" id="brand_name" type="text" name="brand_name" v-model="data.brand_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="brand_name_error"></div>
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

         <div class="modal fade" id="deleteBrandModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteBrandModalLabel" >Delete Brand</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this brand 
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
    name:'brand',
    components:{
        Layout
    },
    async mounted() {
        await this.getBrands()
    },
    methods:{
        ...mapMutations({
            setBrands: 'brand/setBrands',
            setBrand: 'brand/setBrand',
            unsetBrand: 'brand/unsetBrand',
            setIndex: 'brand/setIndex',
            unsetIndex: 'brand/unsetIndex',
            setEditMode: 'brand/setEditMode',
            setData: 'brand/setData',
            xsetdata: 'brand/xsetdata',
            ysetdata: 'brand/ysetdata',
            resetError: 'brand/resetError',
        }),
        ...mapActions({
            getBrands:'brand/getBrands',
            deleteBrand:'brand/deleteBrand',
            createBrand:'brand/createBrand',
            showModal:'brand/showModal',
            hideModal:'brand/hideModal',
            showDeleteModal:'brand/showDeleteModal',
            hideDeleteModal:'brand/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setBrand(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetBrand()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteBrand();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setBrand(data)
            await this.setIndex(index)
            await this.xsetdata([['brand_name','isAvailable'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetBrand()
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
            editMode: state => state.brand.editMode,
            brands: state => state.brand.brands,
            brand: state => state.brand.brand,
            isLoading: state => state.brand.isLoading,
            data: state => state.brand.data,
            errors: state => state.brand.errors,
        })
    }
}
</script>