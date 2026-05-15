<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { UserPlus, Search, Pencil, Trash2, Mail, Shield, MoreHorizontal, UserCheck, X as CloseIcon, Loader2, Lock } from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const props = defineProps({
    users: Array,
    roles: Array,
});

const { t } = useLanguage();

const isModalOpen = ref(false);
const isEdit = ref(false);
const editingUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: [],
});

const openModal = (user = null) => {
    if (user) {
        isEdit.value = true;
        editingUser.value = user;
        form.name = user.name;
        form.email = user.email;
        form.password = '';
        form.roles = user.roles.map(r => r.name);
    } else {
        isEdit.value = false;
        editingUser.value = null;
        form.reset();
    }
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submit = () => {
    if (isEdit.value) {
        form.put(route('users.update', editingUser.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id));
    }
};

const getRoleColor = (role) => {
    switch (role) {
        case 'admin': return 'bg-rose-50 text-rose-700 border-rose-100';
        case 'teacher': return 'bg-blue-50 text-blue-700 border-blue-100';
        case 'student': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
        case 'parent': return 'bg-amber-50 text-amber-700 border-amber-100';
        default: return 'bg-slate-50 text-slate-700 border-slate-100';
    }
};
</script>

<template>
    <Head :title="t.users.title" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ t.users.title }}</h1>
                    <p class="text-slate-500 mt-1">{{ t.users.subtitle }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative hidden sm:block">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
                        <input 
                            type="text" 
                            placeholder="Search users..." 
                            class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm"
                        />
                    </div>
                    <button 
                        @click="openModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-100 group"
                    >
                        <UserPlus :size="18" class="group-hover:scale-110 transition-transform duration-300" />
                        {{ t.users.addUser }}
                    </button>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100 shadow-sm overflow-hidden">
                                            <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${user.name}`" alt="Avatar" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-none">{{ user.name }}</p>
                                            <p class="text-xs text-slate-400 mt-1">ID: #{{ user.id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Mail :size="14" class="text-slate-400" />
                                        <span class="text-sm">{{ user.email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span 
                                            v-for="role in user.roles" 
                                            :key="role.id"
                                            :class="['px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-tight border', getRoleColor(role.name)]"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold uppercase tracking-tight rounded-lg border border-emerald-100">
                                        <UserCheck :size="12" />
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            @click="openModal(user)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                        >
                                            <Pencil :size="18" />
                                        </button>
                                        <button 
                                            v-if="user.email !== 'admin@edusmart.com'" 
                                            @click="deleteUser(user.id)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                        >
                                            <Trash2 :size="18" />
                                        </button>
                                        <button class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 transition-all lg:hidden">
                                            <MoreHorizontal :size="18" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- User Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-100 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>
            <div class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-xl font-bold text-slate-900">{{ isEdit ? 'Edit User' : 'Create New User' }}</h2>
                    <button @click="closeModal" class="p-2 rounded-xl text-slate-400 hover:bg-white hover:text-slate-600 transition-all border border-transparent hover:border-slate-200">
                        <CloseIcon :size="20" />
                    </button>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 ml-1">Full Name</label>
                        <input 
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="John Doe"
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 ml-1">Email Address</label>
                        <div class="relative">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
                            <input 
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="john@example.com"
                                class="block w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.email }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 ml-1">Password</label>
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
                            <input 
                                v-model="form.password"
                                type="password"
                                :required="!isEdit"
                                :placeholder="isEdit ? 'Leave blank to keep current' : '••••••••'"
                                class="block w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                            />
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.password }}</p>
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="text-sm font-semibold text-slate-700 ml-1 block">Assign Roles</label>
                        <div class="flex flex-wrap gap-2 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <label 
                                v-for="role in roles" 
                                :key="role.id"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white transition-all cursor-pointer group bg-white border border-slate-100 shadow-sm"
                            >
                                <input 
                                    type="checkbox" 
                                    v-model="form.roles"
                                    :value="role.name"
                                    class="w-4 h-4 border-2 border-slate-300 rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <span class="text-sm text-slate-600 font-medium capitalize">{{ role.name }}</span>
                            </label>
                        </div>
                    </div>
                </form>

                <div class="p-6 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50">
                    <button 
                        @click="closeModal"
                        class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-100 disabled:opacity-70"
                    >
                        <Loader2 v-if="form.processing" :size="18" class="animate-spin" />
                        {{ isEdit ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
