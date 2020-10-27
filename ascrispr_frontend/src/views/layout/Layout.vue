<template>
  <el-container>

    <el-header><site-header/></el-header>
    <el-main><app-main/></el-main>
    <el-footer><site-footer/></el-footer>

  </el-container>

</template>

<script>
import SiteHeader from '@/views/layouts/partials/TheSiteHeader.vue'
import AppMain from '@/views/layout/components/AppMain.vue'
import SiteFooter from '@/views/layouts/partials/TheSiteFooter.vue'

export default {
  name: 'layout',
  components: {
    SiteHeader,
    SiteFooter,
    AppMain
  },
  computed: {

  }
}
</script>


