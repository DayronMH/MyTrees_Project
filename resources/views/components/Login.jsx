import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Link } from 'react-router-dom';


export const Login = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [error, setError] = useState(''); // Este estado almacenará el mensaje de error
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevState) => ({
      ...prevState,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);
    setError(''); // Limpiar cualquier mensaje de error anterior

    try {
      const response = await fetch('api/auth-user', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(formData),
      });
    
      // Verificar si la respuesta HTTP es correcta
      if (!response.ok) {
        // Si la respuesta no es exitosa (estatus diferente de 200)
        const errorData = await response.json(); // Obtener el error desde el backend
        throw new Error(errorData.error || 'Error desconocido'); // Usar el mensaje de error del backend
      }
    
      const data = await response.json();
      if (data.success) {
        localStorage.setItem('authToken', 'logged-in');
        localStorage.setItem('userRole', data.user.role);
        localStorage.setItem('userEmail', data.user.email);
        localStorage.setItem('userName', data.user.name);
        localStorage.setItem('userId', data.user.id);
    
        navigate(data.redirect || '/dashboard');
      } else {
        setError(data.message || 'Credenciales incorrectas.');
      }
    } catch (error) {
      console.error('Error:', error);
      setError(error.message || 'Ocurrió un error inesperado.'); // Mostrar el error recibido
    } finally {
      setIsSubmitting(false); // Finaliza el estado de carga
    }
  };

  return (
    <div className="login-wrapper">
      <div className="login-container">
        <form onSubmit={handleSubmit}>
          <h2 className="login-title">Iniciar Sesión</h2>

          {error && <div className="error-message">{error}</div>}

          <div className="form-group">
            <label htmlFor="email">Correo Electrónico</label>
            <input
              type="email"
              id="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
              disabled={isSubmitting}
            />
          </div>

          <div className="form-group">
            <label htmlFor="password">Contraseña</label>
            <input
              type="password"
              id="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              required
              disabled={isSubmitting}
            />
          </div>

          <button
            type="submit"
            className={`login-button ${isSubmitting ? 'disabled' : ''}`}
            disabled={isSubmitting}
          >
            {isSubmitting ? 'Iniciando sesión...' : 'Iniciar sesión'}
          </button>

          <h4>No tienes cuenta?</h4>
          <Link to="/register">Registrate</Link>
        </form>
      </div>
    </div>
  );
};

export default Login;
