import React, { useState, useEffect } from 'react';

export const FriendPanel = () => {
  const [userName, setUserName] = useState('');
  const [availableTrees, setAvailableTrees] = useState([]);
  const [userTrees, setUserTrees] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const storedUserName = localStorage.getItem('userName');
        setUserName(storedUserName || 'Invitado');

        const availableResponse = await fetch('/api/all-available-trees');
        const availableData = await availableResponse.json();
        setAvailableTrees(availableData);

        const userId = localStorage.getItem('userId');
        if (userId) {
          const userTreesResponse = await fetch(`/api/get-trees-by-owner/${userId}`);
          const userTreesData = await userTreesResponse.json();
          setUserTrees(userTreesData);
        }

        setLoading(false);
      } catch (error) {
        console.error('Error fetching data:', error);
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return <div>Cargando...</div>;
  }

  return (
    <div className="dashboard-container">
      <div className="dashboard-header">
        <h1>Bienvenido {userName}</h1>
      </div>

      <div className="trees-section">
        <h2>Árboles disponibles:</h2>
        <ul>
          {availableTrees.length > 0 ? (
            availableTrees.map((tree) => (
              <li key={tree.id}>
                {tree.name} - {tree.species} ({tree.available ? 'Disponible' : 'No disponible'})
              </li>
            ))
          ) : (
            <p>No hay árboles disponibles.</p>
          )}
        </ul>
      </div>

      <div className="user-trees-section">
        <h2>Tus árboles:</h2>
        <ul>
          {userTrees.length > 0 ? (
            userTrees.map((tree) => (
              <li key={tree.id}>
                {tree.name} - {tree.species} ({tree.available ? 'Disponible' : 'No disponible'})
              </li>
            ))
          ) : (
            <p>No tienes árboles registrados.</p>
          )}
        </ul>
      </div>
    </div>
  );
};

export default FriendPanel;
