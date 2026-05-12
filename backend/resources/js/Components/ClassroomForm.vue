<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  Layout, 
  User, 
  Calendar, 
  BookOpen,
  Check,
  X,
  Loader2
} from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { lang } = useLanguage();

const props = defineProps({
  classroom: {
    type: Object,
    default: () => ({
      name: '',
      major: '',
      homeroom_teacher_id: '',
      academic_year: '2023/2024',
    })
  },
  teachers: Array,
  isEdit: Boolean,
});

const form = useForm({
  name: props.classroom.name,
  major: props.classroom.major || '',
  homeroom_teacher_id: props.classroom.homeroom_teacher_id || '',
  academic_year: props.classroom.academic_year || '2023/2024',
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('classrooms.update', props.classroom.id));
  } else {
    form.post(route('classrooms.store'));
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Class Details -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Layout :size="20" class="text-indigo-600" />
          {{ lang === 'id' ? 'Detail Kelas' : 'Class Details' }}
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Nama Kelas' : 'Class Name' }}</label>
            <input
                v-model="form.name"
                type="text"
                required
                placeholder="e.g. 10-A, XII-IPA-1"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
            <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.name }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Jurusan' : 'Major' }}</label>
            <select
                v-model="form.major"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            >
                <option value="">{{ lang === 'id' ? 'Umum / Tidak Ada' : 'General / None' }}</option>
                <option v-for="m in ['IPA', 'IPS', 'Bahasa', 'RPL', 'TKJ', 'Multimedia']" :key="m" :value="m">{{ m }}</option>
            </select>
          </div>
        </Card>
      </div>

      <!-- Management -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <User :size="20" class="text-indigo-600" />
          {{ lang === 'id' ? 'Pengelolaan' : 'Management' }}
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Wali Kelas' : 'Homeroom Teacher' }}</label>
            <select
                v-model="form.homeroom_teacher_id"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            >
                <option value="">{{ lang === 'id' ? 'Belum Ditentukan' : 'Not Assigned' }}</option>
                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Tahun Ajaran' : 'Academic Year' }}</label>
            <input
                v-model="form.academic_year"
                type="text"
                placeholder="e.g. 2023/2024"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('classrooms.index')"
        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50 transition-all"
      >
        {{ lang === 'id' ? 'Batal' : 'Cancel' }}
      </Link>
      <button
        type="submit"
        :disabled="form.processing"
        class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 disabled:opacity-70"
      >
        <Loader2 v-if="form.processing" class="animate-spin" :size="18" />
        <Check v-else :size="18" />
        {{ isEdit 
            ? (lang === 'id' ? 'Perbarui Kelas' : 'Update Class') 
            : (lang === 'id' ? 'Simpan Kelas' : 'Save Class') 
        }}
      </button>
    </div>
  </form>
</template>
