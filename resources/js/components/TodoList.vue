<script>
import axios from "axios";

export default {
  data() {
    return {
      todos: [],
      newTodo: "",
      newDueDate: "",
      editingId: null,
      showComplete: true,
      editText: "",
      editDueDate: "",
      sortBy: "created",
    };
  },
  computed: {
    sortedTodos() {
      let sorted = [...this.todos];

      // Do not show completed todos if turned off
      if (!this.showComplete) {
        sorted = sorted.filter((todo) => !todo.completed);
      }

      if (this.sortBy === "created") {
        return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      } else if (this.sortBy === "due") {
        return sorted.sort((a, b) => {
          if (!a.due_date && !b.due_date) return 0;
          if (!a.due_date) return 1;
          if (!b.due_date) return -1;
          return new Date(a.due_date) - new Date(b.due_date);
        });
      } else if (this.sortBy === "alpha") {
        return sorted.sort((a, b) => a.title.localeCompare(b.title));
      }
      return sorted;
    }
  },

  methods: {
    async fetchTodos() {
      if (!this.$page.props.auth?.user) {
        console.error('User not authenticated');
        window.location.href = '/login';
        return;
      }
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
          due_date: this.newDueDate || null,
        });
        this.todos.push(response.data);
        this.newTodo = "";
        this.newDueDate = "";
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
    async deleteTodo(todo) {
      try {
        await axios.delete(`/api/todos/${todo.id}`);
        this.todos = this.todos.filter((curr_todo) => curr_todo !== todo);
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
        due_date: this.editDueDate || null,
      };

      await axios.put(`/api/todos/${todo.id}`, updated);
      todo.title = this.editText;
      todo.due_date = this.editDueDate || null;
      this.cancelEdit();
    },

    cancelEdit() {
      this.editingId = null;
      this.editText = "";
      this.editDueDate = "";
    },

    startEdit(todo) {
      this.editingId = todo.id;
      this.editText = todo.title;
      this.editDueDate = todo.due_date || "";
    },

    toggleShowComplete() {
        this.showComplete = !this.showComplete;
        return !this.showComplete;
    }, 

    formatDate(dateString) {
      if (!dateString) return "";
      // Parse the date string directly without timezone conversion
      // For dates in YYYY-MM-DD format
      const [year, month, day] = dateString.split('T')[0].split('-');
      const date = new Date(year, month - 1, day);
      
      return date.toLocaleDateString("en-US", { 
        month: "short", 
        day: "numeric", 
        year: "numeric",
        timeZone: undefined  // Use local timezone
      });
    },

    isOverdue(todo) {
      if (!todo.due_date || todo.completed) return false;
      return new Date(todo.due_date) < new Date();
    },
  },
  mounted() {
    if (!this.$page.props.auth?.user) {
      window.location.href = '/login';
      return;
    }
    this.fetchTodos();
  },
};
</script>


<!-- Todo App functionality and frontend -->
<template>
  <div class="mx-auto mt-16 max-w-[520px] rounded-xl bg-white p-8 shadow-[0_10px_25px_rgba(0,0,0,0.08)] dark:bg-[#0a0a0a]">
    <h1 class="mb-6 text-center text-2xl font-bold">To-Do List</h1>

    <!-- Sort Controls: -->
    <div class="mb-4 flex items-center gap-2">
      <label class="text-sm font-medium">Sort by:</label>
      <select 
        v-model="sortBy"
        class="rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-indigo-600 focus:outline-none"
      >
        <option value="created">Date Created</option>
        <option value="due">Due Date</option>
        <option value="alpha">Alphabetical</option>
      </select>
       <label class="text-sm font-medium">Show completed:</label>
       <div
            class="flex h-[22px] w-[22px] cursor-pointer select-none items-center justify-center rounded border-2 border-indigo-600 text-sm text-white dark:text-[#0a0a0a]"
            :class="{ 'bg-indigo-600': this.showComplete}"
            @click="toggleShowComplete()"
          >
            ✓
          </div>
    </div>


    <!-- Add Todo Form -->
    <form class="mb-6 flex flex-col gap-2" @submit.prevent="addTodo">
      <!-- Title text -->
    
      <input
        v-model="newTodo"
        type="text" 
        placeholder="What do you need to do?"
        class="rounded-md border border-gray-300 px-3 py-2.5 text-base focus:border-indigo-600 focus:outline-none"
        required
      />
      <!-- Due date (Optional) -->
      <div class="flex gap-2 items-center">
        <label class="text-sm font-medium">Due Date:</label>
        <input
          v-model="newDueDate"
          type="date"
          class="flex-1 rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-600 focus:outline-none"
          placeholder="Due date (optional)"
        />
        <button class="rounded-md bg-indigo-600 px-4 py-2.5 text-white hover:bg-indigo-700" type="submit">
          Add
        </button>
      </div>
    </form>

    <!-- Todo List -->
    <ul class="m-0 list-none p-0">
      <li
        v-for="todo in sortedTodos"
        :key="todo.id"
        class="flex flex-col gap-2 border-b border-gray-200 px-2.5 py-3 last:border-b-0"
      >
        <div 
          class="flex items-center gap-3">
          <!-- Square checkbox -->
          <div
            class="flex h-[22px] w-[22px] cursor-pointer select-none items-center justify-center rounded border-2 border-indigo-600 text-sm text-white dark:text-[#0a0a0a]"
            :class="{ 'bg-indigo-600': todo.completed }"
            @click="toggleComplete(todo)"
          >
            ✓
          </div>

          <!-- Todo text OR edit input -->
          <template v-if="editingId === todo.id">
            <div class="flex flex-1 flex-col gap-1.5">
              <input
                v-model="editText"
                class="rounded border border-indigo-600 px-2 py-1.5"
                @keyup.enter="saveEdit(todo)"
                @keyup.esc="cancelEdit"
                placeholder="Task name"
                autofocus
              />
              <input
                v-model="editDueDate"
                type="date"
                class="rounded border border-indigo-600 px-2 py-1.5 text-sm"
                @keyup.enter="saveEdit(todo)"
                @keyup.esc="cancelEdit"
              />
            </div>
          </template>

          <template v-else>
            <div class="flex-1">
              <span
                class="block text-base"
                :class="{ 'text-gray-400 line-through': todo.completed }"
              >
                {{ todo.title }}
              </span>
              <div class="mt-1 flex gap-3 text-xs text-gray-500">
                <span v-if="todo.due_date" :class="{ 'text-red-500 font-medium': isOverdue(todo) }">
                  📅 Due: {{ formatDate(todo.due_date) }}
                </span>
              </div>
            </div>
          </template>

          <!-- Actions -->
          <div class="flex gap-1">
            <button
              class="cursor-pointer border-0 bg-transparent text-base"
              @click="startEdit(todo)"
              v-if="editingId !== todo.id"
            >
              ✏️
            </button>
            <button
              class="cursor-pointer border-0 bg-transparent text-base"
              @click="saveEdit(todo)"
              v-if="editingId == todo.id"
            >
              ✓
            </button>

            <button
              class="cursor-pointer border-0 bg-transparent text-lg text-red-500 hover:text-red-700"
              @click="deleteTodo(todo)"
            >
              ✕
            </button>
          </div>
        </div>
      </li>
    </ul>

    <p v-if="todos.length === 0" class="mt-4 text-center text-gray-500">
      No todos yet. Add one above!
    </p>
  </div>
</template>