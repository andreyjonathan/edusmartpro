<script setup>
import Card from '@/Components/ui/Card.vue';
import { Users, GraduationCap, Calendar, FileText } from 'lucide-vue-next';

const props = defineProps({
  label: String,
  value: [Number, String],
  icon: String,
  trend: String,
  trendUp: Boolean,
});

const icons = {
  Users,
  GraduationCap,
  Calendar,
  FileText,
};
</script>

<template>
  <Card class="p-5 flex items-center justify-between">
    <div>
      <p class="text-sm font-medium text-slate-500">{{ label }}</p>
      <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ value }}</h3>
      <div v-if="trend" class="flex items-center gap-1 mt-2 text-xs font-semibold" :class="trendUp ? 'text-emerald-600' : 'text-rose-600'">
        <span>{{ trend }}</span>
        <span class="text-slate-400 font-normal">dari bulan lalu</span>
      </div>
    </div>
    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
      <component :is="icons[icon]" :size="24" />
    </div>
  </Card>
</template>