<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Search, 
  Filter, 
  Download,
  Calendar as CalendarIcon,
  CheckCircle2,
  XCircle,
  Clock,
  Thermometer,
  Pencil,
  Trash2,
  User
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  attendances: Object,
  filters: Object,
  classes: Array,
});

const search = ref(props.filters.search || '');
const date = ref(props.filters.date || '');
const status = ref(props.filters.status || '');
const className = ref(props.filters.class || '');

const updateFilters = debounce(() => {
  router.get(route('attendances.index'), { 
    search: search.value, 
    date: date.value, 
    status: status.value,
    class: className.value
  }, { preserveState: true, replace: true });
}, 500);

watch([search, date, status, className], updateFilters);

const deleteAttendance = (id) => {
  if (confirm('Are you sure you want to delete this attendance record?')) {
    router.delete(route('attendances.destroy', id));
  }
};

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
  <Head title="Attendance Management" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Attendance Tracking</h1>
          <p class="text-slate-500 mt-1">Monitor and manage daily student presence and participation.</p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <Download :size="18" />
            Report
          </button>
          <Link 
            :href="route('attendances.create')"
            class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
          >
            <CalendarIcon :size="18" />
            Record Attendance
          </Link>
        </div>
      </div>

      <!-- Filters & Search -->
      <Card class="p-6">
        <div class="flex flex-col lg:flex-row gap-4 items-end">
          <div class="flex-1 space-y-2 w-full">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Search Student</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                <Search :size="18" />
                </div>
                <input 
                v-model="search"
                type="text" 
                placeholder="Name or NIS..." 
                class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
                />
            </div>
          </div>
          
          <div class="w-full lg:w-48 space-y-2">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Date</label>
            <input 
              v-model="date"
              type="date" 
              class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>

          <div class="w-full lg:w-48 space-y-2">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Class</label>
            <select 
              v-model="className"
              class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            >
              <option value="">All Classes</option>
              <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>

          <div class="w-full lg:w-48 space-y-2">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Status</label>
            <select 
              v-model="status"
              class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            >
              <option value="">All Status</option>
              <option v-for="s in ['Present', 'Absent', 'Late', 'Sick']" :key="s" :value="s">{{ s }}</option>
            </select>
          </div>
        </div>
      </Card>

      <!-- Table Section -->
      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Student</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="attendance in attendances.data" :key="attendance.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm overflow-hidden">
                        <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${attendance.student.name}`" :alt="attendance.student.name" />
                    </div>
                    <div>
                      <div class="text-sm font-bold text-slate-900">{{ attendance.student.name }}</div>
                      <div class="text-[11px] font-medium text-slate-400">NIS: {{ attendance.student.nis }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">
                    {{ new Date(attendance.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                </td>
                <td class="px-6 py-4">
                  <div 
                    :class="[
                        'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border transition-all',
                        getStatusStyles(attendance.status)
                    ]"
                  >
                    <component :is="getStatusIcon(attendance.status)" :size="12" />
                    {{ attendance.status }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate italic">
                    {{ attendance.notes || '-' }}
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link 
                        :href="route('attendances.edit', attendance.id)"
                        class="p-2 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-all"
                    >
                        <Pencil :size="18" />
                    </Link>
                    <button 
                        @click="deleteAttendance(attendance.id)"
                        class="p-2 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all"
                    >
                        <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="attendances.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">
                    No attendance records found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
          <p class="text-xs font-medium text-slate-500">
            Showing {{ attendances.from || 0 }} to {{ attendances.to || 0 }} of {{ attendances.total }} records
          </p>
          <div class="flex items-center gap-2">
            <Link 
                v-for="(link, k) in attendances.links" 
                :key="k"
                :href="link.url || '#'"
                v-html="link.label"
                :class="[
                    'px-3 py-1.5 rounded-lg text-xs font-bold transition-all border',
                    link.active ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-100' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:text-indigo-600',
                    !link.url ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''
                ]"
            />
          </div>
        </div>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
