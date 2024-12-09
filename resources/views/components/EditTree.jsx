import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';

export const EditTree = () => {
  const { id } = useParams(); 
  const [tree, setTree] = useState({
    height: '',
    species_id: '',
    location: '',
    available: true,

  });

  const [speciesList, setSpeciesList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchTreeDetails = async () => {
      try {
        const treeResponse = await fetch(`/api/get-tree/${id}`);
        if (!treeResponse.ok) {
          throw new Error('No se pudo obtener la información del árbol');
        }
        const treeData = await treeResponse.json();
        setTree(treeData);

        const speciesResponse = await fetch('/api/get-all-species');
        if (!speciesResponse.ok) {
          throw new Error('No se pudo obtener la lista de especies');
        }
        const speciesData = await speciesResponse.json();
        setSpeciesList(speciesData);

        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchTreeDetails();
  }, [id]);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setTree(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`/api/edit-tree/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(tree)
      });

      if (!response.ok) {
        throw new Error('No se pudo actualizar el árbol');
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
    <div className="edit-tree-container">
      <h1>Editar Árbol</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Altura</label>
          <input
            type="number"
            name="height"
            value={tree.height}
            onChange={handleChange}
            step="0.01"
            required
          />
        </div>

        <div className="form-group">
          <label>Especie</label>
          <select
            name="species_id"
            value={tree.species_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccionar Especie</option>
            {speciesList.map((species) => (
              <option key={species.id} value={species.id}>
                {species.commercial_name}
              </option>
            ))}
          </select>
        </div>

        <div className="form-group">
          <label>Ubicación</label>
          <input
            type="text"
            name="location"
            value={tree.location}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>
            <input
              type="checkbox"
              name="available"
              checked={tree.available}
              onChange={handleChange}
            />
            Disponible
          </label>
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

export default EditTree;
