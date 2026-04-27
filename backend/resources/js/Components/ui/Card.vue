<script setup>
import { cn } from '@/lib/utils';

defineProps({
  class: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div :class="cn('rounded-3xl border border-slate-200 bg-white text-slate-950 shadow-sm transition-all hover:shadow-md', $props.class)">
    <slot />
  </div>
</template>
