<template>
  <div class="min-vh-100 d-flex align-items-center bg-light"> 
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h1 class="h4 text-center mb-4">Login</h1>

              <form @submit.prevent="onSubmit" class="d-grid gap-3">
                <div>
                  <label class="form-label">Username</label>
                  <input v-model="username" class="form-control" placeholder="Username" />
                </div>

                <div>
                  <label class="form-label">Password</label>
                  <input v-model="password" type="password" class="form-control" placeholder="Password" />
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <button type="button" @click="goRegister" class="btn btn-outline-secondary w-100">
                  Register
                </button>

                <p v-if="error" class="text-danger text-center small mb-0">{{ error }}</p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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

const goRegister = () => {
  router.push({ name: 'register' });
};
</script>