import { create } from 'zustand'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'

interface SettingsState {
  system_name: string
  logo: string | null
  fetchSettings: () => Promise<void>
}

const useSettingsStore = create<SettingsState>((set) => ({
  system_name: 'MPC Service Desk',
  logo: null,

  fetchSettings: async () => {
    try {
      const token = localStorage.getItem('token')
      const res = await fetch(`${API_URL}/api/v1/settings`, {
        headers: token ? { Authorization: `Bearer ${token}` } : {},
      })
      if (!res.ok) return
      const d = await res.json()
      set({
        system_name: d?.system_name || 'MPC Service Desk',
        logo: d?.logo ? `${API_URL}/${d.logo}` : null,
      })
    } catch {
      // keep defaults
    }
  },
}))

export default useSettingsStore
