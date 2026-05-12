<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  ChevronLeft, 
  Pencil, 
  User, 
  Calendar, 
  CheckCircle2,
  Clock,
  XCircle,
  Thermometer,
  FileText
} from 'lucide-vue-next';

const props = defineProps({
  attendance: Object,
});

const getStatusStyles = (status) => {
  switch (status) {
    case 'Present': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    case 'Absent': return 'bg-rose-50 text-rose-700 border-rose-100';
    case 'Late': return 'bg-amber-50 text-amber-700 border-amber-100';
    case 'Sick': return 'bg-blue-50 text-blue-700 border-blue-100';
    default: return 'bg-slate-50 text-slate-700 border-slate-100';
  }
};

const getStatusIcon = (status) => {
  switch (status) {
    case 'Present': return CheckCircle2;
    case 'Absent': return XCircle;
    case 'Late': return Clock;
    case 'Sick': return Thermometer;
    default: return CheckCircle2;
  }
};
</script>

<template>
  <Head :title="`Attendance Detail: ${attendance.attendant.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col gap-4">
          <Link 
              :href="route('attendances.index', { type: attendance.attendant_type.split('\\').pop().toLowerCase() })" 
              class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
          >
              <ChevronLeft :size="18" />
              Back to List
          </Link>
          <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-400 border-4 border-white shadow-lg overflow-hidden">
                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${attendance.attendant.name}`" :alt="attendance.attendant.name" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ attendance.attendant.name }}</h1>
              <p v-if="attendance.attendant.nis" class="text-slate-500 font-medium">Student ID: {{ attendance.attendant.nis }}</p>
              <p v-if="attendance.attendant.nip" class="text-slate-500 font-medium">Teacher ID: {{ attendance.attendant.nip }}</p>
              <p v-if="attendance.attendant.nik" class="text-slate-500 font-medium">Employee ID: {{ attendance.attendant.nik }}</p>
            </div>
          </div>
        </div>
        <Link 
            :href="route('attendances.edit', attendance.id)"
            class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2 h-fit"
        >
            <Pencil :size="18" />
            Edit Record
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <Card class="p-8 space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Session Details</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <Calendar :size="24" />
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase">Date</div>
                        <div class="text-lg font-bold text-slate-800">
                            {{ new Date(attendance.date).toLocaleDateString('en-GB', { dateStyle: 'full' }) }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center border', getStatusStyles(attendance.status)]">
                        <component :is="getStatusIcon(attendance.status)" :size="24" />
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase">Attendance Status</div>
                        <div :class="['text-lg font-bold uppercase tracking-tight', getStatusStyles(attendance.status).split(' ')[1]]">
                            {{ attendance.status }}
                        </div>
                    </div>
                </div>
            </div>
        </Card>

        <Card class="p-8 space-y-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Teacher Remarks</h3>
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 min-h-[140px] relative overflow-hidden">
                <FileText class="absolute -right-4 -bottom-4 text-slate-200" :size="80" />
                <p class="text-slate-600 leading-relaxed relative z-10 italic">
                    {{ attendance.notes || 'No remarks provided for this session.' }}
                </p>
            </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
