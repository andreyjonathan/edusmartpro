<script setup>
import { ref, watch, computed } from 'vue';
import Card from '@/Components/ui/Card.vue';
import Input from '@/Components/ui/Input.vue';
import Select from '@/Components/ui/Select.vue';
import Button from '@/Components/ui/Button.vue';

const props = defineProps({
  schedules: Array,
  loading: Boolean,
});

const emit = defineEmits(['submit']);

const form = ref({
  subject_id: '',
  classroom: '',
  semester: 'Ganjil',
  academic_year: new Date().getFullYear() + '/' + (new Date().getFullYear() + 1),
});

const students = ref([]);
const grades = ref({});
const isLoadingStudents = ref(false);

const subjects = computed(() => {
  const seen = new Set();
  return props.schedules.filter(s => {
    if (seen.has(s.subject_id)) return false;
    seen.add(s.subject_id);
    return true;
  });
});

const classrooms = computed(() => {
  if (!form.value.subject_id) return [];
  const seen = new Set();
  return props.schedules
    .filter(s => s.subject_id === form.value.subject_id)
    .filter(s => {
      if (seen.has(s.class)) return false;
      seen.add(s.class);
      return true;
    });
});

const loadStudents = async () => {
  if (!form.value.subject_id || !form.value.classroom) {
    students.value = [];
    return;
  }
  
  isLoadingStudents.value = true;
  try {
    const res = await fetch(`/teacher/schedule/students?subject_id=${form.value.subject_id}&classroom=${encodeURIComponent(form.value.classroom)}`);
    const data = await res.json();
    students.value = data;
    data.forEach(s => {
      if (!grades.value[s.id]) {
        grades.value[s.id] = { task_score: '', mid_exam_score: '', final_exam_score: '', notes: '' };
      }
    });
  } catch (e) {
    console.error(e);
  } finally {
    isLoadingStudents.value = false;
  }
};

watch(() => form.value.subject_id, loadStudents);
watch(() => form.value.classroom, loadStudents);

const formatSubmitData = () => {
  return students.value.map(s => ({
    student_id: s.id,
    task_score: grades.value[s.id]?.task_score || null,
    mid_exam_score: grades.value[s.id]?.mid_exam_score || null,
    final_exam_score: grades.value[s.id]?.final_exam_score || null,
    notes: grades.value[s.id]?.notes || '',
  }));
};

const calculateFinal = (g) => {
  const task = parseFloat(g.task_score) || 0;
  const mid = parseFloat(g.mid_exam_score) || 0;
  const fin = parseFloat(g.final_exam_score) || 0;
  if (!g.task_score && !g.mid_exam_score && !g.final_exam_score) return '-';
  const final = Math.round((task * 0.3 + mid * 0.3 + fin * 0.4) * 100) / 100;
  return final;
};

const getLetterGrade = (score) => {
  if (score === '-') return '-';
  if (score >= 90) return 'A';
  if (score >= 80) return 'B';
  if (score >= 70) return 'C';
  if (score >= 60) return 'D';
  return 'E';
};

const handleSubmit = () => {
  if (!form.value.subject_id || !form.value.classroom) {
    alert('Pilih mata pelajaran dan kelas terlebih dahulu');
    return;
  }
  emit('submit', {
    ...form.value,
    grades: formatSubmitData(),
  });
};
</script>

