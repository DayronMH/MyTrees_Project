import React, { useState, useEffect } from "react";
import { Link } from 'react-router-dom';

export const OperatorPanel = () => {
    const [availableCount, setAvailableCount] = useState(0);
    const [friendsCount, setFriendsCount] = useState(0);
    const [friends, setFriends] = useState([]);
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

        fetch("/api/get-friends")
            .then((response) => response.json())
            .then((data) => {
                setFriends(data);
            })
            .catch((error) => console.error("Error fetching friends:", error));

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
                    <h2>Amigos Registrados:</h2>
                </div>

                <div className="friends-container">
                    {friends.map((friend) => (
                        <a
                            href={`/friendTrees?friend_id=${friend.id}`}
                            className="friend-link"
                            key={friend.id}
                        >
                            <h3 className="friend-name">{friend.name}</h3>
                        </a>
                    ))}
                </div>
            </div>

            <div className="button-container">
                <button onClick={handleAddTree} className="create-btn">
                    Crear Árbol
                </button>

                <button onClick={handleAddSpecie} className="create-btn">
                    Crear Especie
                </button>
            </div>

            <div className="dashboard-footer">
                <a href="login.php" className="back-button">
                    ← Cerrar Sesión
                </a>
            </div>
        </div>
    );
};

export default OperatorPanel;
