<template>
    <div v-if="getSystemMessage" :class="getMessageClass" class="flex flex-row p-3 w-full items-center justify-between">
        <div class="text-sm">
            {{ getSystemMessage.text }}
        </div>
        <button v-on:click="() => {show = false; clearSystemMessage()}" :class="getMessageButtonClass" class="px-1 rounded text-lg">&times;</button>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    data() {
        return {
            show: true,
        };
    },
    methods: {
        ...mapActions(['clearSystemMessage'])
    },
    computed: {
        ...mapGetters(['getSystemMessage']),
        getMessageClass() {
            return {
                'bg-green-300 text-green-700' : this.getSystemMessage.type === 'SUCCESS',
                'bg-red-300 text-red-700' : this.getSystemMessage.type === 'ERROR'
            }
        },
        getMessageButtonClass() {
            return {
                'hover:bg-red-400' :  this.getSystemMessage.type === 'ERROR',
                'hover:bg-gree-400' : this.getSystemMessage.type === 'SUCCESS'
            }
        }
    }
};
</script>
