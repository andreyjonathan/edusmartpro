<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: 'Select option' },
  disabled: Boolean,
  class: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const selectClasses = computed(() => [
  'w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-xl',
  'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
  'disabled:opacity-50 disabled:cursor-not-allowed transition-all',
  props.class,
]);
</script>

<template>
  <select
    :value="modelValue"
    :disabled="disabled"
    @change="emit('update:modelValue', $event.target.value)"
    :class="selectClasses"
  >
    <option value="" disabled selected v-if="placeholder">{{ placeholder }}</option>
    <option v-for="opt in options" :key="opt.value" :value="opt.value">
      {{ opt.label }}
    </option>
  </select>
</template>