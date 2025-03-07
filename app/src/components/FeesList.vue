<script setup lang="ts">
import { ref } from 'vue'
import Fee from './Fee.vue'

const props = defineProps<{ auction: Record<string, any> | null }>()
</script>

<template>
  <div class="h-84 flex flex-col" v-if="auction">
    <h2 class="text-2xl font-bold my-8 text-slate-800 dark:text-slate-300">Total fees:</h2>
    <div class="grid grid-cols-2 grid-auto-rows gap-2">
      <p class="text-slate-800 dark:text-slate-300">Base vehicle value:</p>
      <Fee class="text-right text-slate-800 dark:text-slate-300" :number="auction.vehicleValue" />
      <p class="text-slate-800 dark:text-slate-300">Vehicle type:</p>
      <p class="capitalize text-right text-slate-800 dark:text-slate-300">
        {{ auction.vehicleType }}
      </p>
      <hr class="col-span-2 mb-4 text-slate-800 dark:text-slate-300" />
      <p class="text-slate-800 dark:text-slate-300">List of fees:</p>
      <div class="grid grid-cols-2 grid-auto-rows">
        <p class="text-slate-800 dark:text-slate-300">Basic buyer fee:</p>
        <Fee
          class="text-right text-slate-800 dark:text-slate-300"
          :number="auction.feesList.Basic"
        />
        <p class="text-slate-800 dark:text-slate-300">Seller's seller fee:</p>
        <Fee
          class="text-right text-slate-800 dark:text-slate-300"
          :number="auction.feesList.Seller"
        />
        <p class="text-slate-800 dark:text-slate-300">Association's added cost:</p>
        <Fee
          class="text-right text-slate-800 dark:text-slate-300"
          :number="auction.feesList.Association"
        />
        <p class="text-slate-800 dark:text-slate-300">Fixed storage fee:</p>
        <Fee
          class="text-right text-slate-800 dark:text-slate-300"
          :number="auction.feesList.Storage"
        />
        <hr class="col-span-2 text-slate-800 dark:text-slate-300" />
      </div>
      <p class="font-bold text-slate-800 dark:text-slate-300">Total fees:</p>
      <Fee
        class="text-right font-bold text-slate-800 dark:text-slate-300"
        :number="auction.totalFees"
      />
      <p class="font-bold text-lg mt-4 text-slate-800 dark:text-slate-300">Total vehicle cost:</p>
      <Fee
        class="text-right text-lg font-bold mt-4 text-slate-800 dark:text-slate-300"
        :number="auction.totalVehicleValue"
      />
    </div>
  </div>
</template>
