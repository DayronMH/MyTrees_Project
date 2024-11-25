import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { createRoot } from 'react-dom/client';

// Import your components
import { Navbar } from "../views/NavBar";
import { HomePage, Login, Register, AdminDashboard } from "../views/components";

export const App = () => {
  return (
    <Router>
      <Navbar />
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/admin" element={<AdminDashboard />} />
      </Routes>
    </Router>
  );
}

// Render the app
const container = document.getElementById('app');
const root = createRoot(container);
root.render(<App />);

export default App;