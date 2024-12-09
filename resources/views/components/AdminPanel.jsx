import React, { useState, useEffect } from "react";
import { Link } from 'react-router-dom';
export const AdminPanel = () => {
    const [soldCount, setSoldCount] = useState(0);
    const [availableCount, setAvailableCount] = useState(0);
    const [friendsCount, setFriendsCount] = useState(0);

    const [species, setSpecies] = useState([]);
    const [friends, setFriends] = useState([]);
    const [userName, setUserName] = useState("");

    const [expandedSpecies, setExpandedSpecies] = useState({});

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userName = localStorage.getItem("userName");
                setUserName(userName || "Usuario");
           
                const responseSold = await fetch("api/get-sold-trees");
                const dataS = await responseSold.json();
                setSoldCount(dataS.soldTrees);

                const responseAvailable = await fetch(
                    "api/get-available-trees"
                );
                const dataA = await responseAvailable.json();
                setAvailableCount(dataA.availableTrees);

                const responseFriends = await fetch("api/get-friends-count");
                const dataF = await responseFriends.json();
                setFriendsCount(dataF.friends);
            } catch (error) {
                console.error("Error al obtener datos:", error);
            }
        };

        fetch("/api/get-all-species")
            .then((response) => response.json())
            .then((data) => {
                setSpecies(data);
            })
            .catch((error) => console.error("Error fetching species:", error));

        // Fetch friends
        fetch("/api/get-friends")
            .then((response) => response.json())
            .then((data) => {
                setFriends(data);
            })
            .catch((error) => console.error("Error fetching friends:", error));

        fetchData();
    }, []);
    const handleViewSpecies = (speciesId) => {
        setExpandedSpecies((prev) => ({
            ...prev,
            [speciesId]: !prev[speciesId],
        }));
    };

    const handleAddTree = () => {
        window.location.href = "/addTree";
    };
    const handleAddUser = () => {
        window.location.href = "/addUser";
    };


    const handleAddSpecie = () => {
        window.location.href = "/addSpecie";
    };

    return (
        <div className="dashboard-container">
            <div className="dashboard-header">
                <h1>Bienvenido {userName}</h1>
            </div>
            <div className="dashboard-section">
                <div className="dashboard-header">
                    <h2>Estadisticas</h2>
                </div>
                </div>
            <div className="stats-container">
              <div className="stat-card">
                    <div className="stat-content">
                        <div className="stat-number">{soldCount}</div>
                        <div className="stat-title">Árboles Vendidos</div>
                    </div>
                </div>

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
                    <h2>Especies</h2>
                </div>

                <div className="species-container">
                    {species.map((specie) => (
                        <div className="species-card" key={specie.id}>
                            <div className="species-info">
                                <h3 className="species-name">
                                    {specie.commercial_name}
                                </h3>

                                {expandedSpecies[specie.id] && (
                                    <div className="species-scientific-info">
                                        <p className="species-description">
                                            <strong>Nombre Científico:</strong>{" "}
                                            {specie.scientific_name}
                                        </p>
                                    </div>
                                )}

                                <div className="species-actions">
                                    <button
                                        onClick={() =>
                                            handleViewSpecies(specie.id)
                                        }
                                        className="view-btn"
                                    >
                                        {expandedSpecies[specie.id]
                                            ? "Ocultar"
                                            : "Ver"}
                                    </button>
                                    <button className="edit-btn">
                                        <Link
                                            to={`/editSpecie/${specie.id}`}
                                            style={{
                                                textDecoration: "none",
                                                color: "inherit",
                                            }}
                                        >
                                            Editar
                                        </Link>
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
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
               
                <button onClick={handleAddUser} className="create-btn">
                    Crear Usuario
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

export default AdminPanel;
