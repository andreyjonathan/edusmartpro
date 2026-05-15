<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Shield, ShieldCheck, Plus, Pencil, Trash2, Key, CheckCircle2, X as CloseIcon, Loader2 } from 'lucide-vue-next';
import { useLanguage } from '@/composables/useLanguage';

const props = defineProps({
    roles: Array,
    permissions: Array,
});

const { t } = useLanguage();

const isModalOpen = ref(false);
const isEdit = ref(false);
const editingRole = ref(null);

const form = useForm({
    name: '',
    permissions: [],
});

const openModal = (role = null) => {
    if (role) {
        isEdit.value = true;
        editingRole.value = role;
        form.name = role.name;
        form.permissions = role.permissions.map(p => p.name);
    } else {
        isEdit.value = false;
        editingRole.value = null;
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
        form.put(route('roles.update', editingRole.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('roles.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteRole = (id) => {
    if (confirm('Are you sure you want to delete this role?')) {
        router.delete(route('roles.destroy', id));
    }
};
</script>

<template>
    <Head :title="t.users.rolesTitle" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ t.users.rolesTitle }}</h1>
                    <p class="text-slate-500 mt-1">{{ t.users.rolesSubtitle }}</p>
                </div>
                <button 
                    @click="openModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-100 group"
                >
                    <Plus :size="18" class="group-hover:rotate-90 transition-transform duration-300" />
                    {{ t.common.add || 'Add Role' }}
                </button>
            </div>

            <!-- Roles Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div v-for="role in roles" :key="role.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all group">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                    <ShieldCheck v-if="role.name === 'admin'" :size="28" />
                                    <Shield v-else :size="28" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 capitalize">{{ role.name }}</h3>
                                    <span class="text-sm font-medium text-slate-400 uppercase tracking-wider">{{ role.permissions.length }} Permissions</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button 
                                    @click="openModal(role)"
                                    class="p-2.5 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                >
                                    <Pencil :size="18" />
                                </button>
                                <button 
                                    v-if="!['admin', 'teacher', 'student', 'parent'].includes(role.name)" 
                                    @click="deleteRole(role.id)"
                                    class="p-2.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                >
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </div>

                        <!-- Permissions Preview -->
                        <div class="mt-8">
                            <h4 class="text-sm font-semibold text-slate-900 flex items-center gap-2 mb-4">
                                <Key :size="16" class="text-indigo-500" />
                                Permissions
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <span 
                                    v-for="permission in role.permissions" 
                                    :key="permission.id"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-600 text-xs font-medium rounded-lg border border-slate-100 hover:bg-white hover:border-indigo-200 hover:text-indigo-600 transition-all"
                                >
                                    <CheckCircle2 :size="12" class="text-indigo-400" />
                                    {{ permission.name }}
                                </span>
                                <span v-if="role.name === 'admin'" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100 italic">
                                    All System Permissions (Super Admin)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-100 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>
            <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-xl font-bold text-slate-900">{{ isEdit ? 'Edit Role' : 'Create New Role' }}</h2>
                    <button @click="closeModal" class="p-2 rounded-xl text-slate-400 hover:bg-white hover:text-slate-600 transition-all border border-transparent hover:border-slate-200">
                        <CloseIcon :size="20" />
                    </button>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 ml-1">Role Name</label>
                        <input 
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="e.g. Moderator"
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-700 ml-1 block">Permissions</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <label 
                                v-for="permission in permissions" 
                                :key="permission.id"
                                class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-all cursor-pointer group"
                            >
                                <div class="relative flex items-center justify-center">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.permissions"
                                        :value="permission.name"
                                        class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded-lg checked:bg-indigo-600 checked:border-indigo-600 transition-all"
                                    />
                                    <CheckCircle2 :size="12" class="absolute text-white opacity-0 peer-checked:opacity-100 transition-opacity" />
                                </div>
                                <span class="text-sm text-slate-600 group-hover:text-indigo-600 font-medium transition-colors">{{ permission.name }}</span>
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
                        {{ isEdit ? 'Update Role' : 'Create Role' }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
