<script setup lang="ts">
import { ref } from 'vue'
import Fee from './Fee.vue'

const props = defineProps<{ auction: Record<string, any> | null }>()
</script>

<template>
  <div class="h-84 flex flex-col" v-if="auction">
    <h2 class="text-2xl font-bold my-8">Total fees:</h2>
    <div class="grid grid-cols-2 grid-auto-rows gap-2">
      <p>Base vehicle value:</p>
      <Fee class="text-right" :number="auction.vehicleValue" />
      <p>Vehicle type:</p>
      <p class="capitalize text-right">{{ auction.vehicleType }}</p>
      <hr class="col-span-2 mb-4" />
      <p>List of fees:</p>
      <div class="grid grid-cols-2 grid-auto-rows">
        <p>Basic buyer fee:</p>
        <Fee class="text-right" :number="auction.feesList.Basic" />
        <p>Seller's seller fee:</p>
        <Fee class="text-right" :number="auction.feesList.Seller" />
        <p>Association's added cost:</p>
        <Fee class="text-right" :number="auction.feesList.Association" />
        <p>Fixed storage fee:</p>
        <Fee class="text-right" :number="auction.feesList.Storage" />
        <hr class="col-span-2" />
      </div>
      <p class="font-bold">Total fees:</p>
      <Fee class="text-right font-bold" :number="auction.totalFees" />
      <p class="font-bold text-lg mt-4">Total vehicle cost:</p>
      <Fee class="text-right text-lg font-bold mt-4" :number="auction.totalVehicleValue" />
    </div>
  </div>
</template>
