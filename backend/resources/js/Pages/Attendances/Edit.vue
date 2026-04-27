<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttendanceForm from '@/Components/AttendanceForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const props = defineProps({
  attendance: Object,
  students: Array,
});
</script>

<template>
  <Head :title="`Edit Attendance: ${attendance.student.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
            :href="route('attendances.index')" 
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
          >
            <ChevronLeft :size="18" />
            Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Attendance Record</h1>
        <p class="text-slate-500">Update the attendance status or notes for this specific record.</p>
      </div>

      <AttendanceForm :attendance="attendance" :students="students" :is-edit="true" />
    </div>
  </AuthenticatedLayout>
</template>
