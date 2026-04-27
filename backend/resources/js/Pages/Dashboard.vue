<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import { Head } from '@inertiajs/vue3';
import { 
  TrendingUp, 
  TrendingDown, 
  ArrowUpRight,
  MoreVertical,
  CalendarDays,
  UserCheck,
  GraduationCap,
  Briefcase
} from 'lucide-vue-next';

defineProps({
  stats: Array,
});

const quickActions = [
  { name: 'Add New Student', icon: UserCheck, color: 'bg-emerald-500', shadow: 'shadow-emerald-200' },
  { name: 'Schedule Exam', icon: CalendarDays, color: 'bg-blue-500', shadow: 'shadow-blue-200' },
  { name: 'Assign Teacher', icon: Briefcase, color: 'bg-purple-500', shadow: 'shadow-purple-200' },
  { name: 'Generate Report', icon: GraduationCap, color: 'bg-orange-500', shadow: 'shadow-orange-200' },
];

const recentActivities = [
  { user: 'Budi Santoso', action: 'enrolled in Class 10-A', time: '2 minutes ago', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Budi' },
  { user: 'Siti Aminah', action: 'submitted mathematics assignment', time: '15 minutes ago', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Siti' },
  { user: 'Dr. Ahmad', action: 'marked attendance for Class 12-C', time: '1 hour ago', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Ahmad' },
  { user: 'Finance Dept', action: 'updated SPP payment for 45 students', time: '3 hours ago', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Finance' },
];
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Welcome Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight" v-if="$page.props.auth.user">
            Good morning, {{ $page.props.auth.user.name.split(' ')[0] }}! 👋
          </h1>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight" v-else>
            Good morning! 👋
          </h1>
          <p class="text-slate-500 mt-1">Here's what's happening in your school today.</p>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
            Download Report
          </button>
          <button class="px-5 py-2.5 bg-indigo-600 rounded-2xl text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
            Create New
          </button>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card v-for="stat in stats" :key="stat.name" class="p-6 overflow-hidden relative group cursor-pointer">
          <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <TrendingUp v-if="stat.trend === 'up'" :size="64" class="text-emerald-500" />
            <TrendingDown v-else :size="64" class="text-rose-500" />
          </div>
          <div class="space-y-3">
            <p class="text-sm font-medium text-slate-500">{{ stat.name }}</p>
            <div class="flex items-end justify-between">
              <h3 class="text-2xl font-bold text-slate-900">{{ stat.value }}</h3>
              <div 
                :class="[
                  'flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full',
                  stat.trend === 'up' ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50'
                ]"
              >
                {{ stat.change }}
                <ArrowUpRight v-if="stat.trend === 'up'" :size="12" />
                <TrendingDown v-else :size="12" />
              </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full mt-4 overflow-hidden">
                <div 
                    :class="['h-full rounded-full transition-all duration-1000', stat.trend === 'up' ? 'bg-emerald-500' : 'bg-rose-500']"
                    :style="{ width: '70%' }"
                ></div>
            </div>
          </div>
        </Card>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions & Recent -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Quick Actions -->
          <div>
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold text-slate-900">Quick Actions</h2>
              <button class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">View all</button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <button 
                v-for="action in quickActions" 
                :key="action.name"
                class="flex flex-col items-center gap-3 p-6 bg-white border border-slate-100 rounded-3xl hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group shadow-sm"
              >
                <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg transition-transform group-hover:scale-110', action.color, action.shadow]">
                  <component :is="action.icon" :size="24" />
                </div>
                <span class="text-xs font-bold text-slate-600 text-center">{{ action.name }}</span>
              </button>
            </div>
          </div>

          <!-- Chart Area Placeholder -->
          <Card class="p-8">
            <div class="flex items-center justify-between mb-8">
              <div>
                <h2 class="text-xl font-bold text-slate-900">Student Performance</h2>
                <p class="text-sm text-slate-500 mt-1">Academic trends across all departments</p>
              </div>
              <select class="bg-slate-50 border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:ring-indigo-500 focus:border-indigo-500">
                <option>Last 6 months</option>
                <option>Last year</option>
              </select>
            </div>
            <div class="h-64 flex items-end justify-between gap-2 px-4">
                <div v-for="i in 12" :key="i" class="flex-1 bg-indigo-100 rounded-t-xl hover:bg-indigo-600 transition-all cursor-pointer relative group" :style="{ height: Math.random() * 80 + 20 + '%' }">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                        Score: {{ Math.floor(Math.random() * 20 + 80) }}
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-4 px-2">
                <span v-for="m in ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']" :key="m" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ m }}</span>
            </div>
          </Card>
        </div>

        <!-- Sidebar Components -->
        <div class="space-y-8">
          <!-- Recent Activity -->
          <Card class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-slate-900">Recent Activity</h2>
              <button class="p-1.5 rounded-lg hover:bg-slate-50 text-slate-400"><MoreVertical :size="20" /></button>
            </div>
            <div class="space-y-6">
              <div v-for="(activity, index) in recentActivities" :key="index" class="flex gap-4 relative">
                <div v-if="index !== recentActivities.length - 1" class="absolute left-5 top-10 bottom-[-24px] w-0.5 bg-slate-100"></div>
                <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm flex-shrink-0 z-10 overflow-hidden bg-slate-100">
                  <img :src="activity.avatar" :alt="activity.user" />
                </div>
                <div class="flex-1">
                  <p class="text-sm text-slate-900">
                    <span class="font-bold">{{ activity.user }}</span> {{ activity.action }}
                  </p>
                  <p class="text-xs text-slate-400 mt-1 font-medium">{{ activity.time }}</p>
                </div>
              </div>
            </div>
            <button class="w-full mt-8 py-3 rounded-2xl bg-slate-50 text-slate-600 text-sm font-bold hover:bg-slate-100 transition-all">
              View All Logs
            </button>
          </Card>

          <!-- Announcements Card -->
          <Card class="p-6 bg-gradient-to-br from-indigo-600 to-violet-700 text-white border-none shadow-indigo-200">
            <h2 class="text-lg font-bold mb-2">School Announcement</h2>
            <p class="text-indigo-100 text-sm leading-relaxed mb-6">
              Annual sports meeting scheduled for next Monday. All department heads must submit participant lists by Friday.
            </p>
            <div class="flex items-center justify-between">
              <div class="flex -space-x-2">
                <img v-for="i in 3" :key="i" :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=Teacher${i}`" class="w-8 h-8 rounded-full border-2 border-indigo-500" />
                <div class="w-8 h-8 rounded-full bg-indigo-400 border-2 border-indigo-500 flex items-center justify-center text-[10px] font-bold">+12</div>
              </div>
              <button class="text-xs font-bold bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl transition-all">Details</button>
            </div>
          </Card>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
/* Custom scrollbar for premium look */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: #E2E8F0;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #CBD5E1;
}

body {
  font-family: 'Outfit', 'Inter', sans-serif;
}
</style>
