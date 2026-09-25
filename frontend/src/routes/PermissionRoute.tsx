import { Navigate, Outlet } from 'react-router-dom'
import useAuth from '../hooks/useAuth'

interface PermissionRouteProps {
  permission: string
}

export default function PermissionRoute({ permission }: PermissionRouteProps) {
  const { hasPermission, isLoading } = useAuth()

  if (isLoading) return null

  if (!hasPermission(permission)) {
    return <Navigate to="/" replace />
  }

  return <Outlet />
}
