<template>
    <el-menu
            :default-active="currentPath"
            class="el-menu-demo"
            mode="horizontal"
            background-color="#545c64"
            text-color="#fff"
            active-text-color="#ffd04b"
            router>
      <el-menu-item index="/ascrispr"><i class="el-icon-third-shengwuhuaxue"></i>{{$t('ascrispr')}}</el-menu-item>
        <el-menu-item index="/dominantdb"><i class="el-icon-search"></i>{{$t('dominantdb')}}</el-menu-item>
        <el-menu-item index="/validateddb"><i class="el-icon-search"></i>{{$t('validateddb')}}</el-menu-item>
      <!--<el-menu-item index="/download"><i class="el-icon-download"></i>{{$t('download')}}</el-menu-item>-->
      <el-menu-item index="/tutorial"><i class="el-icon-search"></i>{{$t('tutorial')}}</el-menu-item>
      <el-menu-item index="/references"><i class="el-icon-third-datacloud"></i>{{$t('references')}}</el-menu-item>
        <el-menu-item index="/links"><i class="el-icon-third-link"></i>{{$t('links')}}</el-menu-item>
      <el-menu-item index="/about"><i class="el-icon-third-guanyuwomen"></i>{{$t('about')}}</el-menu-item>

      <!--<span @click="changeLangEvent"><i class="el-icon-third-zhongyingwen"></i>{{$t('g.zh_en')}}</span>-->
    </el-menu>

</template>


<!--<style>-->

  <!--.el-menu-item {-->
    <!--text-size: 22px  !important;-->
  <!--}-->

<!--</style>-->

<script>
  export default {
    data() {
      return {
        activeIndex: '1',
        lang: 'zh',
        currentPath: this.$route.path
      }
    },
    methods: {
      /**
       * 切换语言
       */
      changeLangEvent() {
        if (this.lang === 'zh') {
          this.lang = 'en'
          this.$i18n.locale = this.lang
        } else {
          this.lang = 'zh'
          this.$i18n.locale = this.lang
        }
      }
    },
    watch: {
      '$route'(to, from) {
        this.currentPath = to.path
      }
    }
  }
</script>
