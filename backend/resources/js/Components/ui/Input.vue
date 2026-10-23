<script setup>
import { computed } from 'vue';

defineProps({
  type: { type: String, default: 'text' },
  modelValue: { type: [String, Number], default: '' },
  placeholder: String,
  disabled: Boolean,
  class: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const inputClasses = computed(() => [
  'w-full px-3 py-2 text-sm bg-white border border-slate-300 rounded-xl',
  'placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
  'disabled:opacity-50 disabled:cursor-not-allowed transition-all',
  props.class,
]);
</script>

<template>
  <input
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :disabled="disabled"
    @input="emit('update:modelValue', $event.target.value)"
    :class="inputClasses"
  />
</template>