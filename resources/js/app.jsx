import { Routes, Route, Navigate } from 'react-router-dom';
import { Navbar } from "../NavBar";
import { HomePage, Login, Register } from "../views";

export const App = () => {
  // Función para verificar autenticación
  const isAuthenticated = () => {
    return !!localStorage.getItem('token'); // O tu método de verificación de auth
  };

  // Componente para rutas protegidas
  const PrivateRoute = ({ children }) => {
    return isAuthenticated() ? children : <Navigate to="/login" replace />;
  };

  // Componente para rutas públicas (redirige a dashboard si está autenticado)
  const PublicRoute = ({ children }) => {
    return !isAuthenticated() ? children : <Navigate to="/dashboard" replace />;
  };

  return (
    <Routes>
      <Route path="/" element={<Navbar />}>
        {/* Rutas públicas */}
        <Route index element={
          <PublicRoute>
            <HomePage />
          </PublicRoute>
        } />
        <Route path="/login" element={
          <PublicRoute>
            <Login />
          </PublicRoute>
        } />
        <Route path="/register" element={
          <PublicRoute>
            <Register />
          </PublicRoute>
        } />

        {/* Rutas protegidas */}
        <Route path="/dashboard" element={
          <PrivateRoute>
            {/* Tu componente de Dashboard */}
          </PrivateRoute>
        } />

        {/* Ruta por defecto - redirige a home si no está autenticado */}
        <Route path="*" element={
          isAuthenticated() ? 
          <Navigate to="/dashboard" replace /> : 
          <Navigate to="/" replace />
        } />
      </Route>
    </Routes>
  );
};