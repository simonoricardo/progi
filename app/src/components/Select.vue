<script setup lang="ts">
const value = defineModel<null | string>()
const props = defineProps<{
  options: Record<string, string>[]
  placeholder?: string
  errors?: null | undefined | string[]
}>()
</script>

<template>
  <select
    v-model="value"
    class="w-full px-2 py-1 truncate text-2xl basis-2/5 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200"
  >
    <option v-if="props.placeholder" selected disabled :value="null">
      {{ props.placeholder }}
    </option>
    <option v-for="(item, index) in props.options" :key="index" :value="item.value">
      {{ item.label }}
    </option>
  </select>
</template>
