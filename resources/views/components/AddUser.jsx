import React, { useState } from 'react';

export const AddUser = () => {
  const [newUser, setNewUser] = useState({
    name: '',
    email: '',
    password: '',
    phone: '',
    address: '',
    country: '',
    role: '',
  });

  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setNewUser(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setSuccess(false);
    setIsSubmitting(true);

    try {
      const response = await fetch('/api/create-user', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newUser),
      });

      const responseText = await response.text();
      console.log('Respuesta del servidor:', responseText);

      let responseData;
      try {
        responseData = JSON.parse(responseText);
      } catch (parseError) {
        console.error('Error al parsear JSON:', parseError);
        throw new Error(`Respuesta inesperada del servidor: ${responseText}`);
      }

      if (response.ok) {
        setSuccess(true);
        setNewUser({
          name: '',
          email: '',
          password: '',
          phone: '',
          address: '',
          country: '',
          role: '',
        });
        setTimeout(() => {
          window.location.href = '/adminPanel';  
        }, 1500);
      } else {
        throw new Error(responseData.message || 'Error al crear el usuario');
      }
    } catch (err) {
      setError(err.message);
      console.error('Error completo:', err);
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleCancel = () => {
    window.location.href = '/users';
  };

  return (
    <div className="add-tree-container">
    <h1>Crear Nuevo Usuario</h1>

    {error && <div className="error-message">{error}</div>}
    {success && <div className="success-message">Usuario creado exitosamente</div>}

    <form onSubmit={handleSubmit}>
      <div className="form-group">
        <label>Nombre</label>
        <input
          type="text"
          name="name"
          value={newUser.name}
          onChange={handleChange}
          required
          placeholder="Ingrese el nombre del usuario"
        />
      </div>

      <div className="form-group">
        <label>Email</label>
        <input
          type="email"
          name="email"
          value={newUser.email}
          onChange={handleChange}
          required
          placeholder="Ingrese el correo electrónico"
        />
      </div>

      <div className="form-group">
        <label>Contraseña</label>
        <input
          type="password"
          name="password"
          value={newUser.password}
          onChange={handleChange}
          required
          placeholder="Ingrese la contraseña"
        />
      </div>

      <div className="form-group">
        <label>Teléfono</label>
        <input
          type="text"
          name="phone"
          value={newUser.phone}
          onChange={handleChange}
          required
          placeholder="Ingrese el teléfono"
        />
      </div>

      <div className="form-group">
        <label>Dirección</label>
        <input
          type="text"
          name="address"
          value={newUser.address}
          onChange={handleChange}
          required
          placeholder="Ingrese la dirección"
        />
      </div>

      <div className="form-group">
        <label>País</label>
        <input
          type="text"
          name="country"
          value={newUser.country}
          onChange={handleChange}
          required
          placeholder="Ingrese el país"
        />
      </div>

      <div className="form-group">
        <label>Rol</label>
        <select
          name="role"
          value={newUser.role}
          onChange={handleChange}
          required
        >
          <option value="">Seleccione un rol</option>
          <option value="admin">Administrador</option>
          <option value="operator">Operador</option>
        </select>
      </div>

      <div className="form-actions">
        <button 
          type="submit"
          className="create-btn"
          disabled={isSubmitting || !newUser.name || !newUser.email || !newUser.password || !newUser.phone || !newUser.address || !newUser.country || !newUser.role}
        >
          {isSubmitting ? 'Guardando...' : 'Crear Usuario'}
        </button>
        <button 
          type="button"
          className="cancel-btn"
          onClick={handleCancel}
          disabled={isSubmitting}
        >
          Cancelar
        </button>
      </div>
    </form>
  </div>
);
};

export default AddUser;