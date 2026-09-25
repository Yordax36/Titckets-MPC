import { NavLink } from 'react-router-dom'
import { useState } from 'react'
import { LayoutDashboard, Ticket, Users, Building2, Shield, Link2, Briefcase, Settings, UserCircle, X, HelpCircle, Headphones, Package } from 'lucide-react'
import useAuth from '../../hooks/useAuth'
import useUIStore from '../../store/uiStore'
import { useSettings } from '../../hooks/useSettings'
import HelpGuide from './HelpGuide'

const NavLinkItem = ({ to, icon: Icon, label }: { to: string; icon: any; label: string }) => (
  <NavLink
    to={to}
    onClick={() => window.innerWidth < 1024 && useUIStore.getState().toggleSidebar()}
    className={({ isActive }) =>
      `flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors ${
        isActive
          ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
          : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'
      }`
    }
  >
    <Icon className="h-4 w-4" />
    {label}
  </NavLink>
)

export default function Sidebar() {
  const { user } = useAuth()
  const { sidebarOpen, toggleSidebar } = useUIStore()
  const { system_name, logo } = useSettings()
  const [guideOpen, setGuideOpen] = useState(false)

  const isAdmin = user?.rol?.nombre === 'Administrador' || user?.rol_id === 1
  const isTecnico = user?.rol?.nombre === 'Tecnico' || user?.rol_id === 2
  const isAreaUser = user?.rol?.nombre === 'Area Usuaria' || user?.rol_id === 3

  return (
    <>
      {sidebarOpen && (
        <div className="fixed inset-0 z-30 bg-black/50 lg:hidden" onClick={toggleSidebar} />
      )}

      <aside className={`fixed left-0 top-0 z-40 h-full w-64 bg-white border-r border-gray-200 transition-transform dark:bg-gray-900 dark:border-gray-700 lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
        <div className="flex flex-col h-full">
          <div className="flex items-center justify-between px-5 py-4">
            <div className="flex items-center gap-2">
              {logo ? (
                <img src={logo} alt="Logo" className="h-7 w-7 rounded-lg object-contain" />
              ) : (
                <div className="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600">
                  <Ticket className="h-4 w-4 text-white" />
                </div>
              )}
              <span className="text-base font-bold text-gray-900 dark:text-white">{system_name || 'MPC Service Desk'}</span>
            </div>
            <button onClick={toggleSidebar} className="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400">
              <X className="h-5 w-5" />
            </button>
          </div>

          <nav className="flex-1 space-y-0.5 px-3 py-2 overflow-y-auto">
            <NavLinkItem to="/" icon={LayoutDashboard} label="Dashboard" />

            {/* Administrador */}
            {isAdmin && (
              <>
                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Mesa de Ayuda</p>
                <NavLinkItem to="/tickets" icon={Ticket} label="Tickets" />
                <NavLinkItem to="/tecnicos" icon={Headphones} label="Técnicos" />

                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Gestión Institucional</p>
                <NavLinkItem to="/areas" icon={Building2} label="Áreas" />
                <NavLinkItem to="/usuarios" icon={Users} label="Personal" />
                <NavLinkItem to="/cargos" icon={Briefcase} label="Cargos" />
                <NavLinkItem to="/designaciones" icon={Link2} label="Designaciones" />
                <NavLinkItem to="/bienes" icon={Package} label="Bienes" />

                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Administración</p>
                <NavLinkItem to="/auditoria" icon={Shield} label="Auditoría" />
                <NavLinkItem to="/configuracion" icon={Settings} label="Configuración" />
              </>
            )}

            {/* Técnico */}
            {isTecnico && (
              <>
                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Mesa de Ayuda</p>
                <NavLinkItem to="/tickets" icon={Ticket} label="Mis Tickets" />
                <NavLinkItem to="/bienes" icon={Package} label="Bienes" />
                <NavLinkItem to="/perfil-area" icon={UserCircle} label="Mi Perfil" />
              </>
            )}

            {/* Área Usuaria */}
            {isAreaUser && (
              <>
                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Mesa de Ayuda</p>
                <NavLinkItem to="/tickets" icon={Ticket} label="Tickets" />
                <NavLinkItem to="/bienes/mi-area" icon={Package} label="Bienes" />

                <div className="my-2 border-t border-gray-200 dark:border-gray-700" />
                <p className="px-3 py-0.5 text-[10px] font-semibold uppercase text-gray-400 dark:text-gray-500">Mi Área</p>
                <NavLinkItem to="/perfil-area" icon={UserCircle} label="Perfil del Área" />
              </>
            )}
          </nav>

          <div className="p-3 border-t border-gray-100 dark:border-gray-700">
            <div className="rounded-xl bg-purple-50 border border-purple-100 p-3 flex items-center gap-3">
              <div className="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                <HelpCircle className="h-4 w-4 text-purple-600" />
              </div>
              <div className="min-w-0 flex-1">
                <p className="text-[11px] font-semibold text-gray-800">¿Necesitas ayuda?</p>
                <button onClick={() => setGuideOpen(true)} className="text-[11px] font-medium text-purple-600 hover:text-purple-700 transition-colors">
                  Ver guía →
                </button>
              </div>
            </div>
          </div>
        </div>
      </aside>

      {guideOpen && <HelpGuide onClose={() => setGuideOpen(false)} />}
    </>
  )
}
