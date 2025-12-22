<template>
  <div class="dark:bg-[#0a0a0a] todo-app">
    <h1 class="title">To-Do List</h1>

    <!-- Add Todo Form -->
    <form class="add-form" @submit.prevent="addTodo">
      <input
        v-model="newTodo"
        type="text" 
        placeholder="What do you need to do?"
        class="todo-input"
        required
      />
      <button class="add-button" type="submit">Add</button>
    </form>

    <!-- Todo List -->
    <ul class="todo-list">
      <li
        v-for="todo in todos"
        :key="todo.id"
        class="todo-item"
      >
        <!-- Square checkbox -->
        <div
          class="text-white dark:text-[#0a0a0a] checkbox "
          :class="{ checked: todo.completed }"
          @click="toggleComplete(todo)"
        >
          ✓
        </div>

        <!-- Todo text OR edit input -->
        <template v-if="editingId === todo.id">
          <input
            v-model="editText"
            class="edit-input"
            @keyup.enter="saveEdit(todo)"
            @keyup.esc="cancelEdit"
            @blur="cancelEdit"
            autofocus
          />
        </template>

        <template v-else>
          <span
            class="todo-text"
            :class="{ completed: todo.completed }"
          >
            {{ todo.title }}
          </span>
        </template>

        <!-- Actions -->
        <div class="actions">
          <button
            class="edit-button"
            @click="startEdit(todo)"
            v-if="editingId !== todo.id"
          >
            ✏️
          </button>
          <button
            class="complete-edit-button"
            @click="saveEdit(todo)"
            v-if="editingId == todo.id"
          >
            ✓
          </button>

          <button
            class="delete-button"
            @click="deleteTodo(todo.id)"
          >
            ✕
          </button>
        </div>
      </li>
    </ul>

    <p v-if="todos.length === 0" class="empty-state">
      No todos yet. Add one above!
    </p>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      todos: [],
      newTodo: "",
      editingId: null,
      editText: "",
    };
  },
  methods: {
    async fetchTodos() {
      try {
        const response = await axios.get("/api/todos");
        this.todos = response.data;
      } catch (error) {
        console.error("Error fetching todos:", error);
      }
    },
    async addTodo() {
      try {
        const response = await axios.post("/api/todos", {
          title: this.newTodo,
        });
        this.todos.push(response.data);
        this.newTodo = "";
      } catch (error) {
        console.error("Error adding todo:", error);
      }
    },
    async toggleComplete(todo) {
      try {
        const updatedTodo = {
          ...todo,
          completed: !todo.completed,
        };
        await axios.put(`/api/todos/${todo.id}`, updatedTodo);
        todo.completed = !todo.completed;
      } catch (error) {
        console.error("Error toggling todo:", error);
      }
    },
    async deleteTodo(id) {
      try {
        await axios.delete(`/api/todos/${id}`);
        this.todos = this.todos.filter((todo) => todo.id !== id);
      } catch (error) {
        console.error("Error deleting todo:", error);
      }
    },
    async saveEdit(todo) {
      if (!this.editText.trim()) {
        this.cancelEdit();
        return;
      }

      const updated = {
        ...todo,
        title: this.editText,
      };

      await axios.put(`/api/todos/${todo.id}`, updated);
      todo.title = this.editText;
      this.cancelEdit();
    },

    cancelEdit() {
      this.editingId = null;
      this.editText = "";
    },

    startEdit(todo) {
      this.editingId = todo.id;
      this.editText = todo.title;
    },
  },
  mounted() {
    this.fetchTodos();
  },
};
</script>

<style scoped>
/* Layout */
.todo-app {
  max-width: 520px;
  margin: 4rem auto;
  padding: 2rem;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  /* background: #ffffff; */
  
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.title {
  text-align: center;
  margin-bottom: 1.5rem;
  font-weight: bold;
  font-size: 1.5rem;
}

/* Form */
.add-form {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.todo-input {
  flex: 1;
  padding: 0.6rem 0.75rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.todo-input:focus {
  outline: none;
  border-color: #4f46e5;
}

.add-button {
  padding: 0.6rem 1rem;
  border-radius: 6px;
  border: none;
  background: #4f46e5;
  color: white;
  cursor: pointer;
}

.add-button:hover {
  background: #4338ca;
}

/* Todo list */
.todo-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.todo-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.6rem;
  border-bottom: 1px solid #eee;
}

.todo-item:last-child {
  border-bottom: none;
}

/* Checkbox */
.checkbox {
  width: 22px;
  height: 22px;
  border: 2px solid #4f46e5;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  /* color: white; */
  cursor: pointer;
  user-select: none;
}

.checkbox.checked {
  background: #4f46e5;
}

/* Todo text */
.todo-text {
  flex: 1;
  font-size: 1rem;
}

.completed {
  text-decoration: line-through;
  color: #9ca3af;
}

/* Edit input */
.edit-input {
  flex: 1;
  padding: 0.4rem 0.5rem;
  border-radius: 4px;
  border: 1px solid #4f46e5;
}

.edit-button {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}
.complete-edit-button {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}

/* Actions */
.actions {
  display: flex;
  gap: 0.25rem;
}

/* Delete */
.delete-button {
  border: none;
  background: transparent;
  color: #ef4444;
  font-size: 1.1rem;
  cursor: pointer;
}

.delete-button:hover {
  color: #b91c1c;
}

/* Empty state */
.empty-state {
  text-align: center;
  color: #6b7280;
  margin-top: 1rem;
}
</style>
