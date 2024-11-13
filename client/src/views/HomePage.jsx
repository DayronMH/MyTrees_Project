import React from 'react'
import { Link } from 'react-router-dom';

export const HomePage = () => {
  return (
    <div>
      {/* Hero Section */}
      <header className="hero-section">
        <img src="../../public/images/homePage.jpg" alt="homepage" />
        <h1>Planta un árbol y ayuda al medio ambiente</h1>
        <p>Compra un árbol y nosotros lo plantamos en un lugar específico, contribuyendo a la reforestación.</p>
        <Link to="/shop" className="cta-button">Compra tu árbol ahora</Link>
      </header>

      {/* Benefits Section */}
      <section className="benefits">
        <h2>¿Por qué plantar un árbol con nosotros?</h2>
        <div className="benefits-container">
          <div className="benefit-item">
            <img src="icon1.png" alt="Personalización" />
            <p>Elige el árbol que deseas plantar</p>
          </div>
          <div className="benefit-item">
            <img src="icon2.png" alt="Contribución al medio ambiente" />
            <p>Contribuye al cuidado del medio ambiente</p>
          </div>
          <div className="benefit-item">
            <img src="icon3.png" alt="Seguimiento" />
            <p>Recibe un seguimiento sobre tu árbol plantado</p>
          </div>
        </div>
      </section>

      {/* How It Works Section */}
      <section className="how-it-works">
        <h2>¿Cómo funciona?</h2>
        <div className="steps">
          <div className="step">
            <h3>Paso 1</h3>
            <p>Elige tu árbol</p>
          </div>
          <div className="step">
            <h3>Paso 2</h3>
            <p>Selecciona un lugar de plantación</p>
          </div>
          <div className="step">
            <h3>Paso 3</h3>
            <p>Nosotros lo plantamos</p>
          </div>
          <div className="step">
            <h3>Paso 4</h3>
            <p>Recibe el seguimiento de tu árbol</p>
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="testimonials">
        <h2>Testimonios</h2>
        <p>"Excelente servicio, pude ver el árbol plantado en mi nombre. ¡Me encanta que contribuyo al medio ambiente!" - Juan P.</p>
      </section>

      {/* Call to Action */}
      <section className="cta-final">
        <Link to="/shop" className="cta-button">Planta un árbol ahora</Link>
      </section>

      {/* Footer */}
      <footer>
        <p>Contacto: info@empresa.com</p>
        <p>Síguenos en redes sociales</p>
        <p>&copy; 2024 Empresa de Árboles</p>
      </footer>
    </div>
  );
};

export default HomePage;
