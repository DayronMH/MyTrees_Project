import React from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import { createRoot } from 'react-dom/client';
import { Navbar } from "../views/NavBar";
import { HomePage, Login, Register, AdminDashboard } from "../views/components";

export const App = () => {
  const location = useLocation();
  
  // Lista de rutas donde quieres mostrar el Navbar
  const navbarRoutes = ['/', '/admin'];
  
  return (
    <>
      {navbarRoutes.includes(location.pathname) && <Navbar />}
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/admin" element={<AdminDashboard />} />
      </Routes>
    </>
  );
}

const container = document.getElementById('app');
const root = createRoot(container);
root.render(
  <Router>
    <App />
  </Router>
);

export default App;