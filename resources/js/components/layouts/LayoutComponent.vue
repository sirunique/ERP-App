<template>
    <div>
        <!-- Navbar -->
        <Navbar></Navbar>
        <!-- Sidebar -->
        <Sidebar></Sidebar>
    </div>
</template>

<script>
    import Navbar from '../layouts/navbars/NavbarComponent'
    import Sidebar from '../layouts/navbars/SidebarComponent'
    
    import {mapActions, mapMutations, mapState} from 'vuex'
    import router from '../../routes'

    export default {
        mounted() {
            // console.log('Layout App Component mounted.')
            this.loadUser();
        },
        components:{
            Navbar,
            Sidebar
        },
        methods:{
            ...mapMutations({}),
            ...mapActions({
                loadUser: 'auth/loadUser'
            })
        }
    }
</script>