<template>
  <input ref="inputRef" type="text"/>
</template>

<script>
import useCurrencyInput from "vue-currency-input";
import { computed, watch } from '@vue/composition-api'
export default {
  name: 'CurrencyInput',
  props: {
    value: {
      type: Number,
      default: null
    },
  },
  setup(props) {
    const options = computed(() => ({ 
        currency: 'IDR',
        locale : 'id-ID',
        symbol : 'Rp',
        currencyDisplay : "symbol",
        hideCurrencySymbolOnFocus : false,
        hideGroupingSeparatorOnFocus : false,
        hideNegligibleDecimalDigitsOnFocus : false,
        autoDecimalDigits : false,
        autoSign : false,
        useGrouping : true,
        accountingSign : true

    }))
    const { inputRef, setValue } = useCurrencyInput(options.value)
    watch(
      () => props.value,
      (value) => {
        setValue(value)
      }
    )
    return { inputRef }
  }
}
</script>