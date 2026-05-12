<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PaymentForm from '@/Components/PaymentForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

defineProps({
  students: Array,
  categories: Array,
  months: Array,
});
</script>

<template>
  <Head :title="lang === 'id' ? 'Catat Pembayaran Baru' : 'Record New Payment'" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <div class="flex items-center gap-4">
        <Link 
          :href="route('payments.index')"
          class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 transition-all"
        >
          <ChevronLeft :size="20" />
        </Link>
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Catat Pembayaran Baru' : 'Record New Payment' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Masukkan detail transaksi keuangan siswa di bawah ini' : 'Enter student financial transaction details below' }}
          </p>
        </div>
      </div>

      <PaymentForm 
        :students="students" 
        :categories="categories" 
        :months="months" 
      />
    </div>
  </AuthenticatedLayout>
</template>
