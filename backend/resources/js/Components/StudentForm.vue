<script setup>
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  User, 
  Hash, 
  School, 
  MapPin, 
  Phone,
  Check,
  X,
  Loader2
} from 'lucide-vue-next';

const props = defineProps({
  student: {
    type: Object,
    default: () => ({
      nis: '',
      name: '',
      class: '',
      gender: 'Male',
      status: 'Active',
      address: '',
      phone: '',
    })
  },
  isEdit: Boolean,
});

const form = useForm({
  nis: props.student.nis,
  name: props.student.name,
  class: props.student.class,
  gender: props.student.gender,
  status: props.student.status,
  address: props.student.address,
  phone: props.student.phone,
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('students.update', props.student.id));
  } else {
    form.post(route('students.store'));
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Personal Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <User :size="20" class="text-indigo-600" />
          Personal Information
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Full Name</label>
            <div class="relative group">
                <input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Enter full name"
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
            </div>
            <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.name }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">NIS</label>
                <input
                    v-model="form.nis"
                    type="text"
                    required
                    placeholder="e.g. 12345"
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
                <p v-if="form.errors.nis" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.nis }}</p>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Class</label>
                <select
                    v-model="form.class"
                    required
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                >
                    <option value="" disabled>Select Class</option>
                    <option v-for="c in ['10-A', '10-B', '11-A', '11-B', '12-A', '12-B']" :key="c" :value="c">{{ c }}</option>
                </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Gender</label>
                <div class="flex gap-2 p-1 bg-slate-50 rounded-xl">
                    <button 
                        type="button"
                        v-for="g in ['Male', 'Female']" :key="g"
                        @click="form.gender = g"
                        :class="[
                            'flex-1 py-2 text-xs font-bold rounded-lg transition-all',
                            form.gender === g ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        {{ g }}
                    </button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Status</label>
                <div class="flex gap-2 p-1 bg-slate-50 rounded-xl">
                    <button 
                        type="button"
                        v-for="s in ['Active', 'Inactive']" :key="s"
                        @click="form.status = s"
                        :class="[
                            'flex-1 py-2 text-xs font-bold rounded-lg transition-all',
                            form.status === s ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        {{ s }}
                    </button>
                </div>
            </div>
          </div>
        </Card>
      </div>

      <!-- Contact Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Phone :size="20" class="text-indigo-600" />
          Contact Details
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Phone Number</label>
            <input
                v-model="form.phone"
                type="text"
                placeholder="e.g. +62 812..."
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Address</label>
            <textarea
                v-model="form.address"
                rows="4"
                placeholder="Enter complete address"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none resize-none"
            ></textarea>
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('students.index')"
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
        {{ isEdit ? 'Update Student' : 'Save Student' }}
      </button>
    </div>
  </form>
</template>
