<template>
    <div>
        <Layout></Layout>
         <main class="app-content">
       <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Supplier Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Supplier Page</a></li>
        </ul>
      </div>
      <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                       <div class="d-flex bd-highlight mb-3">
                            <div class="p2 bd-highlight">
                                <button class="btn btn-primary mr-2" @click="showAddModal">Add Supplier</button>
                                <button class="btn btn-primary">Import Supplier</button>
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
                                        <th>Supplier Company</th>
                                        <th>Supplier Vat No</th>
                                        <th>Supplier Email</th>
                                        <th>Supplier Phone No</th>
                                        <th>Supplier Address</th>
                                        <th>Supplier Country</th>
                                        <th>Supplier City</th>
                                        <th>Supplier State</th>
                                        <th>Supplier Postal Code</th>
                                        <th>Is Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody class='table-content'>
                                    <tr v-for="(supplier,i) in suppliers" :key="i" v-if="suppliers.length" >
                                        <td>{{supplier.supplier_id}}</td>
                                        <td>{{supplier.supplier_company_name}}</td>
                                        <td>{{supplier.supplier_vat_no}}</td>
                                        <td>{{supplier.supplier_email}}</td>
                                        <td>{{supplier.supplier_phone_no}}</td>
                                        <td>{{supplier.supplier_address}}</td>
                                        <td>{{supplier.supplier_country}}</td>
                                        <td>{{supplier.supplier_city}}</td>
                                        <td>{{supplier.supplier_state}}</td>
                                        <td>{{supplier.supplier_postal_code}}</td>
                                        <td>{{supplier.isAvailable | isAvailable}}</td>
                                        <td>
                                        <button class="btn btn-primary btn-sm mr-2" @click="editModal(supplier,i)">Edit</button>
                                        <button class="btn btn-danger btn-sm mr-2" @click="deleteModal(supplier,i)">Delete</button>
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
        <div class="modal fade bd-example-modal-lg" id="supplierModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel" >{{!editMode ? "Add" : "Edit"}} Supplier</h5>
                <button type="button" class="close" @click="closeEditModal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
              <form v-on:submit.prevent="createSupplier">
                <div class="modal-body">
                    <div class="text-danger" id="error_msg"></div>
                    <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Company</label>
                      <input class="form-control" id="supplier_company_name" type="text" name="supplier_company_name" v-model="data.supplier_company_name.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_company_name_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Vat No</label>
                      <input class="form-control" id="supplier_vat_no" type="text" name="supplier_vat_no" v-model="data.supplier_vat_no.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_vat_no_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Email</label>
                      <input class="form-control" id="supplier_email" type="text" name="supplier_email" v-model="data.supplier_email.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_email_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Phone</label>
                      <input class="form-control" id="supplier_phone_no" type="text" name="supplier_phone_no" v-model="data.supplier_phone_no.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_phone_no_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Address</label>
                      <input class="form-control" id="supplier_address" type="text" name="supplier_address" v-model="data.supplier_address.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_address_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Country</label>
                      <input class="form-control" id="supplier_country" type="text" name="supplier_country" v-model="data.supplier_country.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_country_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier City</label>
                      <input class="form-control" id="supplier_city" type="text" name="supplier_city" v-model="data.supplier_city.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_city_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier State</label>
                      <input class="form-control" id="supplier_state" type="text" name="supplier_state" v-model="data.supplier_state.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_state_error"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Supplier Postal Code</label>
                      <input class="form-control" id="supplier_postal_code" type="text" name="supplier_postal_code" v-model="data.supplier_postal_code.value" v-on:change="onChange" autofocus>
                      <div class="text-danger" id="supplier_postal_code_error"></div>
                    </div>
                    <div class="form-group col-md-6" v-if="editMode">
                      <label class="control-label">Is Available</label>
                      <select class="form-control" name="isAvailable" v-model="data.isAvailable.value">
                        <option value="1" >True</option>
                        <option value="0">False</option>
                      </select>
                    </div>
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

         <div class="modal fade" id="deleteSupplierModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteSupplierModalLabel" >Delete Supplier</h5>
                <button type="button" class="close"  @click="closeDeleteModal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> 
                <div class="modal-body">
                    <h3>Are sure to delete this supplier 
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
    name:'supplier',
    components:{
        Layout
    },
    async mounted() {
        await this.getSuppliers()
    },
    methods:{
        ...mapMutations({
            setSuppliers: 'supplier/setSuppliers',
            setSupplier: 'supplier/setSupplier',
            unsetSupplier: 'supplier/unsetSupplier',
            setIndex: 'supplier/setIndex',
            unsetIndex: 'supplier/unsetIndex',
            setEditMode: 'supplier/setEditMode',
            setData: 'supplier/setData',
            xsetdata: 'supplier/xsetdata',
            ysetdata: 'supplier/ysetdata',
            resetError: 'supplier/resetError',
        }),
        ...mapActions({
            getSuppliers:'supplier/getSuppliers',
            deleteSupplier:'supplier/deleteSupplier',
            createSupplier:'supplier/createSupplier',
            showModal:'supplier/showModal',
            hideModal:'supplier/hideModal',
            showDeleteModal:'supplier/showDeleteModal',
            hideDeleteModal:'supplier/hideDeleteModal',
        }),
        async onChange(e){
            $('#'+e.target.id+'_error').text('')
            $('#'+e.target.id  ).removeClass('is-invalid')
            await this.setData(e)
        },
        async deleteModal(data, index){
            await this.setSupplier(data)
            await this.setIndex(index)
            await this.showDeleteModal()
        },
        async closeDeleteModal(){
            await this.unsetSupplier()
            await this.unsetIndex()
            await this.hideDeleteModal()
        },
        async confirmDelete(){
            await this.deleteSupplier();
        },
        async editModal(data, index){
            await this.setEditMode(true)
            await this.setSupplier(data)
            await this.setIndex(index)
            await this.xsetdata([[
              'supplier_company_name', 'supplier_vat_no',
              'supplier_email', 'supplier_phone_no',
              'supplier_address', 'supplier_country',
              'supplier_city', 'supplier_state',
              'supplier_postal_code', 'isAvailable'], data])
            await this.showModal()
        },
        async closeEditModal(){
            await this.unsetSupplier()
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
            editMode: state => state.supplier.editMode,
            suppliers: state => state.supplier.suppliers,
            supplier: state => state.supplier.supplier,
            isLoading: state => state.supplier.isLoading,
            data: state => state.supplier.data,
            errors: state => state.supplier.errors,
        })
    }
}
</script>