import { Navigate, Outlet } from 'react-router-dom'
import usePermission from '../hooks/usePermission'

interface PermissionRouteProps {
  permission: string
}

export default function PermissionRoute({ permission }: PermissionRouteProps) {
  const { hasPermission } = usePermission()

  if (!hasPermission(permission)) {
    return <Navigate to="/" replace />
  }

  return <Outlet />
}
