<script setup>
import {ref} from "vue";

const preview = ref(null)
const imageSize = ref(false)

const emit = defineEmits(['image'])

const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0])
    imageSize.value = e.target.files[0].size > 3145729;
    emit('image', e.target.files[0])
}
</script>

<template>
    <div>
        <span class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        :class="{'!text-red-500': imageSize}">
            {{ imageSize ? 'The selected image exceeds 3MB' : 'Image (Max Size: 3MB)'}}
        </span>
        <label for="image" class="block rounded-md-1 bg-slate-300 h-[140px] overflow-hidden cursor-pointer border-slate-300 border"
               :class="{'!border-red-500': imageSize}" >
            <img :src="preview ?? '/storage/images/default.png'" class="object-cover object-center w-full h-full" />
            <input @input="imageSelected" type="file" id="image" class="hidden" />
            <div class="grid place-items-center h-full text-slate-400">
                <i class="fa-solid fa-image"></i>
            </div>
        </label>
    </div>
</template>
