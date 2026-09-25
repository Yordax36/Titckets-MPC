import axios from 'axios'

const api = axios.create({
  baseURL: '/api/v1',
})

export function getErrorMessage(err: any): string {
  const data = err?.response?.data
  if (!data) return 'Error de conexión'

  if (data.message && data.errors) {
    const firstError = Object.values(data.errors)[0]
    return Array.isArray(firstError) ? firstError[0] : String(firstError)
  }
  if (data.message) return data.message
  if (data.error) return data.error

  return 'Error inesperado'
}

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && !error.config?.url?.includes('/auth/login')) {
      localStorage.removeItem('token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api
