<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { 
  GraduationCap, 
  Mail, 
  Lock, 
  ArrowRight,
  Loader2,
  Eye,
  EyeOff
} from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Sign In" />

  <div class="min-h-screen flex bg-[#F8FAFC]">
    <!-- Left: Login Form -->
    <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-32">
      <div class="mx-auto w-full max-w-sm lg:w-96">
        <div class="flex items-center gap-3 mb-12">
          <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
            <GraduationCap :size="28" />
          </div>
          <span class="text-2xl font-bold text-slate-800 tracking-tight">EduSmart <span class="text-indigo-600">Pro</span></span>
        </div>

        <div>
          <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome back!</h2>
          <p class="mt-3 text-slate-500 font-medium">Please sign in to access your school dashboard.</p>
        </div>

        <div class="mt-10">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Email -->
            <div class="space-y-2">
              <label for="email" class="text-sm font-semibold text-slate-700 ml-1">Email Address</label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                  <Mail :size="20" />
                </div>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="admin@edusmart.com"
                  class="block w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
              </div>
              <p v-if="form.errors.email" class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- Password -->
            <div class="space-y-2">
              <div class="flex items-center justify-between ml-1">
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Forgot password?</a>
              </div>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                  <Lock :size="20" />
                </div>
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  placeholder="••••••••"
                  class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
                <button 
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                >
                  <Eye v-if="!showPassword" :size="20" />
                  <EyeOff v-else :size="20" />
                </button>
              </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center ml-1">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded-lg cursor-pointer"
              />
              <label for="remember" class="ml-3 block text-sm font-semibold text-slate-600 cursor-pointer">
                Keep me signed in
              </label>
            </div>

            <!-- Submit -->
            <button
              type="submit"
              :disabled="form.processing"
              class="relative w-full flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98] transition-all disabled:opacity-70 disabled:pointer-events-none shadow-xl shadow-indigo-100"
            >
              <template v-if="form.processing">
                <Loader2 class="animate-spin" :size="20" />
                <span>Verifying...</span>
              </template>
              <template v-else>
                <span>Sign In to Dashboard</span>
                <ArrowRight :size="18" />
              </template>
            </button>
          </form>

          <p class="mt-10 text-center text-sm text-slate-500">
            Not an administrator? 
            <a href="#" class="font-bold text-indigo-600 hover:text-indigo-700">Contact Support</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Right: Brand Section -->
    <div class="hidden lg:block relative flex-1 bg-indigo-600 overflow-hidden">
      <!-- Decorative Elements -->
      <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800"></div>
      <div class="absolute top-0 left-0 w-full h-full opacity-10">
          <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-white rounded-full blur-[120px]"></div>
          <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-indigo-400 rounded-full blur-[150px]"></div>
      </div>
      
      <div class="relative h-full flex flex-col justify-center px-12 xl:px-24">
        <div class="space-y-8">
            
            <h1 class="text-5xl xl:text-6xl font-bold text-white leading-tight">
                The most advanced <br/>
                <span class="text-indigo-200">School Management</span> <br/>
                platform.
            </h1>
            <p class="text-lg text-indigo-100 max-w-xl leading-relaxed">
                Empowering education through data-driven decisions. Manage students, teachers, and curriculum with unprecedented ease and beauty.
            </p>
            
            <!-- Floating Card UI Mockup -->
            
        </div>
        
        <div class="absolute bottom-12 left-12 xl:left-24">
            <div class="flex items-center gap-4 text-white/60 text-sm font-medium">
                <span>Terms of Service</span>
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span>Privacy Policy</span>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
input::placeholder {
  color: #94A3B8;
  font-weight: 500;
}
</style>
