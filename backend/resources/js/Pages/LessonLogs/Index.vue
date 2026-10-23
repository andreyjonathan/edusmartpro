<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, Plus, BookOpen, Pencil, Trash2, Calendar, User, School } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  lessonLogs: Object,
  teachers: Array,
  filters: Object,
});

const search = ref(props.filters.search || '');
const teacherId = ref(props.filters.teacher_id || '');

watch([search, teacherId], debounce(() => {
  router.get(route('lesson-logs.index'), { 
    search: search.value, 
    teacher_id: teacherId.value,
  }, { preserveState: true, replace: true });
}, 500));

const deleteLog = (id) => {
  if (confirm('Are you sure you want to delete this lesson log?')) {
    router.delete(route('lesson-logs.destroy', id));
  }
};
</script>

<template>
  <Head title="Lesson Logs" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Lesson Logs</h1>
          <p class="text-slate-500 mt-1">Track daily teaching activities and lesson progress.</p>
        </div>
        <Link 
          :href="route('lesson-logs.create')"
          class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
        >
          <Plus :size="18" />
          Add Lesson Log
        </Link>
      </div>

      <Card class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="relative flex-1 md:w-80 group">
            <Search :size="18" class="absolute left-4 top-3 text-slate-400 group-focus-within:text-indigo-500" />
            <input 
              v-model="search"
              type="text" 
              placeholder="Search topic or class..." 
              class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
            />
          </div>
          <select v-model="teacherId" class="w-full md:w-64 bg-slate-50 border border-slate-200 rounded-xl py-2 pl-4 pr-10 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">All Teachers</option>
            <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
          </select>
        </div>
      </Card>

      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Date</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Teacher</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Subject</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Class</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Topic</th>
              <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="log in lessonLogs.data" :key="log.id" class="hover:bg-slate-50/50">
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <Calendar :size="18" class="text-slate-400" />
                  <span class="text-sm font-medium text-slate-700">{{ new Date(log.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <User :size="18" class="text-indigo-600" />
                  <span class="text-sm font-medium text-slate-700">{{ log.teacher?.name || 'N/A' }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ log.subject?.name || 'N/A' }}</td>
              <td class="px-6 py-4 text-sm text-slate-500">{{ log.class_name }}</td>
              <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ log.topic }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link :href="route('lesson-logs.edit', log.id)" class="p-2 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg">
                    <Pencil :size="18" />
                  </Link>
                  <button @click="deleteLog(log.id)" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg">
                    <Trash2 :size="18" />
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="lessonLogs.data.length === 0">
              <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">No lesson logs found.</td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>