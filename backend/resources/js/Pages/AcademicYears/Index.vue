<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, Plus, Calendar, Pencil, Trash2, CheckCircle2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  academicYears: Object,
  filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
  router.get(route('academic-years.index'), { search: value }, { preserveState: true, replace: true });
}, 500));

const deleteYear = (id) => {
  if (confirm('Are you sure you want to delete this academic year?')) {
    router.delete(route('academic-years.destroy', id));
  }
};
</script>

<template>
  <Head title="Academic Years" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Academic Years</h1>
          <p class="text-slate-500 mt-1">Manage school years, semesters, and active status.</p>
        </div>
        <Link 
          :href="route('academic-years.create')"
          class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
        >
          <Plus :size="18" />
          Add Academic Year
        </Link>
      </div>

      <Card class="p-4">
        <div class="relative w-full md:w-96">
          <Search :size="18" class="absolute left-4 top-3 text-slate-400" />
          <input 
            v-model="search"
            type="text" 
            placeholder="Search academic years..." 
            class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
          />
        </div>
      </Card>

      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Year Name</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Semester</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Status</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="year in academicYears.data" :key="year.id" class="hover:bg-slate-50/50">
              <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-3">
                <Calendar :size="20" class="text-indigo-600" />
                {{ year.name }}
              </td>
              <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ year.semester }}</td>
              <td class="px-6 py-4">
                <span v-if="year.is_active" class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">
                  <CheckCircle2 :size="14" /> Active
                </span>
                <span v-else class="text-xs font-medium text-slate-400">Inactive</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link :href="route('academic-years.edit', year.id)" class="p-2 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg">
                    <Pencil :size="18" />
                  </Link>
                  <button @click="deleteYear(year.id)" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg">
                    <Trash2 :size="18" />
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="academicYears.data.length === 0">
              <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">No academic years found.</td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
