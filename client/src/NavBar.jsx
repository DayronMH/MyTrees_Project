import React from "react";
import { Link, Outlet, useLocation } from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.min.css';

export const Navbar = () => {
  const { state } = useLocation();
  return (
    <>
      <header className="header header-styler">
        <div className="nav-container">
          <h1 className="mb-0">
            <Link to="/" className="navbar-brand d-flex align-items-center">
              <img
                src="../public/images/logo.png"
                alt="logo"
                className="logo-img me-2"
                style={{ width: '140px', height: '90px' }}
              />
            </Link>
          </h1>

          {state?.logged ? (
            <div className="user d-flex align-items-center">
              <span className="name me-3 text-dark fw-semibold">{state?.name}</span>
              <button className="btn btn-outline-secondary btn-sm">Cerrar Sesión</button>
            </div>
          ) : (
            <nav className="d-flex gap-3">
              <Link to="/login" className="btn btn-outline btn-lg border-2">Login</Link>

              <Link to="/register" className="btn btn-nav">Register</Link>
          

            </nav>
          )}
        </div>
      </header>
      <Outlet />
    </>
  );
};