<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { 
  Plus, 
  Search, 
  Filter,
  MoreVertical,
  Edit,
  Trash2,
  Calendar,
  Clock,
  User,
  BookOpen,
  MapPin
} from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';
import debounce from 'lodash/debounce';

const { t, lang } = useLanguage();

const props = defineProps({
  schedules: Object,
  filters: Object,
  subjects: Array,
});

const search = ref(props.filters.search || '');
const dayFilter = ref(props.filters.day || '');
const subjectFilter = ref(props.filters.subject_id || '');

watch([search, dayFilter, subjectFilter], debounce(() => {
  router.get(route('schedules.index'), { 
    search: search.value, 
    day: dayFilter.value,
    subject_id: subjectFilter.value
  }, {
    preserveState: true,
    replace: true
  });
}, 500));

const deleteSchedule = (id) => {
  if (confirm(lang.value === 'id' ? 'Apakah Anda yakin ingin menghapus jadwal ini?' : 'Are you sure you want to delete this schedule?')) {
    router.delete(route('schedules.destroy', id));
  }
};

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

const getDayColor = (day) => {
  const colors = {
    'Monday': 'bg-blue-100 text-blue-700',
    'Tuesday': 'bg-purple-100 text-purple-700',
    'Wednesday': 'bg-emerald-100 text-emerald-700',
    'Thursday': 'bg-orange-100 text-orange-700',
    'Friday': 'bg-rose-100 text-rose-700',
    'Saturday': 'bg-indigo-100 text-indigo-700',
    'Sunday': 'bg-slate-100 text-slate-700'
  };
  return colors[day] || 'bg-slate-100 text-slate-700';
};
</script>

<template>
  <Head :title="lang === 'id' ? 'Jadwal Pelajaran' : 'School Schedule'" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Jadwal Pelajaran' : 'School Schedule' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Kelola jadwal mingguan untuk semua kelas' : 'Manage weekly schedules for all classes' }}
          </p>
        </div>
        <Link 
          :href="route('schedules.create')"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200"
        >
          <Plus :size="18" />
          {{ lang === 'id' ? 'Tambah Jadwal' : 'Add Schedule' }}
        </Link>
      </div>

      <!-- Filters -->
      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1 relative">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
            <input 
              v-model="search"
              type="text" 
              :placeholder="lang === 'id' ? 'Cari kelas, mata pelajaran, atau guru...' : 'Search class, subject, or teacher...'" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 transition-all"
            />
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto">
            <select 
              v-model="subjectFilter"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Mapel' : 'All Subjects' }}</option>
              <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
            </select>
            <select 
              v-model="dayFilter"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Hari' : 'All Days' }}</option>
              <option v-for="day in days" :key="day" :value="day">{{ day }}</option>
            </select>
          </div>
        </div>
      </Card>

      <!-- Schedule Table -->
      <Card class="overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Hari & Waktu' : 'Day & Time' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Kelas & Ruangan' : 'Class & Room' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Mata Pelajaran' : 'Subject' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Guru' : 'Teacher' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">{{ lang === 'id' ? 'Aksi' : 'Actions' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="schedule in schedules.data" :key="schedule.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <span :class="['inline-flex w-fit px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider', getDayColor(schedule.day)]">
                      {{ schedule.day }}
                    </span>
                    <div class="flex items-center gap-1.5 text-sm text-slate-600 font-medium">
                      <Clock :size="14" class="text-slate-400" />
                      {{ schedule.start_time.substring(0, 5) }} - {{ schedule.end_time.substring(0, 5) }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <span class="text-sm font-bold text-slate-900">{{ schedule.class }}</span>
                    <div v-if="schedule.room" class="flex items-center gap-1.5 text-xs text-slate-500">
                      <MapPin :size="14" class="text-slate-400" />
                      {{ schedule.room }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                      <BookOpen :size="16" />
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ schedule.subject.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 overflow-hidden border border-white shadow-sm">
                      <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${schedule.teacher.name}`" :alt="schedule.teacher.name" />
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ schedule.teacher.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Link 
                      :href="route('schedules.edit', schedule.id)"
                      class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                    >
                      <Edit :size="18" />
                    </Link>
                    <button 
                      @click="deleteSchedule(schedule.id)"
                      class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                    >
                      <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="schedules.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                  <div class="flex flex-col items-center gap-3">
                    <Calendar :size="48" class="text-slate-200" />
                    <p>{{ lang === 'id' ? 'Tidak ada jadwal ditemukan.' : 'No schedules found.' }}</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Placeholder -->
        <div v-if="schedules.links.length > 3" class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
          <div class="text-sm text-slate-500">
            Showing {{ schedules.from }} to {{ schedules.to }} of {{ schedules.total }} schedules
          </div>
          <div class="flex items-center gap-1">
             <Link 
               v-for="(link, i) in schedules.links" 
               :key="i"
               :href="link.url || '#'"
               v-html="link.label"
               :class="[
                 'px-3 py-1 rounded-lg text-sm font-medium transition-all',
                 link.active ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50',
                 !link.url ? 'opacity-50 cursor-not-allowed' : ''
               ]"
             />
          </div>
        </div>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
