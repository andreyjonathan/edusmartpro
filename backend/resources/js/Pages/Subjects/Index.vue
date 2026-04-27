<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Search, 
  Filter, 
  Download,
  Plus,
  BookOpen,
  Pencil,
  Trash2,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  subjects: Object,
  filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
  router.get(route('subjects.index'), { search: value }, { preserveState: true, replace: true });
}, 500));

const deleteSubject = (id) => {
  if (confirm('Are you sure you want to delete this subject?')) {
    router.delete(route('subjects.destroy', id));
  }
};
</script>

<template>
  <Head title="Subjects Management" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Academic Subjects</h1>
          <p class="text-slate-500 mt-1">Manage the curriculum and available courses in your institution.</p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <Download :size="18" />
            Export
          </button>
          <Link 
            :href="route('subjects.create')"
            class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
          >
            <Plus :size="18" />
            Add New Subject
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
              placeholder="Search by subject name or code..." 
              class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>
          <div class="flex items-center gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none px-4 py-2.5 bg-white border border-slate-100 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
              <Filter :size="18" />
              Filters
            </button>
          </div>
        </div>
      </Card>

      <!-- Table Section -->
      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subject Name</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Short Name</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Group</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="subject in subjects.data" :key="subject.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 shadow-sm">
                      <BookOpen :size="20" />
                    </div>
                    <div class="text-sm font-bold text-slate-900">{{ subject.name }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ subject.short_name }}</td>
                <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ subject.code }}</td>
                <td class="px-6 py-4">
                  <div 
                    :class="[
                        'inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border',
                        subject.group === 'Core' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                        subject.group === 'Elective' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 
                        'bg-amber-50 text-amber-700 border-amber-100'
                    ]"
                  >
                    {{ subject.group }}
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">{{ subject.description || '-' }}</td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link 
                        :href="route('subjects.edit', subject.id)"
                        class="p-2 rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-all"
                    >
                        <Pencil :size="18" />
                    </Link>
                    <button 
                        @click="deleteSubject(subject.id)"
                        class="p-2 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-all"
                    >
                        <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="subjects.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">
                    No subjects found matching your search.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
          <p class="text-xs font-medium text-slate-500">
            Showing {{ subjects.from || 0 }} to {{ subjects.to || 0 }} of {{ subjects.total }} subjects
          </p>
          <div class="flex items-center gap-2">
            <Link 
                v-for="(link, k) in subjects.links" 
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
