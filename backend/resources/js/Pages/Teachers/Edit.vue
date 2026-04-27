<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TeacherForm from '@/Components/TeacherForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const props = defineProps({
  teacher: Object,
});
</script>

<template>
  <Head :title="`Edit Teacher: ${teacher.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
            :href="route('teachers.index')" 
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
        >
            <ChevronLeft :size="18" />
            Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Teacher</h1>
        <p class="text-slate-500">Update the teacher's profile and professional information.</p>
      </div>

      <TeacherForm :teacher="teacher" :is-edit="true" />
    </div>
  </AuthenticatedLayout>
</template>
