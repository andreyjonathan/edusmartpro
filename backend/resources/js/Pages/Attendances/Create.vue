<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttendanceForm from '@/Components/AttendanceForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const props = defineProps({
  attendants: Array,
  type: String,
});

const getTitle = () => {
  switch (props.type) {
    case 'teacher': return 'Record Teacher Attendance';
    case 'employee': return 'Record Employee Attendance';
    default: return 'Record Student Attendance';
  }
};

const getSubtitle = () => {
  switch (props.type) {
    case 'teacher': return 'Log a new attendance record for a teacher.';
    case 'employee': return 'Log a new attendance record for an employee.';
    default: return 'Log a new attendance record for a specific student and date.';
  }
};
</script>

<template>
  <Head :title="getTitle()" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
            :href="route('attendances.index', { type: props.type })" 
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
          >
            <ChevronLeft :size="18" />
            Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ getTitle() }}</h1>
        <p class="text-slate-500">{{ getSubtitle() }}</p>
      </div>

      <AttendanceForm :attendants="attendants" :type="type" :is-edit="false" />
    </div>
  </AuthenticatedLayout>
</template>
