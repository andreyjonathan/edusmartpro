<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronLeft, Calendar, Check, Loader2 } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  academicYear: Object,
});

const form = useForm({
  name: props.academicYear.name,
  semester: props.academicYear.semester,
  is_active: props.academicYear.is_active,
});

const submit = () => {
  form.put(route('academic-years.update', props.academicYear.id));
};
</script>

<template>
  <Head title="Edit Academic Year" />

  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
          :href="route('academic-years.index')" 
          class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 w-fit"
        >
          <ChevronLeft :size="18" />
          Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Academic Year</h1>
        <p class="text-slate-500">Update school year and semester details.</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <Card class="p-6 space-y-6">
          <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Calendar :size="20" class="text-indigo-600" />
            Year Details
          </h3>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 block">Year Name <span class="text-rose-500">*</span></label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
            />
            <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold">{{ form.errors.name }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 block">Semester <span class="text-rose-500">*</span></label>
            <select
              v-model="form.semester"
              required
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none"
            >
              <option value="Ganjil">Ganjil (Odd)</option>
              <option value="Genap">Genap (Even)</option>
            </select>
            <p v-if="form.errors.semester" class="text-xs text-rose-500 font-bold">{{ form.errors.semester }}</p>
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                type="checkbox"
                v-model="form.is_active"
                class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
              />
              <span class="text-sm font-semibold text-slate-700">Set as active academic year</span>
            </label>
          </div>
        </Card>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <Link 
            :href="route('academic-years.index')"
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
            Update Academic Year
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>