<template>
  <div class="space-y-6">
    <!-- Form Header -->
    <Card class="p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
          <Select
            :options="subjects.map(s => ({ value: s.subject_id, label: s.subject }))"
            v-model="form.subject_id"
            placeholder="Pilih mata pelajaran"
            class="w-full"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
          <Select
            :options="classrooms.map(c => ({ value: c.class, label: c.class }))"
            v-model="form.classroom"
            placeholder="Pilih kelas"
            class="w-full"
            :disabled="!form.subject_id"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Semester</label>
          <Select
            :options="[
              { value: 'Ganjil', label: 'Ganjil' },
              { value: 'Genap', label: 'Genap' },
            ]"
            v-model="form.semester"
            class="w-full"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran</label>
          <Input
            type="text"
            v-model="form.academic_year"
            placeholder="2024/2025"
            class="w-full"
          />
        </div>
      </div>
    </Card>

    <template v-if="students.length > 0">
      <!-- Grade Table -->
      <Card class="p-4">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-200 text-xs font-medium text-slate-500 uppercase">
                <th class="px-4 py-3 text-left w-24">NIS</th>
                <th class="px-4 py-3 text-left">Nama Siswa</th>
                <th class="px-4 py-3 text-center w-24">Tugas (30%)</th>
                <th class="px-4 py-3 text-center w-24">UTS (30%)</th>
                <th class="px-4 py-3 text-center w-24">UAS (40%)</th>
                <th class="px-4 py-3 text-center w-24">Akhir</th>
                <th class="px-4 py-3 text-center w-20">Grade</th>
                <th class="px-4 py-3 text-left">Catatan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="s in students" :key="s.id" class="hover:bg-slate-50">
                <td class="px-4 py-3 text-slate-600">{{ s.nis }}</td>
                <td class="px-4 py-3 font-medium text-slate-800">{{ s.name }}</td>
                <td class="px-4 py-3 text-center">
                  <Input
                    type="number"
                    :modelValue="grades[s.id]?.task_score"
                    @update:modelValue="v => grades[s.id] = { ...grades[s.id], task_score: v }"
                    min="0" max="100" step="0.1"
                    class="w-20 text-center text-sm"
                    placeholder="-"
                  />
                </td>
                <td class="px-4 py-3 text-center">
                  <Input
                    type="number"
                    :modelValue="grades[s.id]?.mid_exam_score"
                    @update:modelValue="v => grades[s.id] = { ...grades[s.id], mid_exam_score: v }"
                    min="0" max="100" step="0.1"
                    class="w-20 text-center text-sm"
                    placeholder="-"
                  />
                </td>
                <td class="px-4 py-3 text-center">
                  <Input
                    type="number"
                    :modelValue="grades[s.id]?.final_exam_score"
                    @update:modelValue="v => grades[s.id] = { ...grades[s.id], final_exam_score: v }"
                    min="0" max="100" step="0.1"
                    class="w-20 text-center text-sm"
                    placeholder="-"
                  />
                </td>
                <td class="px-4 py-3 text-center font-mono font-semibold text-indigo-600">
                  {{ calculateFinal(grades[s.id]) }}
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-semibold"
                    :class="{
                      'bg-emerald-100 text-emerald-700': getLetterGrade(calculateFinal(grades[s.id])) === 'A',
                      'bg-blue-100 text-blue-700': getLetterGrade(calculateFinal(grades[s.id])) === 'B',
                      'bg-yellow-100 text-yellow-700': getLetterGrade(calculateFinal(grades[s.id])) === 'C',
                      'bg-orange-100 text-orange-700': getLetterGrade(calculateFinal(grades[s.id])) === 'D',
                      'bg-rose-100 text-rose-700': getLetterGrade(calculateFinal(grades[s.id])) === 'E',
                      'bg-slate-100 text-slate-500': getLetterGrade(calculateFinal(grades[s.id])) === '-',
                    }">
                    {{ getLetterGrade(calculateFinal(grades[s.id])) }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <Input
                    type="text"
                    :modelValue="grades[s.id]?.notes"
                    @update:modelValue="v => grades[s.id] = { ...grades[s.id], notes: v }"
                    placeholder="Catatan..."
                    class="w-full text-xs"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Submit -->
      <div class="flex justify-end">
        <Button @click="handleSubmit" :disabled="loading" :loading="loading" class="w-full md:w-48">
          Simpan Nilai
        </Button>
      </div>
    </template>

    <template v-else-if="form.subject_id && form.classroom && !isLoadingStudents">
      <Card class="p-8 text-center">
        <p class="text-slate-500">Tidak ada siswa di kelas ini untuk mata pelajaran terpilih</p>
      </Card>
    </template>

    <template v-else-if="isLoadingStudents">
      <Card class="p-8 text-center">
        <p class="text-slate-500">Memuat data siswa...</p>
      </Card>
    </template>

    <template v-else>
      <Card class="p-8 text-center">
        <p class="text-slate-500">Pilih mata pelajaran dan kelas untuk memulai input nilai</p>
      </Card>
    </template>
  </div>
</template>