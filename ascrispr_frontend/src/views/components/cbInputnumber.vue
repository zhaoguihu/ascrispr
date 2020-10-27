<template>
    <div id="cbInputnumber">
        <el-checkbox v-model=myChecked @change="handleCheckboxChange" v-bind:label=myText ></el-checkbox>
        <template>
            <el-input-number controls-position="right" :min=myMin :max=myMax :step=myStep size="small" :disabled=myIsDisable v-model=myInputValue></el-input-number>

        </template>

    </div>
</template>

<script>
  export default {
    name: 'cbInputnumber',
    props: {
      text: {
        type: String,
        default: 'checkbox1'
      },
      min: {
        type: Number,
        default: 0
      },
      max: {
        type: Number,
        default: 1
      },
      step: {
        type: Number,
        default: 1
      },
      inputValue: {
        type: Number,
        default: 0
      },
      checked: {
        type: Boolean,
        default: false
      },
      isDisable: {
        type: Boolean,
        default: true
      }

    },
    // props: ['text', 'min', 'max', 'step'],
    data() {
      return {
        myIsDisable: this.isDisable,
        myText: this.text,
        myChecked: this.checked,
        myInputValue: this.inputValue,
        myStep: this.step,
        myMin: this.min,
        myMax: this.max
      }
    },
    methods: {
      handleCheckboxChange(val) {
        if (val){
          this.myIsDisable = false
          this.checked = val
        } else {
          this.myIsDisable = true
          this.myChecked = val
        }
      },
      handleParentClickSelectAll(e) {
        this.myIsDisable = false
        this.checked = true
      },
      handleParentClickUnselectAll(e) {
        this.myIsDisable = true
        this.checked = false
      }
    }
  }
</script>

<style scoped>
    .el-checkbox{
        width:200px
    }

</style>
