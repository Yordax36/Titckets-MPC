import { Link } from 'react-router-dom'
import { Home } from 'lucide-react'

export default function NotFoundPage() {
  return (
    <div className="flex min-h-screen flex-col items-center justify-center bg-gray-50 dark:bg-gray-950">
      <div className="text-center">
        <p className="text-6xl font-bold text-gray-300 dark:text-gray-700">404</p>
        <h1 className="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Página no encontrada</h1>
        <p className="mt-2 text-gray-500 dark:text-gray-400">La página que buscas no existe.</p>
        <Link
          to="/"
          className="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
          <Home className="h-4 w-4" />
          Volver al Inicio
        </Link>
      </div>
    </div>
  )
}
