<template>
  <div class="post-create">
    <h2>Create New Post</h2>
    
    <form @submit.prevent="submitForm">
      <!-- Two-way data binding with v-model -->
      <div class="form-group">
        <label for="title">Title</label>
        <input 
          type="text" 
          id="title" 
          v-model="form.title"
          :class="{ 'error': errors.title }"
          @input="clearError('title')"
        >
        <div v-if="errors.title" class="error-message">
          {{ errors.title[0] }}
        </div>
      </div>
      
      <div class="form-group">
        <label for="content">Content</label>
        <textarea 
          id="content" 
          v-model="form.content"
          rows="10"
          :class="{ 'error': errors.content }"
          @input="clearError('content')"
        ></textarea>
        <div v-if="errors.content" class="error-message">
          {{ errors.content[0] }}
        </div>
      </div>
      
      <div class="form-group">
        <label for="tags">Tags (comma-separated)</label>
        <input 
          type="text" 
          id="tags" 
          v-model="tagInput"
          placeholder="vue, laravel, javascript"
          @input="updateTags"
        >
        <div class="selected-tags">
          <span 
            v-for="tag in form.tags" 
            :key="tag"
            class="selected-tag"
            @click="removeTag(tag)"
          >
            {{ tag }} ×
          </span>
        </div>
      </div>
      
      <div class="form-group">
        <label>Visibility</label>
        <div class="radio-group">
          <label>
            <input 
              type="radio" 
              v-model="form.visibility" 
              value="public"
            > Public
          </label>
          <label>
            <input 
              type="radio" 
              v-model="form.visibility" 
              value="private"
            > Private
          </label>
        </div>
      </div>
      
      <div class="form-group">
        <label for="image">Featured Image (Optional)</label>
        <input 
          type="file" 
          id="image" 
          accept="image/*"
          @change="handleImageUpload"
        >
        <div v-if="imagePreview" class="image-preview">
          <img :src="imagePreview" alt="Preview">
        </div>
      </div>
      
      <div class="form-actions">
        <button 
          type="submit" 
          :disabled="loading"
          class="btn-submit"
        >
          {{ loading ? 'Creating...' : 'Create Post' }}
        </button>
        <button 
          type="button" 
          @click="$router.go(-1)"
          class="btn-cancel"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const loading = ref(false)
const imagePreview = ref(null)
const tagInput = ref('')

const form = reactive({
  title: '',
  content: '',
  tags: [],
  visibility: 'public',
  image: null
})

const errors = reactive({})

const submitForm = async () => {
  loading.value = true
  errors.value = {}
  
  const formData = new FormData()
  formData.append('title', form.title)
  formData.append('content', form.content)
  formData.append('visibility', form.visibility)
  
  if (form.tags.length > 0) {
    form.tags.forEach(tag => {
      formData.append('tags[]', tag)
    })
  }
  
  if (form.image) {
    formData.append('image', form.image)
  }
  
  try {
    const response = await api.post('/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    router.push({
      name: 'post-detail',
      params: { slug: response.data.slug }
    })
  } catch (error) {
    if (error.response?.status === 422) {
      Object.assign(errors, error.response.data.errors)
    }
    console.error('Error creating post:', error)
  } finally {
    loading.value = false
  }
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const updateTags = () => {
  const tagArray = tagInput.value
    .split(',')
    .map(tag => tag.trim())
    .filter(tag => tag.length > 0)
  
  form.tags = [...new Set(tagArray)]
}

const removeTag = (tagToRemove) => {
  form.tags = form.tags.filter(tag => tag !== tagToRemove)
}

const clearError = (field) => {
  if (errors[field]) {
    delete errors[field]
  }
}

// Watch for changes in tagInput
watch(tagInput, () => {
  updateTags()
})
</script>

<style scoped>
.post-create {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

input.error,
textarea.error {
  border-color: #e74c3c;
}

.error-message {
  color: #e74c3c;
  font-size: 14px;
  margin-top: 5px;
}

.selected-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  margin-top: 10px;
}

.selected-tag {
  background: #3498db;
  color: white;
  padding: 5px 10px;
  border-radius: 15px;
  font-size: 14px;
  cursor: pointer;
}

.radio-group {
  display: flex;
  gap: 20px;
  margin-top: 5px;
}

.image-preview {
  margin-top: 10px;
  max-width: 300px;
}

.image-preview img {
  width: 100%;
  height: auto;
  border-radius: 4px;
}

.form-actions {
  display: flex;
  gap: 10px;
  margin-top: 30px;
}

.btn-submit {
  background: #2ecc71;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel {
  background: #95a5a6;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
