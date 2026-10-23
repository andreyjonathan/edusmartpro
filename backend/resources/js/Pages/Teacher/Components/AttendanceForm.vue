<script setup>
import { ref, computed, watch } from 'vue';
import Card from '@/Components/ui/Card.vue';
import Input from '@/Components/ui/Input.vue';
import Select from '@/Components/ui/Select.vue';
import Button from '@/Components/ui/Button.vue';

const props = defineProps({
  schedules: Array,
  selectedSchedule: Object,
  students: Array,
  date: String,
  loading: Boolean,
});

const emit = defineEmits(['schedule-change', 'date-change', 'submit']);

const attendances = ref({});

watch(() => props.students, (newStudents) => {
  newStudents.forEach(s => {
    if (!attendances.value[s.id]) {
      attendances.value[s.id] = { status: 'hadir', notes: '' };
    }
  });
}, { immediate: true });

const isAllHadir = computed(() => {
  return props.students.every(s => attendances.value[s.id]?.status === 'hadir');
});

const setAllStatus = (status) => {
  props.students.forEach(s => {
    if (attendances.value[s.id]) {
      attendances.value[s.id].status = status;
    }
  });
};

const formatSubmitData = () => {
  return props.students.map(s => ({
    student_id: s.id,
    status: attendances.value[s.id]?.status || 'hadir',
    notes: attendances.value[s.id]?.notes || '',
  }));
};

const handleSubmit = () => {
  if (!props.selectedSchedule) {
    alert('Pilih jadwal terlebih dahulu');
    return;
  }
  emit('submit', formatSubmitData());
};
</script>

<template>
  <div class="space-y-6">
    <!-- Schedule Selector & Date -->
    <Card class="p-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Jadwal</label>
          <Select
            :options="schedules.map(s => ({ value: s.id, label: `${s.subject} - ${s.class} (${s.start_time} - ${s.end_time})` }))"
            :modelValue="selectedSchedule?.id"
            @update:modelValue="val => emit('schedule-change', schedules.find(s => s.id === val))"
            placeholder="Pilih jadwal mengajar"
            class="w-full"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
          <Input
            type="date"
            :modelValue="date"
            @update:modelValue="emit('date-change', $event)"
            class="w-full"
          />
        </div>
      </div>
    </Card>

    <template v-if="selectedSchedule && students.length > 0">
      <!-- Quick Actions -->
      <Card class="p-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-semibold text-slate-800">Aksi Cepat</h3>
          <div class="flex gap-2">
            <Button @click="setAllStatus('hadir')" variant="ghost" size="sm">Semua Hadir</Button>
            <Button @click="setAllStatus('izin')" variant="ghost" size="sm">Semua Izin</Button>
            <Button @click="setAllStatus('sakit')" variant="ghost" size="sm">Semua Sakit</Button>
            <Button @click="setAllStatus('alpa')" variant="ghost" size="sm">Semua Alpa</Button>
          </div>
        </div>

        <!-- Attendance Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-200 text-xs font-medium text-slate-500 uppercase">
                <th class="px-4 py-3 text-left">NIS</th>
                <th class="px-4 py-3 text-left">Nama Siswa</th>
                <th class="px-4 py-3 text-center w-32">Status</th>
                <th class="px-4 py-3 text-left">Catatan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="s in students" :key="s.id" class="hover:bg-slate-50">
                <td class="px-4 py-3 text-slate-600">{{ s.nis }}</td>
                <td class="px-4 py-3 font-medium text-slate-800">{{ s.name }}</td>
                <td class="px-4 py-3 text-center">
                  <Select
                    :options="[
                      { value: 'hadir', label: 'Hadir' },
                      { value: 'izin', label: 'Izin' },
                      { value: 'sakit', label: 'Sakit' },
                      { value: 'alpa', label: 'Alpa' },
                    ]"
                    :modelValue="attendances[s.id]?.status"
                    @update:modelValue="v => attendances[s.id] = { ...attendances[s.id], status: v }"
                    class="w-24 text-xs"
                  />
                </td>
                <td class="px-4 py-3">
                  <Input
                    type="text"
                    :modelValue="attendances[s.id]?.notes"
                    @update:modelValue="v => attendances[s.id] = { ...attendances[s.id], notes: v }"
                    placeholder="Catatan..."
                    class="w-full text-xs"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Submit Button -->
      <div class="flex justify-end">
        <Button @click="handleSubmit" :disabled="loading" :loading="loading" class="w-full md:w-48">
          Simpan Absensi
        </Button>
      </div>
    </template>

    <template v-else-if="selectedSchedule">
      <Card class="p-8 text-center">
        <p class="text-slate-500">Tidak ada siswa di kelas ini</p>
      </Card>
    </template>

    <template v-else>
      <Card class="p-8 text-center">
        <p class="text-slate-500">Silakan pilih jadwal untuk memulai input absensi</p>
      </Card>
    </template>
  </div>
</template>