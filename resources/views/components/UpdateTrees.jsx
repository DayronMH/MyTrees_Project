import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";

export const UpdateTrees = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const [tree, setTree] = useState({
    height: '',
    photo: null,
    photo_url: '',
  });

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const fetchTreeDetails = async () => {
      try {
        const response = await fetch(`/api/get-tree/${id}`);
        if (!response.ok) {
          throw new Error("No se pudo obtener la información del árbol");
        }
        const treeData = await response.json();

        setTree({
          height: treeData.height || '',
          photo_url: treeData.photo_url || '',
          photo: null,
        });
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchTreeDetails();
  }, [id]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === "photo") {
      setTree((prev) => ({
        ...prev,
        photo: files[0],
      }));
    } else {
      setTree((prev) => ({
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("height", tree.height);
    if (tree.photo) {
      formData.append("photo", tree.photo);
    }

    try {
      const response = await fetch(`http://127.0.0.1:8000/api/update-tree/${id}`, {
        method: "POST",
        body: formData,
    });
    

      const responseData = await response.json();

      if (responseData.error === false) {
        setSuccess(true);
        setTimeout(() => navigate("/adminPanel"), 1500);
      } else {
        let errorMessage = responseData.message || "Error al actualizar el árbol";
        if (responseData.errors) {
          const validationErrors = Object.values(responseData.errors).flat();
          errorMessage = validationErrors.join(", ");
        }
        setError(errorMessage);
      }
    } catch (err) {
      setError("Error de conexión. Intente nuevamente.");
    } finally {
      setIsSubmitting(false);
    }
  };

  if (loading) return <div>Cargando...</div>;
  return (
    <div className="update-trees-container">
      <h1 className="update-trees-title">Actualizar Árbol</h1>

      {success && (
        <div className="alert alert-success">
          Árbol actualizado exitosamente
        </div>
      )}

      {error && <div className="alert alert-danger">{error}</div>}

      <form onSubmit={handleSubmit} className="update-trees-form">
        <div className="form-group">
          <label htmlFor="height" className="form-label">
            Altura
          </label>
          <input
            id="height"
            type="number"
            name="height"
            className="form-input"
            value={tree.height}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="photo" className="form-label">
            Foto
          </label>
          <input
            id="photo"
            type="file"
            name="photo"
            className="form-input"
            accept="image/*"
            onChange={handleChange}
          />

          {tree.photo_url && (
            <div className="image-preview">
              <img
                src={tree.photo_url}
                alt="Vista previa"
                className="image-preview-thumbnail"
              />
            </div>
          )}
        </div>

        <div className="form-actions">
          <button
            type="submit"
            className="btn btn-primary"
            disabled={isSubmitting}
          >
            {isSubmitting ? "Guardando..." : "Guardar Cambios"}
          </button>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={() => navigate("/adminPanel")}
          >
            Cancelar
          </button>
        </div>
      </form>
    </div>
  );
};

export default UpdateTrees;