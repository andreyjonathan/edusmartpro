<script setup>
import { computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  Calendar, 
  Clock, 
  BookOpen, 
  User, 
  MapPin,
  Check,
  X,
  Loader2,
  Layout
} from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  schedule: {
    type: Object,
    default: () => ({
      subject_id: '',
      teacher_id: '',
      day: 'Monday',
      start_time: '07:00',
      end_time: '08:00',
      class: '',
      room: '',
    })
  },
  subjects: Array,
  teachers: Array,
  days: Array,
  isEdit: Boolean,
});

const formatTime = (time) => {
  if (!time) return '';
  return time.substring(0, 5);
};

const form = useForm({
  subject_id: props.schedule.subject_id,
  teacher_id: props.schedule.teacher_id,
  day: props.schedule.day,
  start_time: formatTime(props.schedule.start_time),
  end_time: formatTime(props.schedule.end_time),
  class: props.schedule.class,
  room: props.schedule.room,
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('schedules.update', props.schedule.id));
  } else {
    form.post(route('schedules.store'));
  }
};

const filteredTeachers = computed(() => {
  if (!form.subject_id) return [];
  
  const selectedSubject = props.subjects.find(s => s.id === form.subject_id);
  if (!selectedSubject) return [];
  
  return props.teachers.filter(t => t.subject === selectedSubject.name);
});

// Reset teacher if subject changes and current teacher doesn't match
watch(() => form.subject_id, (newVal) => {
  if (newVal) {
    const selectedSubject = props.subjects.find(s => s.id === newVal);
    if (form.teacher_id) {
      const currentTeacher = props.teachers.find(t => t.id === form.teacher_id);
      if (currentTeacher && currentTeacher.subject !== selectedSubject.name) {
        form.teacher_id = '';
      }
    }
  } else {
    form.teacher_id = '';
  }
});
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Schedule Assignment -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Calendar :size="20" class="text-indigo-600" />
          {{ lang === 'id' ? 'Penugasan Jadwal' : 'Schedule Assignment' }}
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Mata Pelajaran' : 'Subject' }}</label>
            <div class="relative group">
              <select
                v-model="form.subject_id"
                required
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none appearance-none"
              >
                <option value="" disabled>{{ lang === 'id' ? 'Pilih Mata Pelajaran' : 'Select Subject' }}</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }} ({{ subject.code }})</option>
              </select>
              <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                <BookOpen :size="16" />
              </div>
            </div>
            <p v-if="form.errors.subject_id" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.subject_id }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Guru Pengajar' : 'Teacher' }}</label>
            <div class="relative group">
              <select
                v-model="form.teacher_id"
                required
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none appearance-none"
              >
                <option value="" disabled>{{ lang === 'id' ? 'Pilih Guru' : 'Select Teacher' }}</option>
                <option v-for="teacher in filteredTeachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
              </select>
              <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                <User :size="16" />
              </div>
            </div>
            <p v-if="!form.subject_id" class="text-xs text-indigo-500 font-medium ml-1">
              {{ lang === 'id' ? 'Silakan pilih mata pelajaran terlebih dahulu untuk melihat daftar guru.' : 'Please select a subject first to see the list of teachers.' }}
            </p>
            <p v-else-if="filteredTeachers.length === 0" class="text-xs text-rose-500 font-bold ml-1">
              {{ lang === 'id' ? 'Tidak ada guru yang mengajar mata pelajaran ini.' : 'No teachers found for this subject.' }}
            </p>
            <p v-if="form.errors.teacher_id" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.teacher_id }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Hari' : 'Day' }}</label>
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 p-1 bg-slate-50 rounded-xl">
              <button 
                type="button"
                v-for="d in days" :key="d"
                @click="form.day = d"
                :title="d"
                :class="[
                  'py-2 text-[10px] font-bold rounded-lg transition-all',
                  form.day === d ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'
                ]"
              >
                {{ d.substring(0, 3) }}
              </button>
            </div>
            <p v-if="form.errors.day" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.day }}</p>
          </div>
        </Card>
      </div>

      <!-- Time & Location -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Clock :size="20" class="text-indigo-600" />
          {{ lang === 'id' ? 'Waktu & Lokasi' : 'Time & Location' }}
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Waktu Mulai' : 'Start Time' }}</label>
              <input
                v-model="form.start_time"
                type="time"
                required
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
              />
              <p v-if="form.errors.start_time" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.start_time }}</p>
            </div>
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Waktu Selesai' : 'End Time' }}</label>
              <input
                v-model="form.end_time"
                type="time"
                required
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
              />
              <p v-if="form.errors.end_time" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.end_time }}</p>
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Kelas' : 'Class' }}</label>
            <div class="relative group">
              <input
                v-model="form.class"
                type="text"
                required
                placeholder="e.g. 10-A"
                class="block w-full px-4 py-11/2 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
              />
              <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                <Layout :size="16" />
              </div>
            </div>
            <p v-if="form.errors.class" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.class }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">{{ lang === 'id' ? 'Ruangan' : 'Room' }}</label>
            <div class="relative group">
              <input
                v-model="form.room"
                type="text"
                placeholder="e.g. Lab 1, Room 204"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
              />
              <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                <MapPin :size="16" />
              </div>
            </div>
            <p v-if="form.errors.room" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.room }}</p>
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('schedules.index')"
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
            ? (lang === 'id' ? 'Perbarui Jadwal' : 'Update Schedule') 
            : (lang === 'id' ? 'Simpan Jadwal' : 'Save Schedule') 
        }}
      </button>
    </div>
  </form>
</template>
