import React, { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";

const FriendTrees = () => {
    const [trees, setTrees] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    useEffect(() => {
        const fetchTrees = async () => {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const friendId = urlParams.get("friend_id");

                if (!friendId) {
                    throw new Error("ID de amigo no proporcionado");
                }

                    const response = await fetch(
                        `/api/get-trees-by-owner/${friendId}`
                    );
                    if (!response.ok) {
                        throw new Error("No se pudieron cargar los árboles");
                    }

                    const data = await response.json();
                    setTrees(data);
                setLoading(false);
            } catch (err) {
                setError(err.message);
                setLoading(false);
            }
        };
    
        fetchTrees();
    }, []);

    const getImageUrl = (photoUrl) => {
        if (!photoUrl) {
            return "http://127.0.0.1:8000/storage/trees/qU8FleGU1BTdWLMAZS3sgAH0e9scZf21TEh3zIg4.jpg";
        }

        if (photoUrl.startsWith("http")) {
            return photoUrl;
        }
        return `http://127.0.0.1:8000/${photoUrl}`;
    };

    if (loading) return <div>Cargando árboles...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div className="container mx-auto px-4">
            <h1 className="text-2xl font-bold mb-4">Árboles del Amigo</h1>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {trees.map((tree) => (
                    <div
                        key={tree.id}
                        className="border rounded-lg p-4 shadow-md"
                    >
                        <img
                            src={getImageUrl(tree.photo_url)}
                            alt={`Árbol #${tree.id}`}
                            className="tree_image" 
                            onError={(e) => {
                                e.target.src =
                                    "http://127.0.0.1:8000/storage/trees/qU8FleGU1BTdWLMAZS3sgAH0e9scZf21TEh3zIg4.jpg";
                            }}
                        />
                        <h2 className="text-xl font-semibold mb-2">
                            Árbol #{tree.id}
                        </h2>
                        <div className="space-y-2 text-sm">
                            {" "}
                            {/* Reduced text size */}
                            <p>
                                <strong>Altura:</strong> {tree.height} m
                            </p>
                            <p>
                                <strong>Ubicación:</strong> {tree.location}
                            </p>
                            <p>
                                <strong>Precio:</strong> ${tree.price}
                            </p>
                            <p>
                                <strong>Estado:</strong>{" "}
                                {tree.available
                                    ? "Disponible"
                                    : "No Disponible"}
                            </p>
                            <div className="flex space-x-2">
                                       <button className="edit-btn flex-1 px-2 py-1 text-xs">
                                    <Link
                                        to={`/editTree/${tree.id}`}
                                        style={{
                                            textDecoration: "none",
                                            color: "inherit",
                                        }}
                                    >
                                        Editar
                                    </Link>
                                </button>
                                <button className="update-btn flex-1 px-2 py-1 text-xs">
                                    <Link
                                        to={`/updateTrees/${tree.id}`}
                                        style={{
                                            textDecoration: "none",
                                            color: "inherit",
                                        }}
                                    >
                                        Actualizar
                                    </Link>
                                </button>   
                                <button className="update-btn flex-1 px-2 py-1 text-xs">
                                    <Link
                                        to={`/treeLog/${tree.id}`}
                                        style={{
                                            textDecoration: "none",
                                            color: "inherit",
                                        }}
                                    >
                                        Ver historial del árbol                                  </Link>
                                </button>                           
                                </div>
                        </div>
                    </div>
                ))}
            </div>
            <a href="/adminPanel" className="back-button mt-4 inline-block">
                ← Cerrar Sesión
            </a>
        </div>
    );
};

export default FriendTrees;

