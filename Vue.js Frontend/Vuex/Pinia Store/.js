// src/store/auth.js
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import router from '@/router'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  const token = ref(localStorage.getItem('token') || null)
  
  const isAuthenticated = computed(() => !!token.value)
  
  const login = async (credentials) => {
    try {
      const response = await api.post('/auth/login', credentials)
      
      user.value = response.data.user
      token.value = response.data.token
      
      // Store in localStorage
      localStorage.setItem('user', JSON.stringify(user.value))
      localStorage.setItem('token', token.value)
      
      // Set default axios header
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      router.push('/dashboard')
      
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }
  
  const register = async (userData) => {
    try {
      const response = await api.post('/auth/register', userData)
      
      user.value = response.data.user
      token.value = response.data.token
      
      localStorage.setItem('user', JSON.stringify(user.value))
      localStorage.setItem('token', token.value)
      
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      router.push('/dashboard')
      
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }
  
  const logout = () => {
    user.value = null
    token.value = null
    
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    
    delete api.defaults.headers.common['Authorization']
    
    router.push('/login')
  }
  
  const updateProfile = async (profileData) => {
    try {
      const response = await api.put('/profile', profileData)
      user.value = response.data.user
      localStorage.setItem('user', JSON.stringify(user.value))
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }
  
  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    updateProfile
  }
})
