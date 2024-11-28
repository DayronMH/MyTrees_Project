import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

export const Register = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
  });

  // Manejar los cambios en los inputs
  const onInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  // Manejar el envío del formulario
  const onRegister = (e) => {
    e.preventDefault();

    // Aquí puedes hacer la llamada a la API para registrar al usuario
    console.log("Datos enviados:", formData);

    // Redirigir al usuario después de registrarse
    navigate("/login");
  };

  return (
    <div className="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
      <form onSubmit={onRegister} className="p-4 bg-white shadow-sm rounded">
        <h1 className="mb-4">Registro</h1>

        {/* Campo Nombre */}
        <div className="mb-3">
          <label htmlFor="name" className="form-label">
            Nombre:
          </label>
          <input
            type="text"
            name="name"
            id="name"
            className="form-control"
            value={formData.name}
            onChange={onInputChange}
            required
          />
        </div>

        {/* Campo Email */}
        <div className="mb-3">
          <label htmlFor="email" className="form-label">
            Email:
          </label>
          <input
            type="email"
            name="email"
            id="email"
            className="form-control"
            value={formData.email}
            onChange={onInputChange}
            required
          />
        </div>

        {/* Campo Password */}
        <div className="mb-3">
          <label htmlFor="password" className="form-label">
            Contraseña:
          </label>
          <input
            type="password"
            name="password"
            id="password"
            className="form-control"
            value={formData.password}
            onChange={onInputChange}
            required
          />
        </div>

        {/* Botón de registro */}
        <button type="submit" className="btn btn-primary w-100">
          Registrarse
        </button>
      </form>
    </div>
  );
};

export default Register;
