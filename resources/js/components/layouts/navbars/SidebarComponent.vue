<template>
<div>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">John Doe</p>
          <p class="app-sidebar__user-name">{{ user }}</p>
          <p class="app-sidebar__user-designation">Frontend Developer</p>
        </div>
      </div>
      <ul class="app-menu">
        <li><router-link class="app-menu__item active" to="/dashboard"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></router-link></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
              <li><a class="treeview-item" href="blank-page.html"><i class="icon fa fa-circle-o"></i> Blank Page</a></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Setup</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
              <li><a class="treeview-item" href="blank-page.html"><i class="icon fa fa-circle-o"></i> Product</a></li>
              <li><router-link class="treeview-item" to="/category"><i class="icon fa fa-circle-o"></i> Category</router-link></li>
              <li><router-link class="treeview-item" to="/brand"><i class="icon fa fa-circle-o"></i> Brand</router-link></li>
              <li><router-link class="treeview-item" to="/color"><i class="icon fa fa-circle-o"></i> Color</router-link></li>
              <li><router-link class="treeview-item" to="/size"><i class="icon fa fa-circle-o"></i> Size</router-link></li>
              <li><router-link class="treeview-item" to="/type"><i class="icon fa fa-circle-o"></i> Type</router-link></li>
              <li><router-link class="treeview-item" to="/supplier"><i class="icon fa fa-circle-o"></i> Supplier</router-link></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-pie-chart"></i><span class="app-menu__label">Charts</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Settings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><router-link class="treeview-item" to="/mail-setting"><i class="icon fa fa-circle-o"></i> Mail Settings</router-link></li>
            <li><router-link class="treeview-item" to="/sms-setting"><i class="icon fa fa-circle-o"></i> SMS Settings</router-link></li>
            <li><a class="treeview-item" href="blank-page.html"><i class="icon fa fa-circle-o"></i> Blank Page</a></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="docs.html"><i class="app-menu__icon fa fa-file-code-o"></i><span class="app-menu__label">Docs</span></a></li>
      </ul>
    </aside>
    </div>
</template>
<script>
import {mapActions, mapMutations, mapState} from 'vuex'
import router from '../../../routes'
export default {
  mounted() {
    // console.log(this.user)
    // console.log(this.test)
  },
  computed:{
    ...mapState({
      user: state => state.auth.user,
      test: state => state.auth.test
    })
  }
}
</script>