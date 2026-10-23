<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Search, Plus, BookOpen, Pencil, Trash2, GraduationCap, User, Filter } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  grades: Object,
  subjects: Array,
  filters: Object,
});

const search = ref(props.filters.search || '');
const subjectId = ref(props.filters.subject_id || '');
const semester = ref(props.filters.semester || '');

watch([search, subjectId, semester], debounce(() => {
  router.get(route('grades.index'), { 
    search: search.value, 
    subject_id: subjectId.value,
    semester: semester.value,
  }, { preserveState: true, replace: true });
}, 500));

const deleteGrade = (id) => {
  if (confirm('Are you sure you want to delete this grade?')) {
    router.delete(route('grades.destroy', id));
  }
};

const letterGradeColors = {
  'A': 'bg-emerald-50 text-emerald-700 border-emerald-100',
  'B': 'bg-blue-50 text-blue-700 border-blue-100',
  'C': 'bg-amber-50 text-amber-700 border-amber-100',
  'D': 'bg-orange-50 text-orange-700 border-orange-100',
  'E': 'bg-rose-50 text-rose-700 border-rose-100',
};
</script>

<template>
  <Head title="Student Grades" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Student Grades</h1>
          <p class="text-slate-500 mt-1">Manage student assessment scores and letter grades.</p>
        </div>
        <Link 
          :href="route('grades.create')"
          class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
        >
          <Plus :size="18" />
          Add Grade
        </Link>
      </div>

      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="relative flex-1 md:w-80 group">
            <Search :size="18" class="absolute left-4 top-3 text-slate-400 group-focus-within:text-indigo-500" />
            <input 
              v-model="search"
              type="text" 
              placeholder="Search student name..." 
              class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
            />
          </div>
          <select v-model="subjectId" class="w-full md:w-64 bg-slate-50 border border-slate-200 rounded-xl py-2 pl-4 pr-10 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">All Subjects</option>
            <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
          <select v-model="semester" class="w-full md:w-48 bg-slate-50 border border-slate-200 rounded-xl py-2 pl-4 pr-10 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">All Semesters</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
          </select>
        </div>
      </Card>

      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Student</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Subject</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Semester</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Tugas (30%)</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">UTS (30%)</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">UAS (40%)</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Final</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Grade</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="grade in grades.data" :key="grade.id" class="hover:bg-slate-50/50">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">
                    <GraduationCap :size="18" />
                  </div>
                  <div>
                    <div class="text-sm font-bold text-slate-900">{{ grade.student?.name || 'N/A' }}</div>
                    <div class="text-xs text-slate-400">{{ grade.student?.nis || '' }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ grade.subject?.name || 'N/A' }}</td>
              <td class="px-6 py-4 text-sm text-slate-500">{{ grade.semester }} {{ grade.academic_year }}</td>
              <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ grade.task_score }}</td>
              <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ grade.mid_exam_score }}</td>
              <td class="px-6 py-4 text-sm font-bold text-purple-600">{{ grade.final_exam_score }}</td>
              <td class="px-6 py-4 text-lg font-bold text-slate-900">{{ grade.final_score }}</td>
              <td class="px-6 py-4">
                <span v-if="grade.letter_grade" 
                  :class="['inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border', letterGradeColors[grade.letter_grade] || 'bg-slate-50 text-slate-700 border-slate-100']"
                >
                  {{ grade.letter_grade }}
                </span>
                <span v-else class="text-slate-400 text-sm">-</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link :href="route('grades.edit', grade.id)" class="p-2 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg">
                    <Pencil :size="18" />
                  </Link>
                  <button @click="deleteGrade(grade.id)" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg">
                    <Trash2 :size="18" />
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="grades.data.length === 0">
              <td colspan="9" class="px-6 py-12 text-center text-slate-500 font-medium">No grades found.</td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>