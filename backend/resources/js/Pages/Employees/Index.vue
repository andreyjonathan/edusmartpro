<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Search, 
  Filter, 
  Download,
  UserPlus,
  CheckCircle2,
  XCircle,
  Pencil,
  Trash2,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  employees: Object,
  filters: Object,
  positions: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const selectedPosition = ref(props.filters.position || '');

watch([search, status, selectedPosition], debounce(() => {
  router.get(route('employees.index'), { 
    search: search.value, 
    status: status.value, 
    position: selectedPosition.value 
  }, { 
    preserveState: true, 
    replace: true 
  });
}, 500));

const deleteEmployee = (id) => {
  if (confirm(lang.value === 'id' ? 'Apakah Anda yakin ingin menghapus karyawan ini?' : 'Are you sure you want to delete this employee?')) {
    router.delete(route('employees.destroy', id));
  }
};
</script>

<template>
  <Head title="Employees Management" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ t.employees.title }}</h1>
          <p class="text-slate-500 mt-1">{{ t.employees.subtitle }}</p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <Download :size="18" />
            Export
          </button>
          <Link 
            :href="route('employees.create')"
            class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
          >
            <UserPlus :size="18" />
            {{ t.employees.addEmployee }}
          </Link>
        </div>
      </div>

      <!-- Filters & Search -->
      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
              <Search :size="18" />
            </div>
            <input 
              v-model="search"
              type="text" 
              :placeholder="t.employees.searchPlaceholder" 
              class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto">
            <select 
              v-model="selectedPosition"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Posisi' : 'All Positions' }}</option>
              <option v-for="p in positions" :key="p" :value="p">{{ p }}</option>
            </select>
            <select 
              v-model="status"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Status' : 'All Status' }}</option>
              <option value="Active">{{ t.common.active }}</option>
              <option value="Inactive">{{ t.common.inactive }}</option>
            </select>
          </div>
        </div>
      </Card>

      <!-- Table Section -->
      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t.employees.title }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIK</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Jabatan' : 'Position' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Jenis Kelamin' : 'Gender' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">{{ lang === 'id' ? 'Aksi' : 'Actions' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="employee in employees.data" :key="employee.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 overflow-hidden shadow-sm">
                      <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${employee.name}`" :alt="employee.name" />
                    </div>
                    <div>
                      <div class="text-sm font-bold text-slate-900">{{ employee.name }}</div>
                      <div class="text-[11px] font-medium text-slate-400">{{ employee.email || 'No email' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ employee.nik }}</td>
                <td class="px-6 py-4">
                  <div class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                    {{ employee.position }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ employee.gender }}</td>
                <td class="px-6 py-4">
                  <div 
                    :class="[
                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border',
                        employee.status === 'Active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200'
                    ]"
                  >
                    <CheckCircle2 v-if="employee.status === 'Active'" :size="12" />
                    <XCircle v-else :size="12" />
                    {{ employee.status === 'Active' ? t.common.active : t.common.inactive }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link 
                        :href="route('employees.edit', employee.id)"
                        class="p-2 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-all"
                    >
                        <Pencil :size="18" />
                    </Link>
                    <button 
                        @click="deleteEmployee(employee.id)"
                        class="p-2 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all"
                    >
                        <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="employees.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">
                    {{ lang === 'id' ? 'Tidak ada karyawan yang ditemukan.' : 'No employees found matching your search.' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
          <p class="text-xs font-medium text-slate-500">
            {{ t.common.showing }} {{ employees.from || 0 }} {{ t.common.to }} {{ employees.to || 0 }} {{ t.common.of }} {{ employees.total }} {{ t.common.records }}
          </p>
          <div class="flex items-center gap-2">
            <Link 
                v-for="(link, k) in employees.links" 
                :key="k"
                :href="link.url || '#'"
                v-html="link.label"
                :class="[
                    'px-3 py-1.5 rounded-lg text-xs font-bold transition-all border',
                    link.active ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-100' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:text-indigo-600',
                    !link.url ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''
                ]"
            />
          </div>
        </div>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
