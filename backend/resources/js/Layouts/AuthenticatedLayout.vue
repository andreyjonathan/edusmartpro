<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import { 
  LayoutDashboard, 
  Users, 
  GraduationCap, 
  Calendar, 
  Settings, 
  LogOut,
  Menu,
  X,
  Bell,
  Search,
  BookOpen,
  ClipboardCheck,
  CreditCard
} from 'lucide-vue-next';

const isSidebarOpen = ref(true);

const navItems = [
  { name: 'Dashboard', icon: LayoutDashboard, href: route('dashboard'), active: route().current('dashboard') },
  { name: 'Students', icon: Users, href: route('students.index'), active: route().current('students.*') },
  { name: 'Teachers', icon: GraduationCap, href: route('teachers.index'), active: route().current('teachers.*') },
  { name: 'Employees', icon: Users, href: route('employees.index'), active: route().current('employees.*') },
  { name: 'Subjects', icon: BookOpen, href: route('subjects.index'), active: route().current('subjects.*') },
  { name: 'Attendance', icon: ClipboardCheck, href: route('attendances.index'), active: route().current('attendances.*') },
  { name: 'Schedule', icon: Calendar, href: '#', active: false },
  { name: 'Payments', icon: CreditCard, href: '#', active: false },
  { name: 'Settings', icon: Settings, href: '#', active: false },
];
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFC]">
    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="p-6 flex items-center gap-3">
          <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
            <GraduationCap :size="24" />
          </div>
          <span class="text-xl font-bold text-slate-800 tracking-tight">EduSmart <span class="text-indigo-600">Pro</span></span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1">
          <div v-for="item in navItems" :key="item.name">
            <Link
              :href="item.href"
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group',
                item.active 
                  ? 'bg-indigo-50 text-indigo-700 font-semibold' 
                  : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
              ]"
            >
              <component 
                :is="item.icon" 
                :size="20" 
                :class="item.active ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" 
              />
              {{ item.name }}
            </Link>
          </div>
        </nav>

        <!-- Profile / Logout -->
        <div class="p-4 border-t border-slate-100">
          <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-2xl mb-2" v-if="$page.props.auth.user">
            <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-white overflow-hidden shadow-sm">
              <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${$page.props.auth.user.name}`" alt="Avatar" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-900 truncate">{{ $page.props.auth.user.name }}</p>
              <p class="text-xs text-slate-500 truncate">{{ $page.props.auth.user.email }}</p>
            </div>
          </div>
          <Link
            href="/logout"
            method="post"
            as="button"
            class="flex items-center gap-3 w-full px-4 py-3 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200"
          >
            <LogOut :size="20" />
            <span class="font-medium text-sm">Sign Out</span>
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div :class="['transition-all duration-300', isSidebarOpen ? 'lg:pl-72' : '']">
      <!-- Header -->
      <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button 
            @click="isSidebarOpen = !isSidebarOpen"
            class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden"
          >
            <Menu v-if="!isSidebarOpen" :size="24" />
            <X v-else :size="24" />
          </button>
          
          <div class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2 w-96 border border-transparent focus-within:border-indigo-300 focus-within:bg-white transition-all shadow-sm">
            <Search :size="18" class="text-slate-400" />
            <input 
              type="text" 
              placeholder="Search students, classes, records..." 
              class="bg-transparent border-none focus:ring-0 text-sm w-full ml-2 text-slate-700"
            />
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button class="p-2.5 rounded-full text-slate-500 hover:bg-slate-100 relative">
            <Bell :size="22" />
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
          </button>
          <div class="h-8 w-px bg-slate-200 mx-2"></div>
          <button class="flex items-center gap-2 pl-2 pr-1 py-1 rounded-full hover:bg-slate-100 transition-all border border-slate-100">
            <span class="text-sm font-medium text-slate-700 px-2">Academic Year 2023/2024</span>
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">AY</div>
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 sm:p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>
