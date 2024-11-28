import React, { useState, useEffect } from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

export const Navbar = () => {
  const { state } = useLocation();
  const [soldCount, setSoldCount] = useState(0);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('api/get-sold-trees');
        const textResponse = await response.text();
        const data = JSON.parse(textResponse);
        setSoldCount(data.soldTrees);
      } catch (error) {
        setSoldCount(0);
      }
    };
  
    fetchData();
  }, []);
  
  return (
    <div className="app-container">
      <header className="header header-nav">
        <div className="nav-container">
          <h1 className="mb-0">
            <Link to="/" className="navbar-brand">
              <img
                src="../../public/images/logo.png"
                alt="logo"
                className="logo-img"
              />
            </Link>
          </h1>

          <nav>
            <div className="div-counter">
              <span className="counter">{soldCount}</span> {/* Muestra la cantidad de árboles vendidos */}
              <br />
              <h3 className="text">Árboles Vendidos</h3> {/* Título actualizado */}
            </div>
            <Link to="/about" className="btn btn-nav">
              <i className="fas fa-info-circle"></i> Acerca de nosotros
            </Link>
          </nav>
          {state?.logged ? (
            <div className="user">
              <span className="name">{state?.name}</span>
              <button className="btn btn-outline-secondary">
                Cerrar Sesión
              </button>
            </div>
          ) : (
            <div className="user">
              <Link to="/login" className="btn btn-outline">
                Login
              </Link>
              <Link to="/register" className="btn btn-nav">
                Register
              </Link>
            </div>
          )}
        </div>
      </header>

      <Outlet />
    </div>
  );
};
