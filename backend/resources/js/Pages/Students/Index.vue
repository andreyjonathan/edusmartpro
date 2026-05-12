<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Search, 
  Filter, 
  MoreHorizontal, 
  Download,
  UserPlus,
  ArrowUpDown,
  CheckCircle2,
  XCircle,
  Pencil,
  Trash2,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  students: Object,
  filters: Object,
  classes: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const selectedClass = ref(props.filters.class || '');

watch([search, status, selectedClass], debounce(() => {
  router.get(route('students.index'), { 
    search: search.value, 
    status: status.value, 
    class: selectedClass.value 
  }, { 
    preserveState: true, 
    replace: true 
  });
}, 500));

const deleteStudent = (id) => {
  if (confirm(lang.value === 'id' ? 'Apakah Anda yakin ingin menghapus siswa ini?' : 'Are you sure you want to delete this student?')) {
    router.delete(route('students.destroy', id));
  }
};
</script>

<template>
  <Head title="Students Management" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ t.students.title }}</h1>
          <p class="text-slate-500 mt-1">{{ t.students.subtitle }}</p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <Download :size="18" />
            Export
          </button>
          <Link 
            :href="route('students.create')"
            class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
          >
            <UserPlus :size="18" />
            {{ t.students.addStudent }}
          </Link>
        </div>
      </div>

      <!-- Filters & Search -->
      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
              <Search :size="18" />
            </div>
            <input 
              v-model="search"
              type="text" 
              :placeholder="t.students.searchPlaceholder" 
              class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto">
            <select 
              v-model="selectedClass"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ t.students.allClasses }}</option>
              <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
            </select>
            <select 
              v-model="status"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ t.students.allStatus }}</option>
              <option value="Active">{{ t.common.active }}</option>
              <option value="Inactive">{{ t.common.inactive }}</option>
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
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.students.name }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.students.nis }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.students.class }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.students.gender }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.students.status }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">{{ lang === 'id' ? 'Aksi' : 'Actions' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="student in students.data" :key="student.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 overflow-hidden shadow-sm">
                      <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${student.name}`" :alt="student.name" />
                    </div>
                    <div>
                      <div class="text-sm font-bold text-slate-900">{{ student.name }}</div>
                      <div class="text-[11px] font-medium text-slate-400">ID: #STU-{{ student.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ student.nis }}</td>
                <td class="px-6 py-4">
                  <div class="inline-flex px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                    {{ student.class }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ student.gender }}</td>
                <td class="px-6 py-4">
                  <div 
                    :class="[
                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border',
                        student.status === 'Active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200'
                    ]"
                  >
                    <CheckCircle2 v-if="student.status === 'Active'" :size="12" />
                    <XCircle v-else :size="12" />
                    {{ student.status === 'Active' ? t.common.active : t.common.inactive }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link 
                        :href="route('students.edit', student.id)"
                        class="p-2 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-all"
                    >
                        <Pencil :size="18" />
                    </Link>
                    <button 
                        @click="deleteStudent(student.id)"
                        class="p-2 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all"
                    >
                        <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="students.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">
                    {{ t.students.noRecords }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
          <p class="text-xs font-medium text-slate-500">
            {{ t.common.showing }} {{ students.from || 0 }} {{ t.common.to }} {{ students.to || 0 }} {{ t.common.of }} {{ students.total }} {{ t.common.records }}
          </p>
          <div class="flex items-center gap-2">
            <Link 
                v-for="(link, k) in students.links" 
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
