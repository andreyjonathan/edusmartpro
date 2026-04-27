<script setup>
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import { 
  BookOpen, 
  Hash, 
  Layers, 
  FileText,
  Check,
  Loader2,
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  subject: {
    type: Object,
    default: () => ({
      name: '',
      short_name: '',
      code: '',
      group: 'Core',
      description: '',
    })
  },
  isEdit: Boolean,
});

const form = useForm({
  name: props.subject.name,
  short_name: props.subject.short_name,
  code: props.subject.code,
  group: props.subject.group,
  description: props.subject.description,
});

const submit = () => {
  if (props.isEdit) {
    form.put(route('subjects.update', props.subject.id));
  } else {
    form.post(route('subjects.store'));
  }
};
</script>

<template>
  <form @submit.prevent="submit" class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Core Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <BookOpen :size="20" class="text-indigo-600" />
          Subject Details
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Subject Name</label>
            <div class="relative group">
                <input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="e.g. Advanced Mathematics"
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
            </div>
            <p v-if="form.errors.name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.name }}</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Short Name</label>
            <input
                v-model="form.short_name"
                type="text"
                required
                placeholder="e.g. Math"
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
            />
            <p v-if="form.errors.short_name" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.short_name }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Subject Code</label>
                <input
                    v-model="form.code"
                    type="text"
                    required
                    placeholder="e.g. MATH101"
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                />
                <p v-if="form.errors.code" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.code }}</p>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 ml-1">Group / Category</label>
                <select
                    v-model="form.group"
                    required
                    class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none"
                >
                    <option v-for="g in ['Core', 'Elective', 'Vocational']" :key="g" :value="g">{{ g }}</option>
                </select>
                <p v-if="form.errors.group" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.group }}</p>
            </div>
          </div>
        </Card>
      </div>

      <!-- Additional Information -->
      <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
          <FileText :size="20" class="text-indigo-600" />
          Curriculum Info
        </h3>
        
        <Card class="p-6 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 ml-1">Description</label>
            <textarea
                v-model="form.description"
                rows="5"
                placeholder="Brief summary of the subject's curriculum or goals..."
                class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 text-sm focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-500 transition-all outline-none resize-none"
            ></textarea>
            <p v-if="form.errors.description" class="text-xs text-rose-500 font-bold ml-1">{{ form.errors.description }}</p>
          </div>
        </Card>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
      <Link 
        :href="route('subjects.index')"
        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50 transition-all"
      >
        Cancel
      </Link>
      <button
        type="submit"
        :disabled="form.processing"
        class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 disabled:opacity-70"
      >
        <Loader2 v-if="form.processing" class="animate-spin" :size="18" />
        <Check v-else :size="18" />
        {{ isEdit ? 'Update Subject' : 'Save Subject' }}
      </button>
    </div>
  </form>
</template>
