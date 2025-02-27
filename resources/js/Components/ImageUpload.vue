<script setup>
import {ref} from "vue";

const preview = ref(null)
const imageSize = ref(false)
const revertBtn = ref(false)

const emit = defineEmits(['image'])

const props = defineProps({
    defaultImage: String,
})
const imageSelected = (e) => {
    preview.value = URL.createObjectURL(e.target.files[0])
    imageSize.value = e.target.files[0].size > 3145729;
    revertBtn.value = true
    emit('image', e.target.files[0])
}

const revertImage = () => {
    preview.value = props.defaultImage
    imageSize.value = false
    revertBtn.value = false
    emit('image', null)
}
</script>

<template>
    <div>
        <span class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        :class="{'!text-red-500': imageSize}">
            {{ imageSize ? 'The selected image exceeds 3MB' : 'Image (Max Size: 3MB)'}}
        </span>
        <label for="image" class="relative block rounded-md-1 bg-slate-300 h-[230px] overflow-hidden cursor-pointer border-slate-300 border"
               :class="{'!border-red-500': imageSize}" >
            <img :src="preview ?? props.defaultImage" class="object-cover object-center w-full h-full" />

            <button class="absolute top-2 right-2 bg-white/75 w-8 h-8 rounded-full grid place-items-center text-slate-400"
                    v-if="revertBtn"
                    @click.prevent="revertImage"

                    type="button"
                    >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <input @input="imageSelected" type="file" id="image" class="hidden" />
            <div class="grid place-items-center h-full text-slate-400">
                <i class="fa-solid fa-image"></i>
            </div>
        </label>
    </div>
</template>
