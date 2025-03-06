<script setup lang="ts">
import { ref, watch } from 'vue'
import CustomInput from '../components/Input.vue'
import CustomSelect from '../components/Select.vue'
import FeesList from '../components/FeesList.vue'
import { fetchFeesData } from '../api/fees.ts'
import { useDebouncedRef } from '../helpers/useDebouncedRef.ts'

const selectRef = ref(null)
const inputRef = useDebouncedRef(null)
const inputErrorsRef = ref(null)
const selectErrorsRef = ref(null)
const auctionRef = ref(null)

const isInputDirty = ref(false)
const isSelectDirty = ref(false)

watch([inputRef, selectRef], async ([newInputValue, newSelectValue], _) => {
  if (newInputValue !== null) {
    isInputDirty.value = true
  }

  if (newSelectValue !== null) {
    isSelectDirty.value = true
  }

  if (isInputDirty.value && isSelectDirty.value) {
    const { auction, errors } = await fetchFeesData({
      vehicleType: selectRef.value,
      vehicleValue: inputRef.value,
    })

    if (errors) {
      inputErrorsRef.value = errors?.vehicleValue
      selectErrorsRef.value = errors?.vehicleType
      return
    } else {
      inputErrorsRef.value = null
      selectErrorsRef.value = null
      auctionRef.value = { ...auction }
    }
  }
})
</script>

<template>
  <main class="max-w-6xl mx-auto h-full flex flex-col gap-20 items-center py-96">
    <h1 class="text-4xl font-bold">Auction calculator</h1>
    <div class="flex flex-wrap gap-2 lg:flex-nowrap">
      <CustomSelect
        :errors="selectErrorsRef"
        :options="[
          { value: 'common', label: 'Common' },
          { value: 'luxury', label: 'Luxury' },
        ]"
        placeholder="Choose a vehicle type"
        v-model="selectRef"
      />
      <CustomInput
        type="number"
        placeholder="Enter a vehicle price"
        v-model="inputRef"
        :errors="inputErrorsRef"
      />
    </div>

    <FeesList :auction="auctionRef" />
  </main>
</template>
