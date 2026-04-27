<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StudentForm from '@/Components/StudentForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

defineProps({
  student: Object,
});
</script>

<template>
  <Head :title="`Edit Student: ${student.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
            :href="route('students.index')" 
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
        >
            <ChevronLeft :size="18" />
            Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Student</h1>
        <p class="text-slate-500">Updating records for <span class="font-bold text-slate-700">{{ student.name }}</span>.</p>
      </div>

      <StudentForm :student="student" :is-edit="true" />
    </div>
  </AuthenticatedLayout>
</template>
