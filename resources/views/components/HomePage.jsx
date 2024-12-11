import React from 'react';
import { Link } from 'react-router-dom';

export const HomePage = () => {
  return (
    <div className="homepage-container">
      <section className="hero-section">
        <img src="/images/homePage.jpg" alt="homepage" className="hero-image" />
        <div className="hero-content-container">
          <h1 className="hero-title">Planta un árbol y ayuda al medio ambiente</h1>
          <p className="hero-description">
            Compra un árbol y nosotros lo plantamos en un lugar específico, contribuyendo a la reforestación.
          </p>
          <Link to="/login" className="hero-cta-button">
            Compra tu árbol ahora
          </Link>
        </div>
      </section>

      <section className="benefits-section">
  <h2 className="benefits-title">¿Por qué plantar un árbol con nosotros?</h2>
  <div className="benefits-grid">
    {[
      {
        title: 'Personalización',
        desc: 'Elige el árbol que deseas plantar',
        icon: 'images/personalized.png', // Ícono para "Personalización"
      },
      {
        title: 'Contribución',
        desc: 'Contribuye al cuidado del medio ambiente',
        icon: 'images/contribution.webp', // Ícono para "Contribución"
      },
      {
        title: 'Seguimiento',
        desc: 'Recibe un seguimiento sobre tu árbol plantado',
        icon: 'images/tracking.webp', // Ícono para "Seguimiento"
      },
    ].map((benefit, index) => (
      <div key={index} className="benefit-item">
        <div className="benefit-icon-container">
          <img
            src={benefit.icon}
            alt={benefit.title}
            className="benefit-icon"
          />
        </div>
              <h3 className="benefit-title">{benefit.title}</h3>
              <p className="benefit-description">{benefit.desc}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="how-it-works-section">
        <h2 className="how-it-works-title">¿Cómo funciona?</h2>
        <div className="how-it-works-grid">
          {[
            'Elige tu árbol',
            'Selecciona un lugar de plantación',
            'Nosotros lo plantamos',
            'Recibe el seguimiento de tu árbol'
          ].map((step, index) => (
            <div key={index} className="how-it-works-step">
              <div className="how-it-works-step-number">{index + 1}</div>
              <h3 className="how-it-works-step-title">{step}</h3>
            </div>
          ))}
        </div>
      </section>

      <section className="testimonials-section">
        <h2 className="testimonials-title">Testimonios</h2>
        <div className="testimonial-card">
          <p className="testimonial-text">
            "Excelente servicio, pude ver el árbol plantado en mi nombre. ¡Me encanta que contribuyo al medio ambiente!"
          </p>
          <p className="testimonial-author">- Juan P.</p>
        </div>
      </section>

      <section className="cta-section">
        <h2 className="cta-title">¿Listo para hacer la diferencia?</h2>
      </section>

      <footer className="footer">
        <p className="footer-contact">Contacto: info@empresa.com</p>
        <p className="footer-social">Síguenos en redes sociales</p>
        <p className="footer-copyright">&copy; 2024 Empresa de Árboles</p>
      </footer>
    </div>
  );
};

export default HomePage;