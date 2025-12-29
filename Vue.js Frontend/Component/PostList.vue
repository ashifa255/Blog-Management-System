<template>
  <div class="post-list">
    <!-- v-if for loading state -->
    <div v-if="loading" class="loading">
      Loading posts...
    </div>
    
    <!-- v-show for empty state -->
    <div v-show="!loading && posts.length === 0" class="empty-state">
      No posts found.
    </div>
    
    <!-- v-for for list rendering -->
    <div v-for="post in posts" :key="post.id" class="post-card">
      <div class="post-header">
        <img 
          :src="post.user.profile_picture" 
          :alt="post.user.username"
          class="profile-picture"
        >
        <div class="user-info">
          <h4>{{ post.user.username }}</h4>
          <small>{{ formatDate(post.created_at) }}</small>
        </div>
        
        <!-- Conditional rendering for edit/delete buttons -->
        <div v-if="isPostOwner(post)" class="post-actions">
          <button @click="editPost(post)" class="btn-edit">Edit</button>
          <button @click="deletePost(post)" class="btn-delete">Delete</button>
        </div>
      </div>
      
      <h3>{{ post.title }}</h3>
      <p>{{ truncateText(post.content, 200) }}</p>
      
      <!-- Tags with v-for -->
      <div class="tags">
        <span 
          v-for="tag in post.tags" 
          :key="tag.id"
          class="tag"
        >
          {{ tag.name }}
        </span>
      </div>
      
      <!-- Interaction buttons -->
      <div class="post-footer">
        <button 
          @click="toggleLike(post)"
          :class="['btn-like', { liked: post.is_liked }]"
        >
          <i class="icon-heart"></i>
          {{ post.likes_count }}
        </button>
        
        <button @click="toggleBookmark(post)" class="btn-bookmark">
          <i :class="['icon-bookmark', { active: post.is_bookmarked }]"></i>
        </button>
        
        <router-link 
          :to="{ name: 'post-detail', params: { slug: post.slug } }"
          class="btn-read"
        >
          Read More
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useAuthStore } from '@/store/auth'
import api from '@/services/api'
import { useRouter } from 'vue-router'

const props = defineProps({
  filter: {
    type: String,
    default: 'all'
  }
})

const posts = ref([])
const loading = ref(false)
const authStore = useAuthStore()
const router = useRouter()

// Computed property for filtered posts
const filteredPosts = computed(() => {
  if (props.filter === 'user') {
    return posts.value.filter(post => post.user_id === authStore.user?.id)
  }
  if (props.filter === 'bookmarked') {
    return posts.value.filter(post => post.is_bookmarked)
  }
  return posts.value
})

// Fetch posts on mounted
onMounted(async () => {
  await fetchPosts()
})

// Watch for filter changes
watch(() => props.filter, async () => {
  await fetchPosts()
})

const fetchPosts = async () => {
  loading.value = true
  try {
    let url = '/posts'
    if (props.filter === 'user') {
      url = '/user/posts'
    } else if (props.filter === 'bookmarked') {
      url = '/user/bookmarks'
    }
    
    const response = await api.get(url)
    posts.value = response.data.data
  } catch (error) {
    console.error('Error fetching posts:', error)
  } finally {
    loading.value = false
  }
}

const isPostOwner = (post) => {
  return authStore.user && post.user_id === authStore.user.id
}

const toggleLike = async (post) => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }
  
  try {
    const endpoint = post.is_liked ? `/posts/${post.id}/unlike` : `/posts/${post.id}/like`
    await api.post(endpoint)
    
    post.is_liked = !post.is_liked
    post.likes_count += post.is_liked ? 1 : -1
  } catch (error) {
    console.error('Error toggling like:', error)
  }
}

const toggleBookmark = async (post) => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }
  
  try {
    const endpoint = post.is_bookmarked 
      ? `/posts/${post.id}/remove-bookmark` 
      : `/posts/${post.id}/bookmark`
    
    await api.post(endpoint)
    post.is_bookmarked = !post.is_bookmarked
  } catch (error) {
    console.error('Error toggling bookmark:', error)
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const truncateText = (text, maxLength) => {
  if (text.length <= maxLength) return text
  return text.substring(0, maxLength) + '...'
}
</script>

<style scoped>
.post-list {
  max-width: 800px;
  margin: 0 auto;
}

.post-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  background: white;
}

.post-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.profile-picture {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.post-actions {
  margin-left: auto;
  display: flex;
  gap: 10px;
}

.tags {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  margin: 10px 0;
}

.tag {
  background: #f0f0f0;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 12px;
}

.post-footer {
  display: flex;
  gap: 15px;
  margin-top: 15px;
}

.btn-like.liked {
  color: #e74c3c;
}
</style>
