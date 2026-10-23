<script setup>
import Card from '@/Components/ui/Card.vue';
import { Bell, Megaphone } from 'lucide-vue-next';

const props = defineProps({
  announcements: Array,
});
</script>

<template>
  <Card class="p-5 space-y-4">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <h3 class="font-semibold text-slate-800 flex items-center gap-2">
        <Megaphone :size="18" class="text-indigo-600" />
        Pengumuman Sekolah
      </h3>
      <span class="text-xs text-slate-400">Terbaru</span>
    </div>

    <div class="space-y-4">
      <template v-if="announcements && announcements.length > 0">
        <div v-for="ann in announcements" :key="ann.id" class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
          <h4 class="text-sm font-semibold text-slate-800">{{ ann.title }}</h4>
          <p class="text-xs text-slate-600 line-clamp-2">{{ ann.body }}</p>
          <div class="flex items-center justify-between pt-2 text-[10px] text-slate-400">
            <span>Oleh: {{ ann.user?.name || 'Admin' }}</span>
            <span>{{ new Date(ann.published_at).toLocaleDateString('id-ID') }}</span>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="py-6 text-center text-slate-400 text-xs">
          Tidak ada pengumuman saat ini
        </div>
      </template>
    </div>
  </Card>
</template>