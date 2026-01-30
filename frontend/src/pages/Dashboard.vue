<template>
  <div class="bg-light min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Task Management</a>
        <div class="d-flex">
          <button class="btn btn-outline-light btn-sm" @click="onLogout">Logout</button>
        </div>
      </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
      <div class="row g-3">
        <!-- Welcome Card -->
        <div class="col-12 mb-4">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Welcome {{ user?.username }}</h6>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-primary" @click="showFilter = !showFilter">
                    <i class="bi bi-funnel"></i> Filter
                  </button>
                  <button class="btn btn-sm btn-primary" @click="showCreateModal = true">
                    <i class="bi bi-plus"></i> New Task
                  </button>
                </div>
              </div>

              <!-- Filter Section -->
              <div v-if="showFilter" class="mb-3 p-3 bg-light rounded">
                <div class="row g-2">
                  <div class="col-md-4">
                    <select v-model="filter.status" class="form-select form-select-sm">
                      <option value="">All Status</option>
                      <option value="todo">Todo</option>
                      <option value="in_progress">In Progress</option>
                      <option value="done">Done</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <input v-model="filter.search" type="text" class="form-control form-control-sm"
                      placeholder="Search...">
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-sm btn-secondary" @click="clearFilter">Clear</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tasks List -->
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h6 class="mb-3">My Tasks</h6>

              <div v-if="filteredTasks.length === 0" class="text-muted">
                No tasks found.
              </div>

              <div v-else class="row g-3">
                <div class="col-12 col-md-6 col-lg-4" v-for="task in tasks" :key="task.task_id">
                  <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-1">{{ task.title }}</h6>
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <a class="dropdown-item" href="#" @click="editTask(task)">
                                <i class="bi bi-pencil"></i> Edit
                              </a>
                            </li>
                            <li>
                              <a class="dropdown-item text-danger" href="#" @click="deleteTask(task)">
                                <i class="bi bi-trash"></i> Delete
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="text-muted small mb-2">
                        Status: {{ task.status }} | Deadline: {{ task.dateline }}
                      </div>
                      <div class="small">
                        Created by: {{ task.user?.name }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Task Modal -->
    <div v-if="showCreateModal" class="modal fade show" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Task</h5>
            <button type="button" class="btn-close" @click="showCreateModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createTask">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input v-model="newTask.title" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea v-model="newTask.description" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select v-model="newTask.status" class="form-select">
                  <option value="todo">todo</option>
                  <option value="in_progress">in progress</option>
                  <option value="done">done</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Deadline</label>
                <input v-model="newTask.dateline" type="date" class="form-control">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="showCreateModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="createTask">Create</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Task Modal -->
    <div v-if="showEditModal" class="modal fade show" style="display: block;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Task</h5>
            <button type="button" class="btn-close" @click="showEditModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateTask">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input v-model="currentTask.title" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea v-model="currentTask.description" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select v-model="currentTask.status" class="form-select">
                  <option value="pending">Pending</option>
                  <option value="in-progress">In Progress</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Deadline</label>
                <input v-model="currentTask.dateline" type="date" class="form-control">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="showEditModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="updateTask">Update</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import api from '../lib/api';

const router = useRouter();
const user = ref(null);
const tasks = ref([]);
const showFilter = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const currentTask = ref(null);

const filter = ref({
  status: '',
  search: ''
});

const filteredTasks = computed(() => {
  // Filter di frontend saja
  return tasks.value.filter(task => {
    const matchesStatus = !filter.value.status || task.status === filter.value.status;
    const matchesSearch = !filter.value.search ||
      task.title.toLowerCase().includes(filter.value.search.toLowerCase()) ||
      task.description.toLowerCase().includes(filter.value.search.toLowerCase());
    return matchesStatus && matchesSearch;
  });
});

const newTask = ref({
  title: '',
  description: '',
  status: 'pending',
  dateline: ''
});

// Load functions
const loadMe = async () => {
  const { data } = await api.get('/me');
  user.value = data;
};

const loadTasks = async () => {
  try {
    const { data } = await api.get('/dashboard');
    tasks.value = data;
  } catch (error) {
    console.error('Load tasks failed:', error.response?.data);
  }
};

// Task actions
const editTask = (task) => {
  currentTask.value = { ...task };
  showEditModal.value = true;
};

const deleteTask = async (task) => {
  if (confirm('Are you sure you want to delete this task?')) {
    try {
      await api.post('/dashboard/delete', { task_id: task.task_id });
      await loadTasks();
    } catch (error) {
      console.error('Delete failed:', error.response?.data);
    }
  }
};

const createTask = async () => {
  try {
    const response = await api.post('/dashboard/create', newTask.value);
    console.log('Task created:', response.data);
    showCreateModal.value = false;
    newTask.value = { title: '', description: '', status: 'todo', dateline: '' };
    await loadTasks();
  } catch (error) {
    console.error('Create failed:', error);
  }
};

const updateTask = async () => {
  try {
    await api.post('/dashboard/edit', {
      task_id: currentTask.value.task_id,
      title: currentTask.value.title,
      description: currentTask.value.description,
      status: currentTask.value.status,
      dateline: currentTask.value.dateline,
    });

    showEditModal.value = false;
    await loadTasks();
  } catch (error) {
    console.error('Update failed:', error.response?.data);
  }
};
const clearFilter = () => {
  filter.value = { status: '', search: '' };
  loadTasks();
};

const onLogout = async () => {
  try {
    await api.post('/auth/logout');
  } catch { }
  localStorage.removeItem('access_token');
  router.push({ name: 'login' });
};

onMounted(async () => {
  await loadMe();
  await loadTasks();
});
</script>