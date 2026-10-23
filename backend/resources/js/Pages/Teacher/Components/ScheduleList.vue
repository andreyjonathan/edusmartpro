<script setup>
import { ChevronDown } from 'lucide-vue-next';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  schedules: Array,
});

const confirmDelete = (e) => {
  if (!confirm('Yakin ingin menghapus jadwal ini?')) {
    e.preventDefault();
  }
};
</script>

<template>
  <div class="space-y-4">
    <template v-if="schedules.length === 0">
      <div class="py-8 text-center text-slate-500">
        <svg class="mx-auto mb-3 text-slate-300 w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path class="stroke-2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <p>Tidak ada jadwal mengajar</p>
      </div>
    </template>

    <template v-else>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-200 text-xs font-medium text-slate-500 uppercase">
              <th class="px-6 py-3 text-left">Hari</th>
              <th class="px-6 py-3 text-left text-center">Jam</th>
              <th class="px-6 py-3 text-left">Mata Pelajaran</th>
              <th class="px-6 py-3 text-left text-center">Kelas</th>
              <th class="px-6 py-3 text-left">Ruang</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="(s, index) in schedules" :key="s.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-3 whitespace-nowrap text-slate-700">{{ s.day }}</td>
              <td class="px-6 py-3 text-center text-slate-600 whitespace-nowrap">{{ s.start_time }} - {{ s.end_time }}</td>
              <td class="px-6 py-3 whitespace-nowrap text-slate-800 font-medium">{{ s.subject }}</td>
              <td class="px-6 py-3 whitespace-nowrap text-slate-600">{{ s.class }}</td>
              <td class="px-6 py-3 text-center text-slate-500 whitespace-nowrap">{{ s.room }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>