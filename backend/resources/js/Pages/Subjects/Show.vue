<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  ChevronLeft, 
  Pencil, 
  BookOpen, 
  Hash,
  Layers,
  Calendar,
  FileText
} from 'lucide-vue-next';

const props = defineProps({
  subject: Object,
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
  <Head :title="`Subject Details: ${subject.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col gap-4">
          <Link 
              :href="route('subjects.index')" 
              class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
          >
              <ChevronLeft :size="18" />
              Back to List
          </Link>
          <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-3xl bg-indigo-50 flex items-center justify-center text-indigo-600 border-4 border-white shadow-xl">
                <BookOpen :size="40" />
            </div>
            <div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ subject.name }}</h1>
              <div class="flex items-center gap-3 mt-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100 uppercase">
                    {{ subject.short_name }}
                </div>
                <div 
                    :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border',
                        subject.group === 'Core' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                        subject.group === 'Elective' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 
                        'bg-amber-50 text-amber-700 border-amber-100'
                    ]"
                  >
                    {{ subject.group }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <Link 
            :href="route('subjects.edit', subject.id)"
            class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2 h-fit"
        >
            <Pencil :size="18" />
            Edit Subject
        </Link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <Card class="p-6 space-y-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Academic Metadata</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                            <Hash :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Subject Code</div>
                            <div class="text-sm font-bold text-slate-700">{{ subject.code }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                            <Layers :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Group / Category</div>
                            <div class="text-sm font-bold text-slate-700">{{ subject.group }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                            <Calendar :size="18" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Added On</div>
                            <div class="text-sm font-bold text-slate-700">{{ formatDate(subject.created_at) }}</div>
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
                        <FileText :size="20" class="text-indigo-600" />
                        Subject Description & Curriculum
                    </h3>
                    <p class="text-slate-600 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100 min-h-[160px]">
                        {{ subject.description || 'No detailed description provided for this subject.' }}
                    </p>
                </div>
            </Card>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
