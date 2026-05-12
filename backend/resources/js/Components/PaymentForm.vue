<script setup>
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  Save, 
  X, 
  DollarSign, 
  User, 
  Calendar,
  CreditCard,
  FileText,
  Tag
} from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';
import { watch } from 'vue';

const { t, lang } = useLanguage();

const props = defineProps({
  payment: {
    type: Object,
    default: () => ({})
  },
  students: Array,
  categories: Array,
  months: Array,
  isEdit: Boolean
});

const form = useForm({
  student_id: props.payment.student_id || '',
  payment_category_id: props.payment.payment_category_id || '',
  amount: props.payment.amount || 0,
  payment_date: props.payment.payment_date || new Date().toISOString().split('T')[0],
  payment_method: props.payment.payment_method || 'Cash',
  month: props.payment.month || '',
  year: props.payment.year || new Date().getFullYear(),
  status: props.payment.status || 'Paid',
  reference_number: props.payment.reference_number || '',
  notes: props.payment.notes || '',
});

// Update amount when category changes
watch(() => form.payment_category_id, (newVal) => {
  const category = props.categories.find(c => c.id === parseInt(newVal));
  if (category && !props.isEdit) {
    form.amount = category.amount;
  }
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('payments.update', props.payment.id));
  } else {
    form.post(route('payments.store'));
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="max-w-4xl mx-auto space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Info -->
      <div class="lg:col-span-2 space-y-6">
        <Card class="p-6">
          <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <DollarSign :size="20" class="text-indigo-600" />
            {{ lang === 'id' ? 'Detail Pembayaran' : 'Payment Details' }}
          </h3>
          
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                  <User :size="16" class="text-slate-400" />
                  {{ lang === 'id' ? 'Pilih Siswa' : 'Select Student' }}
                </label>
                <select 
                  v-model="form.student_id"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                  required
                >
                  <option value="" disabled>{{ lang === 'id' ? 'Pilih Siswa...' : 'Select Student...' }}</option>
                  <option v-for="student in students" :key="student.id" :value="student.id">
                    {{ student.name }} ({{ student.nis }})
                  </option>
                </select>
                <p v-if="form.errors.student_id" class="text-xs text-rose-500 font-medium">{{ form.errors.student_id }}</p>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                  <Tag :size="16" class="text-slate-400" />
                  {{ lang === 'id' ? 'Kategori Pembayaran' : 'Payment Category' }}
                </label>
                <select 
                  v-model="form.payment_category_id"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                  required
                >
                  <option value="" disabled>{{ lang === 'id' ? 'Pilih Kategori...' : 'Select Category...' }}</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
                <p v-if="form.errors.payment_category_id" class="text-xs text-rose-500 font-medium">{{ form.errors.payment_category_id }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">{{ lang === 'id' ? 'Jumlah (Rp)' : 'Amount (IDR)' }}</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                  <input 
                    v-model="form.amount"
                    type="number"
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                    placeholder="0"
                    required
                  />
                </div>
                <p v-if="form.errors.amount" class="text-xs text-rose-500 font-medium">{{ form.errors.amount }}</p>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                  <Calendar :size="16" class="text-slate-400" />
                  {{ lang === 'id' ? 'Tanggal Pembayaran' : 'Payment Date' }}
                </label>
                <input 
                  v-model="form.payment_date"
                  type="date"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none"
                  required
                />
                <p v-if="form.errors.payment_date" class="text-xs text-rose-500 font-medium">{{ form.errors.payment_date }}</p>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                <FileText :size="16" class="text-slate-400" />
                {{ lang === 'id' ? 'Catatan / Keterangan' : 'Notes / Remarks' }}
              </label>
              <textarea 
                v-model="form.notes"
                rows="3"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none resize-none"
                :placeholder="lang === 'id' ? 'Contoh: Pembayaran SPP lunas' : 'e.g. Full payment for SPP'"
              ></textarea>
            </div>
          </div>
        </Card>
      </div>

      <!-- Sidebar Settings -->
      <div class="space-y-6">
        <Card class="p-6">
          <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ lang === 'id' ? 'Metode & Status' : 'Method & Status' }}</h4>
          
          <div class="space-y-4">
            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase">{{ lang === 'id' ? 'Metode' : 'Method' }}</label>
              <select v-model="form.payment_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet (Dana/OVO)</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase">Status</label>
              <select v-model="form.status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                <option value="Paid">Paid</option>
                <option value="Pending">Pending</option>
                <option value="Failed">Failed</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase">Ref #</label>
              <input v-model="form.reference_number" type="text" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none" placeholder="REF-12345" />
            </div>
          </div>
        </Card>

        <Card class="p-6" v-if="form.payment_category_id">
          <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ lang === 'id' ? 'Periode (Untuk SPP)' : 'Period (For SPP)' }}</h4>
          <div class="grid grid-cols-1 gap-4">
            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase">{{ lang === 'id' ? 'Bulan' : 'Month' }}</label>
              <select v-model="form.month" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                <option value="">-- {{ lang === 'id' ? 'N/A' : 'Not Applicable' }} --</option>
                <option v-for="m in months" :key="m" :value="m">{{ m }}</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="text-xs font-bold text-slate-400 uppercase">{{ lang === 'id' ? 'Tahun' : 'Year' }}</label>
              <input v-model="form.year" type="number" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none" />
            </div>
          </div>
        </Card>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
          <button 
            type="submit" 
            :disabled="form.processing"
            class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
          >
            <Save :size="18" />
            {{ isEdit ? (lang === 'id' ? 'Simpan Perubahan' : 'Save Changes') : (lang === 'id' ? 'Catat Pembayaran' : 'Record Payment') }}
          </button>
          <Link 
            :href="route('payments.index')"
            class="w-full py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-all flex items-center justify-center gap-2"
          >
            <X :size="18" />
            {{ t.common.cancel }}
          </Link>
        </div>
      </div>
    </div>
  </form>
</template>
