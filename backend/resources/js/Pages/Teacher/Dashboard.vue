<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import StatCard from './Components/StatCard.vue';
import ScheduleList from './Components/ScheduleList.vue';
import AttendanceForm from './Components/AttendanceForm.vue';
import GradeEntryForm from './Components/GradeEntryForm.vue';
import AnnouncementWidget from './Components/AnnouncementWidget.vue';
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
  todays_schedules: Array,
  announcements: Array,
});

const activeTab = ref('overview');
const selectedSchedule = ref(null);
const attendanceDate = ref(new Date().toISOString().slice(0, 10));
const gradeData = ref({ subject_id: '', classroom: '', semester: '', academic_year: '' });

const scheduleStudents = ref([]);
const isLoadingAttendance = ref(false);
const isLoadingGrades = ref(false);

onMounted(() => {
  if (props.todays_schedules.length > 0) {
    selectedSchedule.value = props.todays_schedules[0];
    loadScheduleStudents(props.todays_schedules[0].id);
  }
});

const loadScheduleStudents = async (scheduleId) => {
  const schedule = props.todays_schedules.find(s => s.id === scheduleId);
  if (!schedule) return;
  
  isLoadingAttendance.value = true;
  try {
    const response = await router.get(`/teacher/schedule/${scheduleId}/students`, {
      only: ['students'],
      preserveState: true,
    });
    scheduleStudents.value = response.props.students || [];
  } catch (e) {
    console.error(e);
  } finally {
    isLoadingAttendance.value = false;
  }
};

const selectSchedule = (schedule) => {
  selectedSchedule.value = schedule;
  loadScheduleStudents(schedule.id);
};

const handleAttendanceSubmit = (data) => {
  isLoadingAttendance.value = true;
  router.post('/teacher/attendance', {
    schedule_id: selectedSchedule.value.id,
    date: attendanceDate.value,
    attendances: data,
  }, {
    onSuccess: () => {
      isLoadingAttendance.value = false;
      alert('Absensi berhasil disimpan');
    },
    onError: () => {
      isLoadingAttendance.value = false;
    }
  });
};

const handleGradeSubmit = (data) => {
  isLoadingGrades.value = true;
  router.post('/teacher/grades', {
    ...gradeData.value,
    grades: data,
  }, {
    onSuccess: () => {
      isLoadingGrades.value = false;
      alert('Nilai berhasil disimpan');
    },
    onError: () => {
      isLoadingGrades.value = false;
    }
  });
};
</script>

<template>
  <Head title="Dashboard Guru" />

  <AuthenticatedLayout>
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Dashboard Guru</h1>
          <p class="text-slate-500 mt-1">Kelola kegiatan belajar mengajar harian</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
          <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full font-medium">
            Guru
          </span>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard
          label="Kelas Ajar"
          :value="stats.classes"
          icon="Users"
          trend="+2"
          trendUp
        />
        <StatCard
          label="Total Siswa"
          :value="stats.students"
          icon="GraduationCap"
          trend="+15"
          trendUp
        />
        <StatCard
          label="Jadwal Hari Ini"
          :value="stats.todays_schedule"
          icon="Calendar"
          trend="0"
          trendUp
        />
        <StatCard
          label="Tugas Penilaian"
          :value="stats.pending_grading"
          icon="FileText"
          trend="-3"
          :trendUp="false"
        />
      </div>

      <!-- Tabs Navigation -->
      <div class="border-b border-slate-200">
        <nav class="flex gap-6" aria-label="Tabs">
          <button
            @click="activeTab = 'overview'"
            :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'overview' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700']"
          >
            Ringkasan
          </button>
          <button
            @click="activeTab = 'schedule'"
            :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'schedule' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700']"
          >
            Jadwal Mengajar
          </button>
          <button
            @click="activeTab = 'attendance'"
            :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'attendance' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700']"
          >
            Input Absensi
          </button>
          <button
            @click="activeTab = 'grades'"
            :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'grades' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700']"
          >
            Nilai & Tugas
          </button>
        </nav>
      </div>

      <!-- Tab Panels -->
      <div class="space-y-6">
        <!-- Overview Tab -->
        <div v-show="activeTab === 'overview'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2">
            <ScheduleList :schedules="props.todays_schedules" />
          </div>
          <div>
            <AnnouncementWidget :announcements="props.announcements" />
          </div>
        </div>

        <!-- Schedule Tab -->
        <div v-show="activeTab === 'schedule'" class="space-y-4">
          <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Jadwal Mengajar Mingguan</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b border-slate-200">
                    <th class="px-4 py-3 text-left font-semibold text-slate-500">Hari</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-500">Jam</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-500">Mata Pelajaran</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-500">Kelas</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-500">Ruang</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="s in props.todays_schedules" :key="s.id" class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-700">Hari Ini</td>
                    <td class="px-4 py-3 text-slate-700">{{ s.start_time }} - {{ s.end_time }}</td>
                    <td class="px-4 py-3 font-medium text-indigo-600">{{ s.subject }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ s.classroom }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ s.student_count }} siswa</td>
                  </tr>
                  <tr v-if="props.todays_schedules.length === 0">
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Tidak ada jadwal hari ini</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Attendance Tab -->
        <div v-show="activeTab === 'attendance'">
          <AttendanceForm
            :schedules="props.todays_schedules"
            :selected-schedule="selectedSchedule"
            :students="scheduleStudents"
            :date="attendanceDate"
            @schedule-change="selectSchedule"
            @date-change="d => attendanceDate = d"
            @submit="handleAttendanceSubmit"
            :loading="isLoadingAttendance"
          />
        </div>

        <!-- Grades Tab -->
        <div v-show="activeTab === 'grades'">
          <GradeEntryForm
            :schedules="props.todays_schedules"
            @submit="handleGradeSubmit"
            :loading="isLoadingGrades"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>