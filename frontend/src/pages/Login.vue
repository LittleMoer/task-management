<template>
  <form @submit.prevent="onSubmit">
    <h1>Login</h1>
    <input v-model="username" placeholder="Username" />
    <input v-model="password" type="password" placeholder="Password" />
    <button type="submit">Login</button>
    <p v-if="error" style="color:red">{{ error }}</p>
  </form>
</template>
<script setup>
import { ref } from 'vue';
import api from '../lib/api';
import { useRouter } from 'vue-router';
const router = useRouter();
const username = ref(''); const password = ref(''); const error = ref('');
const onSubmit = async () => {
  error.value = '';
  try {
    const { data } = await api.post('/auth/login', { username: username.value, password: password.value });
    localStorage.setItem('access_token', data.access_token);
    router.push({ name: 'dashboard' });
  } catch { error.value = 'Invalid credentials'; }
};
</script>