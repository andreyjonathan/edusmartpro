<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, GraduationCap, Check, Loader2, Calculator } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  grade: Object,
  students: Array,
  subjects: Array,
});

const form = useForm({
  student_id: props.grade.student_id,
  subject_id: props.grade.subject_id,
  semester: props.grade.semester,
  academic_year: props.grade.academic_year,
  task_score: props.grade.task_score,
  mid_exam_score: props.grade.mid_exam_score,
  final_exam_score: props.grade.final_exam_score,
  notes: props.grade.notes || '',
});

const finalScore = ref(props.grade.final_score || 0);
const letterGrade = ref(props.grade.letter_grade || '');

const calculateFinal = () => {
  const task = parseFloat(form.task_score) || 0;
  const mid = parseFloat(form.mid_exam_score) || 0;
  const final = parseFloat(form.final_exam_score) || 0;
  const result = Math.round((task * 0.30) + (mid * 0.30) + (final * 0.40) * 100) / 100;
  finalScore.value = result;
  
  if (result >= 90) letterGrade.value = 'A';
  else if (result >= 80) letterGrade.value = 'B';
  else if (result >= 70) letterGrade.value = 'C';
  else if (result >= 60) letterGrade.value = 'D';
  else letterGrade.value = 'E';
};

watch(() => form.task_score, calculateFinal);
watch(() => form.mid_exam_score, calculateFinal);
watch(() => form.final_exam_score, calculateFinal);

calculateFinal();

const submit = () => {
  form.put(route('grades.update', props.grade.id));
};
</script>

<template>
  <Head title="Edit Student Grade" />

  <AuthenticatedLayout>
    <div class="max-w-3xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
          :href="route('grades.index')" 
          class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 w-fit"
        >
          <ChevronLeft :size="18" />
          Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Student Grade</h1>
        <p class="text-slate-500">Update assessment scores. Final score auto-calculated (30% Tugas + 30% UTS + 40% UAS).</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <Card class="p-6 space-y-6">
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <GraduationCap :size="20" class="text-indigo-600" />
            Student & Subject
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Student <span class="text-rose-500">*</span></label>
              <select
                v-model="form.student_id"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              >
                <option value="">Select student</option>
                <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }} ({{ s.nis }})</option>
              </select>
              <p v-if="form.errors.student_id" class="text-xs text-rose-500 font-bold">{{ form.errors.student_id }}</p>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Subject <span class="text-rose-500">*</span></label>
              <select
                v-model="form.subject_id"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              >
                <option value="">Select subject</option>
                <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
              <p v-if="form.errors.subject_id" class="text-xs text-rose-500 font-bold">{{ form.errors.subject_id }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Semester <span class="text-rose-500">*</span></label>
              <select
                v-model="form.semester"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              >
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
              </select>
              <p v-if="form.errors.semester" class="text-xs text-rose-500 font-bold">{{ form.errors.semester }}</p>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Academic Year <span class="text-rose-500">*</span></label>
              <input
                v-model="form.academic_year"
                type="text"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
              <p v-if="form.errors.academic_year" class="text-xs text-rose-500 font-bold">{{ form.errors.academic_year }}</p>
            </div>
          </div>
        </Card>

        <Card class="p-6 space-y-6">
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Calculator :size="20" class="text-indigo-600" />
            Assessment Scores
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Tugas (30%) <span class="text-rose-500">*</span></label>
              <input
                v-model.number="form.task_score"
                type="number"
                required
                min="0"
                max="100"
                step="0.5"
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">UTS (30%) <span class="text-rose-500">*</span></label>
              <input
                v-model.number="form.mid_exam_score"
                type="number"
                required
                min="0"
                max="100"
                step="0.5"
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">UAS (40%) <span class="text-rose-500">*</span></label>
              <input
                v-model.number="form.final_exam_score"
                type="number"
                required
                min="0"
                max="100"
                step="0.5"
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
            </div>
          </div>

          <div class="bg-slate-50 rounded-2xl p-6 space-y-3 border border-slate-100">
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-slate-600">Final Score (Auto-calculated)</span>
              <span class="text-3xl font-bold text-indigo-600">{{ finalScore.toFixed(2) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-slate-600">Letter Grade</span>
              <span class="text-2xl font-bold text-emerald-600">{{ letterGrade || '-' }}</span>
            </div>
          </div>
        </Card>

        <Card class="p-6 space-y-4">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 block">Notes</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"
            ></textarea>
          </div>
        </Card>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <Link 
            :href="route('grades.index')"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50"
          >
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 disabled:opacity-70"
          >
            <Loader2 v-if="form.processing" class="animate-spin" :size="18" />
            <Check v-else :size="18" />
            Update Grade
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>