import React, { useState, useEffect } from "react";
import { Link } from 'react-router-dom';

export const OperatorPanel = () => {
    const [availableCount, setAvailableCount] = useState(0);
    const [friendsCount, setFriendsCount] = useState(0);
    const [soldTrees, setSoldTrees] = useState([]); // Nuevo estado para los árboles vendidos
    const [userName, setUserName] = useState("");

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userName = localStorage.getItem("userName");
                setUserName(userName || "Usuario");
                
                const responseAvailable = await fetch("api/get-available-trees");
                const dataA = await responseAvailable.json();
                setAvailableCount(dataA.availableTrees);

                const responseFriends = await fetch("api/get-friends-count");
                const dataF = await responseFriends.json();
                setFriendsCount(dataF.friends);
            } catch (error) {
                console.error("Error al obtener datos:", error);
            }
        };

        // Fetch para los árboles vendidos
        fetch("/api/get-sold-trees-with-user")
            .then((response) => response.json())
            .then((data) => {
                setSoldTrees(data); // Establecer los árboles vendidos
            })
            .catch((error) => console.error("Error fetching sold trees:", error));

        fetchData();
    }, []);

    const handleAddTree = () => {
        window.location.href = "/addTree";
    };

    const handleAddSpecie = () => {
        window.location.href = "/addSpecie";
    };

    return (
        <div className="dashboard-container">
            <div className="dashboard-header">
                <h1>Bienvenido {userName}</h1>
            </div>
            <h2>Estadísticas</h2>
            <div className="stats-container">
                {/* Árboles Disponibles */}
                <div className="stat-card">
                    <div className="stat-content">
                        <div className="stat-number">{availableCount}</div>
                        <div className="stat-title">Árboles Disponibles</div>
                    </div>
                </div>

                <div className="stat-card">
                    <div className="stat-content">
                        <div className="stat-number">{friendsCount}</div>
                        <div className="stat-title">Amigos Registrados</div>
                    </div>
                </div>
            </div>

            <div className="dashboard-section">
                <div className="dashboard-header">
                    <h2>Árboles Vendidos:</h2>
                </div>

                <div className="sold-trees-container">
                    {soldTrees.map((tree) => (
                        <div className="stat-card" key={tree.tree_id}>
                            <div className="stat-content">
                                <div className="stat-number">{tree.commercial_name}</div>
                                <div className="stat-title">Árbol Vendido</div>
                                <div className="stat-owner">
                                    <strong>Dueño:</strong> {tree.owner_name}
                                </div>
                            </div>

                            {/* Botones para Actualizar y Ver historial */}
                            <div className="button-container">
                                <button className="update-btn">
                                    <Link
                                        to={`/updateTrees/${tree.tree_id}`}
                                        style={{
                                            textDecoration: "none",
                                            color: "inherit",
                                        }}
                                    >
                                        Actualizar
                                    </Link>
                                </button>

                                <button className="log-btn">
                                    <Link
                                        to={`/treeLog/${tree.tree_id}`}
                                        style={{
                                            textDecoration: "none",
                                            color: "inherit",
                                        }}
                                    >
                                        Ver historial del árbol
                                    </Link>
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="dashboard-footer">
                <a href="/" className="back-button">
                    ← Cerrar Sesión
                </a>
            </div>
        </div>
    );
};

export default OperatorPanel;
