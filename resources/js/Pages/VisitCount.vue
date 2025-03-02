<script setup>
import {ref} from "vue"

const count = ref(0);
const messages = ref([]);
const message = ref('');

Echo.channel('hello-world').listen('HelloWorld', (e)=> {
    console.log(e);
    count.value++;
});


//Echo.channel('messages').listen('MessageReceived', (e)=> {
Echo.private('messages').listen('MessageReceived', (e)=> {
    if (!messages.value.find((m) => m.id === e.id)){
        messages.value.push({
            ...e,
            //who: "Them"
        });
    }
});

/*const props = defineProps({
    user: Object
});
Echo.private(`messages.${props.user.id}`).listen('MessageReceived', (e)=> {
    if (!messages.value.find((m) => m.id === e.id)){
        messages.value.push({
            ...e,
            //who: "Them"
        });
    }
});*/

const handleSubmit = () => {
  const msg = {
    id: crypto.randomUUID(),
    message: message.value
  };
  messages.value.push({
      ...msg,
      who: "Me"
  });
  axios.post("/messages", msg);
}
</script>
<template>
    <div>The visit count is {{ count }}</div>
    <ul>
        <li v-for="message in messages" :key="message.id">
            {{ message.who }} - {{ message.message }}
        </li>
    </ul>
    <form @submit.prevent="handleSubmit">
        <textarea v-model="message"></textarea>
        <button>Send Message</button>
    </form>
</template>