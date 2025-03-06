<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ number: number | string; class: string }>()

const formatFn = Intl.NumberFormat('en-CA', {
  style: 'currency',
  currency: 'CAD',
  maximumFractionDigits: 2,
  minimumFractionDigits: 2,
})

const num = computed(() => Number(props.number))
</script>

<template>
  <p :class="props.class">{{ formatFn.format(num) }}</p>
</template>
