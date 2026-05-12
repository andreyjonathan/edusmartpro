<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { 
  Plus, 
  Search, 
  Layout,
  MoreVertical,
  Edit,
  Trash2,
  User,
  Calendar,
  GraduationCap
} from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  classrooms: Object,
  filters: Object,
  majors: Array,
  academic_years: Array,
});

const search = ref(props.filters.search || '');
const selectedMajor = ref(props.filters.major || '');
const selectedYear = ref(props.filters.academic_year || '');

watch([search, selectedMajor, selectedYear], debounce(() => {
  router.get(route('classrooms.index'), { 
    search: search.value, 
    major: selectedMajor.value,
    academic_year: selectedYear.value
  }, {
    preserveState: true,
    replace: true
  });
}, 500));

const deleteClassroom = (id) => {
  if (confirm(lang.value === 'id' ? 'Apakah Anda yakin ingin menghapus kelas ini?' : 'Are you sure you want to delete this class?')) {
    router.delete(route('classrooms.destroy', id));
  }
};
</script>

<template>
  <Head :title="lang === 'id' ? 'Manajemen Kelas' : 'Class Management'" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Data Kelas' : 'Class Data' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Kelola daftar kelas, jurusan, dan wali kelas' : 'Manage classes, majors, and homeroom teachers' }}
          </p>
        </div>
        <Link 
          :href="route('classrooms.create')"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200"
        >
          <Plus :size="18" />
          {{ lang === 'id' ? 'Tambah Kelas' : 'Add Class' }}
        </Link>
      </div>

      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="relative flex-1 group">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
            <input 
              v-model="search"
              type="text" 
              :placeholder="lang === 'id' ? 'Cari nama kelas, jurusan, atau wali kelas...' : 'Search class, major, or teacher...'" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto">
            <select 
              v-model="selectedMajor"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Jurusan' : 'All Majors' }}</option>
              <option v-for="m in majors" :key="m" :value="m">{{ m }}</option>
            </select>
            <select 
              v-model="selectedYear"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Tahun' : 'All Years' }}</option>
              <option v-for="y in academic_years" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>
        </div>
      </Card>

      <!-- Grid View -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <Card 
          v-for="classroom in classrooms.data" 
          :key="classroom.id" 
          class="p-6 hover:border-indigo-200 transition-all group relative overflow-hidden"
        >
          <!-- Decoration -->
          <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
          
          <div class="relative space-y-4">
            <div class="flex items-start justify-between">
              <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                <Layout :size="24" />
              </div>
              <div class="flex items-center gap-2">
                <Link 
                  :href="route('classrooms.edit', classroom.id)"
                  class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                >
                  <Edit :size="18" />
                </Link>
                <button 
                  @click="deleteClassroom(classroom.id)"
                  class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                >
                  <Trash2 :size="18" />
                </button>
              </div>
            </div>

            <div>
              <h3 class="text-xl font-bold text-slate-900">{{ classroom.name }}</h3>
              <span v-if="classroom.major" class="inline-flex px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider mt-1">
                {{ classroom.major }}
              </span>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-50">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                  <User :size="16" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ lang === 'id' ? 'Wali Kelas' : 'Homeroom' }}</p>
                  <p class="text-sm font-medium text-slate-700 truncate">
                    {{ classroom.homeroom_teacher?.name || (lang === 'id' ? 'Belum Ada' : 'None') }}
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                  <Calendar :size="16" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ lang === 'id' ? 'Tahun Ajaran' : 'Academic Year' }}</p>
                  <p class="text-sm font-medium text-slate-700">
                    {{ classroom.academic_year || '-' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </Card>
      </div>

      <!-- Empty State -->
      <Card v-if="classrooms.data.length === 0" class="p-12 text-center text-slate-500">
        <div class="flex flex-col items-center gap-4">
          <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
            <Layout :size="48" />
          </div>
          <div class="space-y-1">
            <h3 class="text-lg font-bold text-slate-900">{{ lang === 'id' ? 'Tidak Ada Kelas' : 'No Classes Found' }}</h3>
            <p class="text-sm">{{ lang === 'id' ? 'Mulai dengan menambahkan kelas baru ke sistem.' : 'Start by adding a new class to the system.' }}</p>
          </div>
          <Link 
            :href="route('classrooms.create')"
            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition-all"
          >
            {{ lang === 'id' ? 'Tambah Kelas Pertama' : 'Add First Class' }}
          </Link>
        </div>
      </Card>

      <!-- Pagination -->
      <div v-if="classrooms.links.length > 3" class="flex items-center justify-between pt-4">
        <p class="text-sm text-slate-500">
          Showing {{ classrooms.from }} to {{ classrooms.to }} of {{ classrooms.total }} classes
        </p>
        <div class="flex items-center gap-1">
          <Link 
            v-for="(link, i) in classrooms.links" 
            :key="i"
            :href="link.url || '#'"
            v-html="link.label"
            :class="[
              'px-4 py-2 rounded-xl text-sm font-bold transition-all',
              link.active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50',
              !link.url ? 'opacity-50 cursor-not-allowed' : ''
            ]"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
