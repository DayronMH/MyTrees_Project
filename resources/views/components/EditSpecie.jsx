import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';

export const EditSpecie = () => {
  const { id } = useParams(); 
  const [specie, setSpecie] = useState({
    commercial_name: '',
    scientific_name: '',
    description: '',
  });

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchSpecieDetails = async () => {
      try {
        const response = await fetch(`/api/get-specie/${id}`);
        if (!response.ok) {
          throw new Error('No se pudo obtener la información de la especie');
        }
        const data = await response.json();
        setSpecie(data);
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchSpecieDetails();
  }, [id]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setSpecie(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`/api/update-specie/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(specie)
      });

      if (!response.ok) {
        throw new Error('No se pudo actualizar la especie');
      }

      window.location.href = '/adminPanel';
    } catch (err) {
      setError(err.message);
    }
  };

  const handleCancel = () => {
    window.location.href = '/adminPanel';
  };

  if (loading) return <div>Cargando...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div className="edit-specie-container">
      <h1>Editar Especie</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Nombre Comercial</label>
          <input
            type="text"
            name="commercial_name"
            value={specie.commercial_name}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>Nombre Científico</label>
          <input
            type="text"
            name="scientific_name"
            value={specie.scientific_name}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>Descripción</label>
          <textarea
            name="description"
            value={specie.description}
            onChange={handleChange}
          />
        </div>

        <div className="form-actions">
          <button type="submit" className="save-btn">Guardar Cambios</button>
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

export default EditSpecie;