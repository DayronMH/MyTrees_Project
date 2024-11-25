import React from 'react'
import { Link } from 'react-router-dom';

export const HomePage = () => {
  return (
    <div className="root">
    <div className="container">
      <section className="relative h-[200px] bg-gray-900 text-white">
        <img 
          src="../../public/images/homePage.jpg" 
          alt="homepage" 
          className="hp-image"
        />
        <div className="container">
          <h1 className="text">
            Planta un árbol y ayuda al medio ambiente
          </h1>
          <p className="text-xl mb-8 max-w-2xl">
            Compra un árbol y nosotros lo plantamos en un lugar específico, contribuyendo a la reforestación.
          </p>
          <Link 
            to="/shop" 
            className="inline-flex px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors w-fit"
          >
            Compra tu árbol ahora
          </Link>
        </div>
      </section>

      {/* Benefits Section */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center mb-16">
            ¿Por qué plantar un árbol con nosotros?
          </h2>
          <div className="grid grid-cols-3 md:grid-cols-3 gap-12">
            {[
              { title: 'Personalización', desc: 'Elige el árbol que deseas plantar' },
              { title: 'Contribución', desc: 'Contribuye al cuidado del medio ambiente' },
              { title: 'Seguimiento', desc: 'Recibe un seguimiento sobre tu árbol plantado' }
            ].map((benefit, index) => (
              <div key={index} className="text-center">
                <div className="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
                  <img src="../../public/images/personalized.png" alt={benefit.title} className="hb-img" />
                </div>
                <h3 className="text-xl font-semibold mb-4">{benefit.title}</h3>
                <p className="text-gray-600">{benefit.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center mb-16">¿Cómo funciona?</h2>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            {[
              'Elige tu árbol',
              'Selecciona un lugar de plantación',
              'Nosotros lo plantamos',
              'Recibe el seguimiento de tu árbol'
            ].map((step, index) => (
              <div key={index} className="text-center">
                <div className="w-12 h-12 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                  {index + 1}
                </div>
                <h3 className="text-lg font-medium">{step}</h3>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center mb-16">Testimonios</h2>
          <div className="max-w-3xl mx-auto bg-gray-50 p-8 rounded-2xl">
            <p className="text-lg text-gray-600 italic text-center">
              "Excelente servicio, pude ver el árbol plantado en mi nombre. ¡Me encanta que contribuyo al medio ambiente!"
            </p>
            <p className="text-center font-medium mt-4">- Juan P.</p>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="py-24 bg-teal-900 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold mb-8">¿Listo para hacer la diferencia?</h2>
          <Link 
            to="/shop" 
            className="inline-flex px-8 py-4 bg-white text-teal-900 rounded-lg hover:bg-gray-100 transition-colors font-medium"
          >
            Planta un árbol ahora
          </Link>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-gray-400 py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <p className="mb-4">Contacto: info@empresa.com</p>
          <p className="mb-4">Síguenos en redes sociales</p>
          <p>&copy; 2024 Empresa de Árboles</p>
        </div>
      </footer>
    </div>
    </div>
  );
};

export default HomePage;
