import useAuthStore from '../store/authStore'

const usePermission = () => {
  const { hasPermission, user } = useAuthStore()
  return { hasPermission, user }
}

export default usePermission
