<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  ChevronLeft, 
  Pencil, 
  Mail, 
  Phone, 
  MapPin, 
  Briefcase, 
  Hash,
  CheckCircle2,
  XCircle,
  Calendar
} from 'lucide-vue-next';

const props = defineProps({
  employee: Object,
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>

<template>
  <Head :title="`Employee Profile: ${employee.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col gap-4">
          <Link 
              :href="route('employees.index')" 
              class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
          >
              <ChevronLeft :size="18" />
              Back to List
          </Link>
          <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-3xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border-4 border-white shadow-xl overflow-hidden">
                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${employee.name}`" :alt="employee.name" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ employee.name }}</h1>
              <div class="flex items-center gap-3 mt-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                    <Briefcase :size="12" />
                    {{ employee.position }}
                </div>
                <div 
                    :class="[
                        'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                        employee.status === 'Active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200'
                    ]"
                  >
                    <CheckCircle2 v-if="employee.status === 'Active'" :size="12" />
                    <XCircle v-else :size="12" />
                    {{ employee.status }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <Link 
            :href="route('employees.edit', employee.id)"
            class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2 h-fit"
        >
            <Pencil :size="18" />
            Edit Profile
        </Link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <Card class="p-6 space-y-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Identification</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                            <Hash :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">NIK</div>
                            <div class="text-sm font-bold text-slate-700">{{ employee.nik }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                            <Calendar :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Joined At</div>
                            <div class="text-sm font-bold text-slate-700">{{ formatDate(employee.created_at) }}</div>
                        </div>
                    </div>
                </div>
            </Card>

            <Card class="p-6 space-y-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Contact Info</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                            <Mail :size="18" />
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Email</div>
                            <div class="text-sm font-bold text-slate-700 truncate">{{ employee.email || '-' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                            <Phone :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Phone</div>
                            <div class="text-sm font-bold text-slate-700">{{ employee.phone || '-' }}</div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <Card class="p-8 space-y-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6">
                        <MapPin :size="20" class="text-indigo-600" />
                        Residential Address
                    </h3>
                    <p class="text-slate-600 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100 min-h-[120px]">
                        {{ employee.address || 'No address information provided.' }}
                    </p>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Staff Position</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl border border-slate-100 bg-white shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold uppercase">
                                {{ employee.position.substring(0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">{{ employee.position }}</div>
                                <div class="text-xs text-slate-400">Primary Role</div>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
