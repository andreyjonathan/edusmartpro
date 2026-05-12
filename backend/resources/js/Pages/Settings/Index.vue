<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { 
  Settings, 
  School, 
  BookOpen, 
  AppWindow, 
  Save, 
  Globe, 
  Mail, 
  Phone, 
  MapPin, 
  User,
  Calendar
} from 'lucide-vue-next';
import { computed } from 'vue';
import { useLanguage } from '@/composables/useLanguage';

const { t, lang } = useLanguage();

const props = defineProps({
  settings: Object,
  tab: String
});

const activeTab = computed(() => props.tab || 'school');

// Initialize form with all settings from all groups
const initialData = {};
Object.keys(props.settings).forEach(group => {
  Object.keys(props.settings[group]).forEach(key => {
    initialData[key] = props.settings[group][key];
  });
});

const form = useForm(initialData);

const submit = () => {
  form.put(route('settings.update'), {
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Settings" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
            {{ lang === 'id' ? 'Pengaturan' : 'Settings' }}
          </h1>
          <p class="text-slate-500 mt-1">
            {{ lang === 'id' ? 'Kelola konfigurasi sekolah dan aplikasi Anda' : 'Manage your school and application configuration' }}
          </p>
        </div>
      </div>

      <div class="flex flex-col gap-6">
        <!-- Settings Content -->
        <div class="space-y-6">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- School Profile Tab -->
            <Card v-if="activeTab === 'school'" class="p-8 space-y-8 animate-in fade-in zoom-in-95 duration-500 border-none shadow-xl shadow-slate-200/50">
              <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                  <School :size="24" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-slate-900">{{ lang === 'id' ? 'Informasi Sekolah' : 'School Information' }}</h3>
                  <p class="text-sm text-slate-500">{{ lang === 'id' ? 'Detail utama institusi Anda' : 'Primary details of your institution' }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <Globe :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Nama Sekolah' : 'School Name' }}
                  </label>
                  <input v-model="form.school_name" type="text" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none" />
                </div>

                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <MapPin :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Alamat' : 'Address' }}
                  </label>
                  <textarea v-model="form.school_address" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none resize-none"></textarea>
                </div>

                <div class="space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <Phone :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Telepon' : 'Phone' }}
                  </label>
                  <input v-model="form.school_phone" type="text" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none" />
                </div>

                <div class="space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <Mail :size="16" class="text-indigo-500" />
                    Email
                  </label>
                  <input v-model="form.school_email" type="email" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none" />
                </div>

                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <User :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Kepala Sekolah' : 'Principal Name' }}
                  </label>
                  <input v-model="form.school_principal" type="text" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none" />
                </div>
              </div>
            </Card>

            <!-- Academic Tab -->
            <Card v-if="activeTab === 'academic'" class="p-8 space-y-8 animate-in fade-in zoom-in-95 duration-500 border-none shadow-xl shadow-slate-200/50">
              <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                  <BookOpen :size="24" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-slate-900">{{ lang === 'id' ? 'Pengaturan Akademik' : 'Academic Settings' }}</h3>
                  <p class="text-sm text-slate-500">{{ lang === 'id' ? 'Kelola periode belajar aktif' : 'Manage active learning periods' }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <Calendar :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Tahun Ajaran' : 'Academic Year' }}
                  </label>
                  <input v-model="form.academic_year" type="text" placeholder="2023/2024" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none" />
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-bold text-slate-700 flex items-center gap-2 ml-1">
                    <Clock :size="16" class="text-indigo-500" />
                    {{ lang === 'id' ? 'Semester' : 'Semester' }}
                  </label>
                  <select v-model="form.semester" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none appearance-none cursor-pointer">
                    <option value="Ganjil">Ganjil (Odd)</option>
                    <option value="Genap">Genap (Even)</option>
                  </select>
                </div>
              </div>
            </Card>

            <!-- App Settings Tab -->
            <Card v-if="activeTab === 'app'" class="p-8 space-y-8 animate-in fade-in zoom-in-95 duration-500 border-none shadow-xl shadow-slate-200/50">
              <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                  <AppWindow :size="24" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-slate-900">{{ lang === 'id' ? 'Pengaturan Aplikasi' : 'App Settings' }}</h3>
                  <p class="text-sm text-slate-500">{{ lang === 'id' ? 'Konfigurasi sistem umum' : 'General system configuration' }}</p>
                </div>
              </div>

              <div class="space-y-6">
                <div class="flex items-center justify-between p-8 bg-slate-50/50 border border-slate-100 rounded-3xl group hover:bg-white hover:border-indigo-100 transition-all">
                  <div>
                    <h4 class="text-base font-bold text-slate-900">{{ lang === 'id' ? 'Item per Halaman' : 'Items per Page' }}</h4>
                    <p class="text-sm text-slate-500 mt-1">{{ lang === 'id' ? 'Jumlah data yang ditampilkan per halaman' : 'Number of records shown per page' }}</p>
                  </div>
                  <input v-model="form.items_per_page" type="number" class="w-28 px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-center outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" />
                </div>

                <div class="flex items-center justify-between p-8 bg-slate-50/50 border border-slate-100 rounded-3xl group hover:bg-white hover:border-indigo-100 transition-all">
                  <div>
                    <h4 class="text-base font-bold text-slate-900">{{ lang === 'id' ? 'Pendaftaran Mandiri' : 'Self Registration' }}</h4>
                    <p class="text-sm text-slate-500 mt-1">{{ lang === 'id' ? 'Izinkan siswa mendaftar akun sendiri' : 'Allow students to register their own accounts' }}</p>
                  </div>
                  <button 
                    type="button"
                    @click="form.enable_registration = form.enable_registration === 'true' ? 'false' : 'true'"
                    :class="[
                      'w-16 h-8 rounded-full transition-all relative flex items-center px-1.5',
                      form.enable_registration === 'true' ? 'bg-indigo-600' : 'bg-slate-300'
                    ]"
                  >
                    <div :class="['w-5 h-5 bg-white rounded-full transition-all shadow-md', form.enable_registration === 'true' ? 'translate-x-8' : 'translate-x-0']"></div>
                  </button>
                </div>
              </div>
            </Card>

            <!-- Save Button -->
            <div class="flex items-center justify-between p-6 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/80 sticky bottom-8 z-10">
              <div class="hidden md:block pl-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ lang === 'id' ? 'Pastikan data benar' : 'Ensure data is correct' }}</p>
              </div>
              <button 
                type="submit" 
                :disabled="form.processing"
                class="w-full md:w-auto px-12 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3 disabled:opacity-50"
              >
                <Save :size="20" />
                {{ lang === 'id' ? 'Simpan Pengaturan' : 'Save Settings' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
