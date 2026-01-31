<template>
  <div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h1 class="h4 text-center mb-4">Register</h1>

              <form @submit.prevent="onSubmit" class="d-grid gap-3">
                <div>
                  <label class="form-label">Name</label>
                  <input v-model="name" class="form-control" placeholder="Full Name" required />
                </div>

                <div>
                  <label class="form-label">Username</label>
                  <input v-model="username" class="form-control" placeholder="Username" required />
                </div>

                <div>
                  <label class="form-label">Password</label>
                  <input v-model="password" type="password" class="form-control" placeholder="Password" required />
                </div>

                <button type="submit" class="btn btn-primary w-100">Create Account</button>

                <button type="button" @click="goLogin" class="btn btn-outline-secondary w-100">
                  Back to Login
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
const name = ref('');
const username = ref('');
const password = ref('');
const error = ref('');

const onSubmit = async () => {
  error.value = '';
  try {
    const { data } = await api.post('/auth/register', {
      name: name.value,
      username: username.value,
      password: password.value
    });
    localStorage.setItem('access_token', data.access_token);
    router.push({ name: 'dashboard' });
  } catch (err) {
    // Tampilkan error detail dari backend
    if (err.response?.status === 422 && err.response?.data?.errors) {
      const errors = err.response.data.errors;
      const errorMessages = Object.values(errors).flat();
      error.value = errorMessages.join(', ');
    } else {
      error.value = err.response?.data?.message || 'Registration failed';
    }
  }
};
</script>