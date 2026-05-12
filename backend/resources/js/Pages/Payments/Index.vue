<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
  Search, 
  Plus, 
  Download,
  CreditCard,
  CheckCircle2,
  Clock,
  XCircle,
  Pencil,
  Trash2,
  Filter,
  DollarSign,
  TrendingUp,
  Users
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  payments: Object,
  filters: Object,
  categories: Array,
  months: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const categoryId = ref(props.filters.category_id || '');
const selectedMonth = ref(props.filters.month || '');

watch([search, status, categoryId, selectedMonth], debounce(() => {
  router.get(route('payments.index'), { 
    search: search.value, 
    status: status.value, 
    category_id: categoryId.value,
    month: selectedMonth.value
  }, { 
    preserveState: true, 
    replace: true 
  });
}, 500));

const deletePayment = (id) => {
  if (confirm(lang.value === 'id' ? 'Apakah Anda yakin ingin menghapus data pembayaran ini?' : 'Are you sure you want to delete this payment record?')) {
    router.delete(route('payments.destroy', id));
  }
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
};

const getStatusStyles = (status) => {
  switch (status) {
    case 'Paid': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    case 'Pending': return 'bg-amber-50 text-amber-700 border-amber-100';
    case 'Failed': return 'bg-rose-50 text-rose-700 border-rose-100';
    default: return 'bg-slate-50 text-slate-700 border-slate-100';
  }
};
</script>

<template>
  <Head :title="lang === 'id' ? 'Manajemen Pembayaran' : 'Payment Management'" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Manajemen Pembayaran' : 'Payment Management' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Kelola dan pantau semua transaksi keuangan siswa' : 'Manage and monitor all student financial transactions' }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <Download :size="18" />
            Export
          </button>
          <Link 
            :href="route('payments.create')"
            class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2"
          >
            <Plus :size="18" />
            {{ lang === 'id' ? 'Catat Pembayaran' : 'Record Payment' }}
          </Link>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card class="p-6 border-none shadow-xl shadow-slate-200/50 bg-gradient-to-br from-indigo-600 to-indigo-700 text-white">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-indigo-100 text-sm font-medium">{{ lang === 'id' ? 'Total Pendapatan' : 'Total Revenue' }}</p>
              <h3 class="text-2xl font-bold mt-2">{{ formatCurrency(payments.data.reduce((acc, p) => acc + parseFloat(p.amount), 0)) }}</h3>
              <p class="text-indigo-200 text-xs mt-2 flex items-center gap-1">
                <TrendingUp :size="12" />
                +12.5% from last month
              </p>
            </div>
            <div class="p-3 bg-white/10 rounded-xl">
              <DollarSign :size="24" />
            </div>
          </div>
        </Card>

        <Card class="p-6 border-none shadow-xl shadow-slate-200/50">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-slate-500 text-sm font-medium">{{ lang === 'id' ? 'Transaksi Bulan Ini' : 'Transactions This Month' }}</p>
              <h3 class="text-2xl font-bold mt-2 text-slate-900">{{ payments.total }}</h3>
              <p class="text-emerald-600 text-xs mt-2 flex items-center gap-1 font-medium">
                Active academic year
              </p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl text-indigo-600">
              <CreditCard :size="24" />
            </div>
          </div>
        </Card>

        <Card class="p-6 border-none shadow-xl shadow-slate-200/50">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-slate-500 text-sm font-medium">{{ lang === 'id' ? 'Siswa Terdaftar' : 'Registered Students' }}</p>
              <h3 class="text-2xl font-bold mt-2 text-slate-900">1,284</h3>
              <p class="text-slate-400 text-xs mt-2 flex items-center gap-1">
                Total active students
              </p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl text-indigo-600">
              <Users :size="24" />
            </div>
          </div>
        </Card>
      </div>

      <!-- Filters -->
      <Card class="p-4">
        <div class="flex flex-col lg:flex-row gap-4 items-center">
          <div class="relative flex-1 group w-full">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
            <input 
              v-model="search"
              type="text" 
              :placeholder="lang === 'id' ? 'Cari nama siswa, NIS, atau no. referensi...' : 'Search student, NIS, or ref...'" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
            />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full lg:w-auto">
            <select 
              v-model="categoryId"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Kategori' : 'All Categories' }}</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <select 
              v-model="selectedMonth"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Bulan' : 'All Months' }}</option>
              <option v-for="m in months" :key="m" :value="m">{{ m }}</option>
            </select>
            <select 
              v-model="status"
              class="bg-slate-50 border-none rounded-xl text-sm py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-indigo-500/20 transition-all"
            >
              <option value="">{{ lang === 'id' ? 'Semua Status' : 'All Status' }}</option>
              <option value="Paid">Paid</option>
              <option value="Pending">Pending</option>
              <option value="Failed">Failed</option>
            </select>
          </div>
        </div>
      </Card>

      <!-- Table -->
      <Card class="overflow-hidden border-none shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Siswa' : 'Student' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Kategori & Keterangan' : 'Category & Details' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Jumlah' : 'Amount' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Tanggal' : 'Date' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ lang === 'id' ? 'Status' : 'Status' }}</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">{{ lang === 'id' ? 'Aksi' : 'Actions' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="payment in payments.data" :key="payment.id" class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden border border-white shadow-sm">
                      <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${payment.student.name}`" :alt="payment.student.name" />
                    </div>
                    <div>
                      <div class="text-sm font-bold text-slate-900">{{ payment.student.name }}</div>
                      <div class="text-[10px] font-medium text-slate-400">NIS: {{ payment.student.nis }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-slate-700">{{ payment.category.name }}</span>
                    <span v-if="payment.month" class="text-[11px] text-slate-500">{{ payment.month }} {{ payment.year }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-bold text-slate-900">{{ formatCurrency(payment.amount) }}</div>
                  <div class="text-[10px] text-slate-400">{{ payment.payment_method }}</div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-600">
                  {{ new Date(payment.payment_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                </td>
                <td class="px-6 py-4">
                  <div 
                    :class="[
                      'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border',
                      getStatusStyles(payment.status)
                    ]"
                  >
                    <CheckCircle2 v-if="payment.status === 'Paid'" :size="12" />
                    <Clock v-if="payment.status === 'Pending'" :size="12" />
                    <XCircle v-if="payment.status === 'Failed'" :size="12" />
                    {{ payment.status }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Link 
                      :href="route('payments.edit', payment.id)"
                      class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                    >
                      <Pencil :size="18" />
                    </Link>
                    <button 
                      @click="deletePayment(payment.id)"
                      class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                    >
                      <Trash2 :size="18" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="payments.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                  <div class="flex flex-col items-center gap-3">
                    <CreditCard :size="48" class="text-slate-200" />
                    <p>{{ lang === 'id' ? 'Tidak ada data pembayaran.' : 'No payment records found.' }}</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="payments.total > 0" class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
          <p class="text-xs font-medium text-slate-500">
            {{ t.common.showing }} {{ payments.from || 0 }} {{ t.common.to }} {{ payments.to || 0 }} {{ t.common.of }} {{ payments.total }} {{ t.common.records }}
          </p>
          <div class="flex items-center gap-2">
            <Link 
              v-for="(link, k) in payments.links" 
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
