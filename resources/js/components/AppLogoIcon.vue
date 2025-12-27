<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps({
    className: {
        type: String,
        default: 'w-8 h-8'
    }
});
</script>

<template>
    <svg  viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="none">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M29 16a13 13 0 11-26 0 13 13 0 0126 0h0z"/>
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.5 16l3.5 3.5 6-6"/>
    </svg>
    
</template>
