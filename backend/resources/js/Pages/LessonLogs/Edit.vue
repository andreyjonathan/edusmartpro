<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, BookOpen, Check, Loader2 } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  lessonLog: Object,
  teachers: Array,
  subjects: Array,
});

const form = useForm({
  teacher_id: props.lessonLog.teacher_id,
  subject_id: props.lessonLog.subject_id,
  class_name: props.lessonLog.class_name,
  date: props.lessonLog.date,
  topic: props.lessonLog.topic,
  notes: props.lessonLog.notes || '',
});

const submit = () => {
  form.put(route('lesson-logs.update', props.lessonLog.id));
};
</script>

<template>
  <Head title="Edit Lesson Log" />

  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
          :href="route('lesson-logs.index')" 
          class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 w-fit"
        >
          <ChevronLeft :size="18" />
          Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Lesson Log</h1>
        <p class="text-slate-500">Update teaching activity details.</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <Card class="p-6 space-y-6">
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <BookOpen :size="20" class="text-indigo-600" />
            Session Details
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Teacher <span class="text-rose-500">*</span></label>
              <select
                v-model="form.teacher_id"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              >
                <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Subject <span class="text-rose-500">*</span></label>
              <select
                v-model="form.subject_id"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              >
                <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Class Name <span class="text-rose-500">*</span></label>
              <input
                v-model="form.class_name"
                type="text"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 block">Date <span class="text-rose-500">*</span></label>
              <input
                v-model="form.date"
                type="date"
                required
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
              />
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 block">Topic <span class="text-rose-500">*</span></label>
            <input
              v-model="form.topic"
              type="text"
              required
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 block">Notes / Summary</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"
            ></textarea>
          </div>
        </Card>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <Link 
            :href="route('lesson-logs.index')"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50"
          >
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 disabled:opacity-70"
          >
            <Loader2 v-if="form.processing" class="animate-spin" :size="18" />
            <Check v-else :size="18" />
            Update Lesson Log
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>