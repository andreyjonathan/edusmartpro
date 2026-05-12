<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ClassroomForm from '@/Components/ClassroomForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { lang } = useLanguage();

defineProps({
  teachers: Array,
});
</script>

<template>
  <Head :title="lang === 'id' ? 'Tambah Kelas' : 'Add Class'" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col gap-4">
        <Link 
          :href="route('classrooms.index')"
          class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors w-fit"
        >
          <ChevronLeft :size="20" />
          <span class="text-sm font-bold uppercase tracking-wider">{{ lang === 'id' ? 'Kembali' : 'Back' }}</span>
        </Link>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Tambah Kelas Baru' : 'Add New Class' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Daftarkan kelas baru ke dalam sistem akademik' : 'Register a new class into the academic system' }}
          </p>
        </div>
      </div>

      <ClassroomForm :teachers="teachers" />
    </div>
  </AuthenticatedLayout>
</template>
