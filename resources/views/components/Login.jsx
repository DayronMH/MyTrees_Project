import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export const Login = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });

  const { email, password } = formData;

  const onInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value
    }));
  };

  const onResetForm = () => {
    setFormData({
      email: '',
      password: '',
    });
  };

  const onLogin = (e) => {
    e.preventDefault();
    navigate('/adminDashboard', {
      replace: true,
      state: {
        logged: true,
      },
    });
    onResetForm();
  };

  return (
    <div className="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
      <div className="card shadow border-0" style={{ maxWidth: '400px', width: '100%' }}>
        <div className="card-body p-4">
          {/* Logo y título */}
          <div className="text-center mb-4">
            <div className="d-inline-block bg-success bg-opacity-10 rounded-circle p-3 mb-3">
              <span style={{ fontSize: '2.5rem' }}>🌳</span>
            </div>
            <h1 className="text-success fw-bold h3">Iniciar sesión</h1>
          </div>

          <form onSubmit={onLogin}>
            <div className="form-floating mb-3">
              <input
                type="email"
                name="email"
                id="email"
                className="form-control"
                value={email}
                onChange={onInputChange}
                autoComplete="off"
                placeholder="Email"
                required
              />
              <label htmlFor="email">Email</label>
            </div>

            <div className="form-floating mb-4">
              <input
                type="password"
                name="password"
                id="password"
                className="form-control"
                value={password}
                onChange={onInputChange}
                autoComplete="off"
                placeholder="Password"
                required
              />
              <label htmlFor="password">Password</label>
            </div>

            <button
              type="submit"
              className="btn btn-success w-100 py-2"
            >
              Iniciar Sesión
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Login;