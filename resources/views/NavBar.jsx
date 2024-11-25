import React, { useState, useEffect } from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

export const Navbar = () => {
  const { state } = useLocation();
  const [count, setCount] = useState(0);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('/api?route=data');
        const data = await response.json();
        setCount(data.plantedTrees);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    fetchData();
  }, []);

  return (
    <div className="app-container">
      <head>
        <link
          rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        ></link>
      </head>
      <header className="header header-nav">
        <div className="nav-container">
          <h1 className="mb-0">
            <Link to="/" className="navbar-brand">
              <img
                src="../public/images/logo.png"
                alt="logo"
                className="logo-img"
              />
            </Link>
          </h1>

          <nav>
            <div className="div-counter">
              <span className="counter">{count}</span>
              <br />
              <h3 className="text">Arboles Plantados</h3>
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