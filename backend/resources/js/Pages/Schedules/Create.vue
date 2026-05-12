<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ScheduleForm from '@/Components/ScheduleForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { lang } = useLanguage();

defineProps({
  subjects: Array,
  teachers: Array,
  days: Array,
});
</script>

<template>
  <Head :title="lang === 'id' ? 'Tambah Jadwal' : 'Add Schedule'" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col gap-4">
        <Link 
          :href="route('schedules.index')"
          class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors w-fit"
        >
          <ChevronLeft :size="20" />
          <span class="text-sm font-bold uppercase tracking-wider">{{ lang === 'id' ? 'Kembali' : 'Back' }}</span>
        </Link>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Tambah Jadwal Baru' : 'Add New Schedule' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Buat jadwal pelajaran baru untuk guru dan kelas tertentu' : 'Create a new lesson schedule for a specific teacher and class' }}
          </p>
        </div>
      </div>

      <ScheduleForm 
        :subjects="subjects" 
        :teachers="teachers" 
        :days="days" 
      />
    </div>
  </AuthenticatedLayout>
</template>
