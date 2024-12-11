import React, { useState, useEffect } from "react";

export const FriendPanel = () => {
    const [userName, setUserName] = useState("");
    const [availableTrees, setAvailableTrees] = useState([]);
    const [loading, setLoading] = useState(true);
    const [trees, setTrees] = useState([]);
    const [userId, setUserId] = useState(localStorage.getItem("userId"));

    useEffect(() => {
        const fetchData = async () => {
            try {
                // Retrieve username from local storage
                const storedUserName = localStorage.getItem("userName");
                setUserName(storedUserName || "Usuario");

                // Fetch available trees
                const availableResponse = await fetch(
                    "/api/all-available-trees"
                );
                const availableData = await availableResponse.json();
                setAvailableTrees(availableData.availableTrees);

                // Fetch user's trees
                if (!userId) {
                    throw new Error("ID de usuario no proporcionado");
                }
                const response = await fetch(
                    `/api/get-trees-by-owner/${userId}`
                );
                if (!response.ok) {
                    throw new Error("No se pudieron cargar los árboles");
                }
                const data = await response.json();
                setTrees(data);
                setLoading(false);
            } catch (error) {
                console.error("Error fetching data:", error);
                setLoading(false);
            }
        };
        fetchData();
    }, [userId]);

    /**
     * Handles tree purchase process
     * @param {number} treeId - ID of the tree to be purchased
     */
    const handleBuyTree = async (treeId) => {
        try {
            const response = await fetch(`/api/buy-tree/${treeId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    owner_id: userId,
                }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Update available trees list
                setAvailableTrees((prevTrees) =>
                    prevTrees.filter((tree) => tree.tree_id !== treeId)
                );
                alert("Árbol comprado exitosamente!");
            } else {
                alert(result.error || "Error al comprar el árbol");
            }
        } catch (error) {
            console.error("Error al comprar el árbol:", error);
            alert("Ocurrió un error inesperado. Inténtalo de nuevo más tarde.");
        }
    };

    /**
     * Generates image URL with fallback
     * @param {string} photoUrl - URL of the tree photo
     * @returns {string} Processed image URL
     */
    const getImageUrl = (photoUrl) => {
        if (!photoUrl) {
            return "http://127.0.0.1:8000/storage/trees/qU8FleGU1BTdWLMAZS3sgAH0e9scZf21TEh3zIg4.jpg";
        }
        return photoUrl.startsWith("http")
            ? photoUrl
            : `http://127.0.0.1:8000/${photoUrl}`;
    };

    // Loading state
    if (loading) {
        return <div>Cargando...</div>;
    }

    return (
        <div className="dashboard-container">
            <div className="dashboard-header">
                <h1>Bienvenido, {userName}</h1>
            </div>

            {/* Available Trees Section */}
            <div className="trees-section">
                <h2>Árboles disponibles:</h2>
                <div className="sold-trees-container">
                    {availableTrees.length > 0 ? (
                        availableTrees.map((tree) => (
                            <div className="stat-card" key={tree.tree_id}>
                                <div className="tree-card">
                                    <div className="tree-image">
                                        <img
                                            src={getImageUrl(tree.photo_url)}
                                            alt={`Árbol #${tree.id}`}
                                            className="tree_image"
                                            onError={(e) => {
                                                e.target.src =
                                                    "http://127.0.0.1:8000/storage/trees/qU8FleGU1BTdWLMAZS3sgAH0e9scZf21TEh3zIg4.jpg";
                                            }}
                                        />
                                    </div>
                                    <div className="tree-info">
                                        <p>
                                            <strong>Altura:</strong>{" "}
                                            {tree.height} m
                                        </p>
                                        <p>
                                            <strong>Ubicación:</strong>{" "}
                                            {tree.location}
                                        </p>
                                        <p>
                                            <strong>Precio:</strong> $
                                            {tree.price}
                                        </p>
                                    </div>
                                </div>
                                <div className="button-cont">
                                    <button
                                        className="buy-btn"
                                        onClick={() =>
                                            handleBuyTree(tree.tree_id)
                                        }
                                    >
                                        Comprar
                                    </button>
                                </div>
                            </div>
                        ))
                    ) : (
                        <p>No hay árboles disponibles.</p>
                    )}
                </div>
            </div>

            {/* User's Trees Section */}
            <div className="container mx-auto px-4">
                <h1 className="text-2xl font-bold mb-4">Mis Árboles</h1>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {trees.map((tree) => (
                        <div
                            key={tree.id}
                            className="border rounded-lg p-4 shadow-md"
                        >
                            <div className="tree-image">
                                <img
                                    src={getImageUrl(tree.photo_url)}
                                    alt={`Árbol #${tree.id}`}
                                    className="tree_image"
                                    onError={(e) => {
                                        e.target.src =
                                            "http://127.0.0.1:8000/storage/trees/qU8FleGU1BTdWLMAZS3sgAH0e9scZf21TEh3zIg4.jpg";
                                    }}
                                />
                            </div>
                            <h2 className="text-xl font-semibold mb-2">
                                Árbol #{tree.id}
                            </h2>
                            <p>
                                <strong>Altura:</strong> {tree.height} m
                            </p>
                            <p>
                                <strong>Ubicación:</strong> {tree.location}
                            </p>
                            <p>
                                <strong>Precio:</strong> ${tree.price}
                            </p>
                        </div>
                    ))}
                </div>
                <a href="/logOut" className="back-button mt-4 inline-block">
                    ← Cerrar Sesión
                </a>
            </div>
        </div>
    );
};

export default FriendPanel;
