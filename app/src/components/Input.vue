<script setup lang="ts">
import { watch } from 'vue'
const value = defineModel<null | number | string>()
const props = defineProps<{
  type: string
  placeholder: string
  errors?: null | undefined | string[]
}>()
</script>
<template>
  <label class="w-full flex-grow basis-3/5" for="vehicle-value">
    <input
      id="vehicle-value"
      :type="props.type"
      :placeholder="props.placeholder"
      :class="[
        'relative px-2 py-1 w-full text-2xl truncate border rounded-lg text-slate-800 placeholder:text-slate-400 dark:text-slate-200 dark:placeholder:text-slate-600',
        props.errors
          ? 'border-red-400 focus:border-red-400 ring-red-400 outline-red-400 dark:border-red-400'
          : 'border-slate-300 dark:border-slate-600',
      ]"
      v-model="value"
    />
    <p class="absolute text-red-400" v-if="props.errors">{{ props.errors[0] }}</p>
  </label>
</template>
