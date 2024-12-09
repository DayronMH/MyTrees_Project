import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

export const AddTree = () => {
  const navigate = useNavigate();

  const [newTree, setNewTree] = useState({
    species_id: "",
    location: "",
    price: "",
    photo: null,
    photo_url: "",
  });

  const [species, setSpecies] = useState([]);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Cargar especies
  useEffect(() => {
    const fetchSpecies = async () => {
      try {
        const response = await fetch("/api/get-all-species");
        const data = await response.json();
        setSpecies(data);
      } catch (err) {
        setError("No se pudieron cargar las especies");
      }
    };

    fetchSpecies();
  }, []);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === "photo") {
      setNewTree((prev) => ({
        ...prev,
        photo: files[0],
      }));
    } else {
      setNewTree((prev) => ({
        ...prev,
        [name]: value,
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setSuccess(false);
    setIsSubmitting(true);
    
    // Crear FormData para enviar los datos, incluyendo la imagen
    const formData = new FormData();
    formData.append("species_id", newTree.species_id);
    formData.append("location", newTree.location);
    formData.append("price", newTree.price);
    if (newTree.photo) {
      formData.append("photo", newTree.photo); // Adjuntar la foto al FormData
    }

    try {
      const response = await fetch("/api/create-tree", {
        method: "POST",
        body: formData,
      });

      const responseData = await response.json();

      if (response.ok) {
        setSuccess(true);
        setNewTree({
          species_id: "",
          location: "",
          price: "",
          photo: null,
          photo_url: "",
        });
        setTimeout(() => navigate("/adminPanel"), 1500); // Redirigir después de 1.5 segundos
      } else {
        throw new Error(responseData.message || "Error al crear el árbol");
      }
    } catch (err) {
      setError(err.message);
      console.error("Error al enviar el formulario:", err);
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleCancel = () => {
    navigate("/trees");
  };

  return (
    <div className="add-tree-container">
      <h1>Crear Nuevo Árbol</h1>

      {error && <div className="error-message">{error}</div>}

      {success && (
        <div className="success-message">
          Árbol creado exitosamente
        </div>
      )}

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        <div className="form-group">
          <label>Especie</label>
          <select
            name="species_id"
            value={newTree.species_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccione una especie</option>
            {species.map((spec) => (
              <option key={spec.id} value={spec.id}>
                {spec.commercial_name}
              </option>
            ))}
          </select>
        </div>

        <div className="form-group">
          <label>Ubicación</label>
          <input
            type="text"
            name="location"
            value={newTree.location}
            onChange={handleChange}
            required
            placeholder="Ingrese la ubicación del árbol"
          />
        </div>

        <div className="form-group">
          <label>Precio</label>
          <input
            type="number"
            name="price"
            value={newTree.price}
            onChange={handleChange}
            required
            placeholder="Ingrese el precio"
            min="0"
            step="0.01"
          />
        </div>

        <div className="form-group">
          <label>Foto</label>
          <input
            type="file"
            name="photo"
            accept="image/*"
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-actions">
          <button
            type="submit"
            className="create-btn"
            disabled={
              isSubmitting ||
              !newTree.species_id ||
              !newTree.location ||
              !newTree.price ||
              !newTree.photo
            }
          >
            {isSubmitting ? "Guardando..." : "Crear Árbol"}
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

export default AddTree;
