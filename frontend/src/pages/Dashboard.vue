<template>
  <div>
    <h1>Dashboard</h1>
    <pre v-if="me">{{ me }}</pre>
    <button @click="logout">Logout</button>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import api from '../lib/api';
import { useRouter } from 'vue-router';
const router = useRouter();
const me = ref('');
onMounted(async () => {
  const { data } = await api.get('/me');
  me.value = JSON.stringify(data, null, 2);
});
const logout = async () => {
  try { await api.post('/auth/logout'); } catch {}
  localStorage.removeItem('access_token');
  router.push({ name: 'login' });
};
</script>