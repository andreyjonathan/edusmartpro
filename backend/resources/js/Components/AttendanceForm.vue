<script setup>
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  User, 
  Calendar, 
  CheckCircle2, 
  FileText,
  Check,
  Loader2,
  AlertCircle,
  Clock,
  XCircle,
  Thermometer
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  attendance: {
    type: Object,
    default: () => ({
      student_id: '',
      date: new Date().toISOString().split('T')[0],
      status: 'Present',
      notes: '',
    })
  },
  students: Array,
  isEdit: Boolean,
});

const form = useForm({
  student_id: props.attendance.student_id,
  date: props.attendance.date,
  status: props.attendance.status,
  notes: props.attendance.notes,
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('attendances.update', props.attendance.id));
  } else {
    form.post(route('attendances.store'));
  }
};

const statuses = [
  { value: 'Present', icon: CheckCircle2, color: 'text-emerald-500', bg: 'bg-emerald-50', border: 'border-emerald-100' },
  { value: 'Absent', icon: XCircle, color: 'text-rose-500', bg: 'bg-rose-50', border: 'border-rose-100' },
  { value: 'Late', icon: Clock, color: 'text-amber-500', bg: 'bg-amber-50', border: 'border-amber-100' },
  { value: 'Sick', icon: Thermometer, color: 'text-blue-500', bg: 'bg-blue-50', border: 'border-blue-100' },
];
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Core Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Calendar :size="20" class="text-indigo-600" />
          Attendance Details
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Student</label>
            <select
                v-model="form.student_id"
                required
                :disabled="isEdit"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none disabled:bg-slate-50 disabled:text-slate-500"
            >
                <option value="" disabled>Select Student</option>
                <option v-for="student in students" :key="student.id" :value="student.id">
                    {{ student.name }} ({{ student.nis }})
                </option>
            </select>
            <p v-if="form.errors.student_id" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.student_id }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Date</label>
            <input
                v-model="form.date"
                type="date"
                required
                :disabled="isEdit"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none disabled:bg-slate-50 disabled:text-slate-500"
            />
            <p v-if="form.errors.date" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.date }}</p>
          </div>
        </Card>
      </div>

      <!-- Status & Notes -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <CheckCircle2 :size="20" class="text-indigo-600" />
          Status & Remarks
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Status</label>
            <div class="grid grid-cols-2 gap-3">
                <button 
                    type="button"
                    v-for="s in statuses" :key="s.value"
                    @click="form.status = s.value"
                    :class="[
                        'flex items-center gap-3 p-4 rounded-2xl border-2 transition-all text-left',
                        form.status === s.value 
                            ? `${s.bg} ${s.border} border-current ${s.color} ring-4 ring-slate-50` 
                            : 'bg-white border-slate-100 text-slate-500 hover:border-slate-200'
                    ]"
                >
                    <component :is="s.icon" :size="20" />
                    <span class="font-bold text-sm">{{ s.value }}</span>
                </button>
            </div>
            <p v-if="form.errors.status" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.status }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Notes (Optional)</label>
            <textarea
                v-model="form.notes"
                rows="3"
                placeholder="e.g. Arrived 15 mins late due to traffic"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none resize-none"
            ></textarea>
            <p v-if="form.errors.notes" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.notes }}</p>
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('attendances.index')"
        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50 transition-all"
      >
        Cancel
      </Link>
      <button
        type="submit"
        :disabled="form.processing"
        class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 disabled:opacity-70"
      >
        <Loader2 v-if="form.processing" class="animate-spin" :size="18" />
        <Check v-else :size="18" />
        {{ isEdit ? 'Update Record' : 'Record Attendance' }}
      </button>
    </div>
  </form>
</template>
