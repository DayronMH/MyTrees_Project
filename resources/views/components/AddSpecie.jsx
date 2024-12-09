import React, { useState } from 'react';

export const AddSpecie = () => {
  const [newSpecie, setNewSpecie] = useState({
    commercial_name: '',
    scientific_name: '',
    description: '',
  });

  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setNewSpecie(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setSuccess(false);

    try {
      const response = await fetch('/api/create-specie', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newSpecie)
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'No se pudo crear la especie');
      }

      const responseData = await response.json();
      
      // Resetear formulario
      setNewSpecie({
        commercial_name: '',
        scientific_name: '',
        description: '',
      });

      setSuccess(true);

      
      setTimeout(() => {
        window.location.href = '/adminPanel';
      }, 1500);

    } catch (err) {
      setError(err.message);
    }
  };

  const handleCancel = () => {
    window.location.href = '/adminPanel';
  };

  return (
    <div className="create-specie-container">
      <h1>Crear Nueva Especie</h1>
      
      {error && (
        <div className="error-message">
          {error}
        </div>
      )}

      {success && (
        <div className="success-message">
          Especie creada exitosamente
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Nombre Comercial</label>
          <input
            type="text"
            name="commercial_name"
            value={newSpecie.commercial_name}
            onChange={handleChange}
            required
            placeholder="Ingrese el nombre comercial"
          />
        </div>

        <div className="form-group">
          <label>Nombre Científico</label>
          <input
            type="text"
            name="scientific_name"
            value={newSpecie.scientific_name}
            onChange={handleChange}
            required
            placeholder="Ingrese el nombre científico"
          />
        </div>

        <div className="form-group">
          <label>Descripción</label>
          <textarea
            name="description"
            value={newSpecie.description}
            onChange={handleChange}
            placeholder="Ingrese una descripción de la especie"
            rows="4"
          />
        </div>

        <div className="form-actions">
          <button 
            type="submit" 
            className="create-btn"
            disabled={!newSpecie.commercial_name || !newSpecie.scientific_name}
          >
            Crear Especie
          </button>
          <button 
            type="button" 
            className="cancel-btn" 
            onClick={handleCancel}
          >
            Cancelar
          </button>
        </div>
      </form>
    </div>
  );
};

export default AddSpecie;