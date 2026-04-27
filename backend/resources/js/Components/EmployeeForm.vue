<script setup>
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  User, 
  Hash, 
  Briefcase, 
  MapPin, 
  Phone,
  Mail,
  Check,
  X,
  Loader2,
  ChevronLeft
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  employee: {
    type: Object,
    default: () => ({
      nik: '',
      name: '',
      position: '',
      gender: 'Male',
      status: 'Active',
      email: '',
      phone: '',
      address: '',
    })
  },
  isEdit: Boolean,
});

const form = useForm({
  nik: props.employee.nik,
  name: props.employee.name,
  position: props.employee.position,
  gender: props.employee.gender,
  status: props.employee.status,
  email: props.employee.email,
  phone: props.employee.phone,
  address: props.employee.address,
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('employees.update', props.employee.id));
  } else {
    form.post(route('employees.store'));
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- General Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <Briefcase :size="20" class="text-indigo-600" />
          Employment Information
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
                <label class="text-sm font-semibold text-slate-700 ml-1">NIK (ID Number)</label>
                <input
                    v-model="form.nik"
                    type="text"
                    required
                    placeholder="e.g. 320..."
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
                <p v-if="form.errors.nik" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.nik }}</p>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Position</label>
                <select
                    v-model="form.position"
                    required
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                >
                    <option value="" disabled>Select Position</option>
                    <option v-for="p in ['Administrative Staff', 'Librarian', 'Security', 'Janitor', 'IT Support', 'Accountant', 'HR Specialist']" :key="p" :value="p">{{ p }}</option>
                </select>
                <p v-if="form.errors.position" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.position }}</p>
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
            <label class="text-sm font-semibold text-slate-700 ml-1">Email Address</label>
            <input
                v-model="form.email"
                type="email"
                placeholder="e.g. employee@school.com"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
            <p v-if="form.errors.email" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.email }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Phone Number</label>
            <input
                v-model="form.phone"
                type="text"
                placeholder="e.g. +62 812..."
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
            <p v-if="form.errors.phone" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.phone }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Address</label>
            <textarea
                v-model="form.address"
                rows="4"
                placeholder="Enter complete address"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none resize-none"
            ></textarea>
            <p v-if="form.errors.address" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.address }}</p>
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('employees.index')"
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
        {{ isEdit ? 'Update Employee' : 'Save Employee' }}
      </button>
    </div>
  </form>
</template>
