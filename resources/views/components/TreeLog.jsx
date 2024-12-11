import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";

const TreeLog = () => {
  const { id } = useParams(); // Obtén el ID del árbol desde la URL.
  const [updates, setUpdates] = useState([]); // Estado para guardar las actualizaciones.
  const [loading, setLoading] = useState(true); // Estado para manejar el estado de carga.
  const [error, setError] = useState(null); // Estado para manejar errores.

  useEffect(() => {
    const fetchUpdates = async () => {
      try {
        // Llamada al API con el ID del árbol
        const response = await fetch(
          `/api/get-tree-updates-by-id/${id}`
        );
        
        if (!response.ok) {
          throw new Error("Error fetching updates");
        }
        
        const data = await response.json();
        
        // Check if the API response is an array or an object
        if (Array.isArray(data)) {
          setUpdates(data);
        } else {
          setUpdates([data]); // Convert the object to an array
        }
      } catch (err) {
        console.error("Fetch error:", err);
        setError(err.message); // Maneja cualquier error
      } finally {
        setLoading(false); // Finaliza el estado de carga.
      }
    };

    fetchUpdates();
  }, [id]); // Ejecuta el efecto cuando el ID cambie.

  if (loading) return <div>Cargando...</div>; // Muestra mensaje mientras carga.
  
  if (error) return <div>Error: {error}</div>; // Muestra mensaje de error si ocurre.
  
  // Add a check for empty updates
  if (updates.length === 0) {
    return (
      <div>
        <h1>Actualizaciones del Árbol ID: {id}</h1>
        <p>No hay actualizaciones disponibles para este árbol.</p>
      </div>
    );
  }

  return (
    <div>
      <h1>Actualizaciones del Árbol ID: {id}</h1>
      
      <div
        className="cards-container"
        style={{ display: "flex", flexWrap: "wrap", gap: "20px" }}
      >
        {updates.map((update) => (
          <div
            key={update.id}
            className="card"
            style={{
              width: "200px",
              border: "1px solid #ccc",
              padding: "10px",
              borderRadius: "8px",
            }}
          >
            <h3>ID de Actualización: {update.id}</h3>
            <p>
              <strong>Fecha de Actualización:</strong> {update.update_date}
            </p>
            <p>
              <strong>Altura (cm):</strong> {update.height}
            </p>
            <p>
              <strong>Estado:</strong>{" "}
              {update.status === 1 ? "Disponible" : "No Disponible"}
            </p>
            <div>
              {update.image_url ? (
                <img
                  src={update.image_url}
                  alt={`Actualización ${update.id}`}
                  style={{
                    width: "100%",
                    height: "auto",
                    borderRadius: "8px",
                  }}
                />
              ) : (
                <p>Sin Imagen</p>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default TreeLog;