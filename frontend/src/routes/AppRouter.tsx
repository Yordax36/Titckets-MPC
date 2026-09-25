import { Routes, Route } from 'react-router-dom'
import { useEffect } from 'react'
import Layout from '../components/layout/Layout'
import ProtectedRoute from './ProtectedRoute'
import PermissionRoute from './PermissionRoute'
import LoginPage from '../pages/auth/LoginPage'
import DashboardPage from '../pages/dashboard/DashboardPage'
import TicketListPage from '../pages/tickets/TicketListPage'
import TicketDetailPage from '../pages/tickets/TicketDetailPage'
import UsuarioListPage from '../pages/usuarios/UsuarioListPage'
import TecnicosPage from '../pages/tecnicos/TecnicosPage'
import AreaListPage from '../pages/areas/AreaListPage'
import CargosPage from '../pages/cargos/CargosPage'
import DesignacionesPage from '../pages/designaciones/DesignacionesPage'
import BienesPage from '../pages/bienes/BienesPage'
import RegistrarBienPage from '../pages/bienes/RegistrarBienPage'
import BienDetailPage from '../pages/bienes/BienDetailPage'
import AreaBienesPage from '../pages/bienes/AreaBienesPage'
import AuditPage from '../pages/audit/AuditPage'
import ConfiguracionPage from '../pages/configuracion/ConfiguracionPage'
import PerfilAreaPage from '../pages/perfil-area/PerfilAreaPage'
import NotFoundPage from '../pages/NotFoundPage'
import useAuth from '../hooks/useAuth'

export default function AppRouter() {
  const { loadUser, isAuthenticated } = useAuth()

  useEffect(() => {
    if (isAuthenticated) {
      loadUser()
    }
  }, [])

  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />

      <Route element={<ProtectedRoute />}>
        <Route element={<Layout />}>
          <Route path="/" element={<DashboardPage />} />
          <Route path="/tickets" element={<TicketListPage />} />
          <Route path="/tickets/:id" element={<TicketDetailPage />} />
          <Route path="/bienes/mi-area" element={<AreaBienesPage />} />
          <Route path="/bienes/:id" element={<BienDetailPage />} />
          <Route path="/perfil-area" element={<PerfilAreaPage />} />

          <Route element={<PermissionRoute permission="ver_usuarios" />}>
            <Route path="/usuarios" element={<UsuarioListPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_tecnicos" />}>
            <Route path="/tecnicos" element={<TecnicosPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_areas" />}>
            <Route path="/areas" element={<AreaListPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_cargos" />}>
            <Route path="/cargos" element={<CargosPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_designaciones" />}>
            <Route path="/designaciones" element={<DesignacionesPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_bienes" />}>
            <Route path="/bienes" element={<BienesPage />} />
            <Route path="/bienes/registrar" element={<RegistrarBienPage />} />
          </Route>
          <Route element={<PermissionRoute permission="ver_auditoria" />}>
            <Route path="/auditoria" element={<AuditPage />} />
          </Route>
          <Route element={<PermissionRoute permission="configurar_sistema" />}>
            <Route path="/configuracion" element={<ConfiguracionPage />} />
          </Route>
        </Route>
      </Route>

      <Route path="*" element={<NotFoundPage />} />
    </Routes>
  )
}
