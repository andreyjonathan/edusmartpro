<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubjectForm from '@/Components/SubjectForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';

const props = defineProps({
  subject: Object,
});
</script>

<template>
  <Head :title="`Edit Subject: ${subject.name}`" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <div class="flex flex-col gap-4">
        <Link 
            :href="route('subjects.index')" 
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors w-fit"
        >
            <ChevronLeft :size="18" />
            Back to List
        </Link>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Subject</h1>
        <p class="text-slate-500">Modify the subject's name, code, or curriculum details.</p>
      </div>

      <SubjectForm :subject="subject" :is-edit="true" />
    </div>
  </AuthenticatedLayout>
</template>
