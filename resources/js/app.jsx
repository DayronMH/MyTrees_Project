import React from "react";
import {
    BrowserRouter as Router,
    Routes,
    Route,
    useLocation,
    Navigate,
} from "react-router-dom";
import { createRoot } from "react-dom/client";
import { Navbar } from "../views/NavBar";
import {
    HomePage,
    Login,
    Register,
    AdminPanel,
    FriendPanel,
    EditSpecie,
    AddSpecie,
    AddTree,
    FriendTrees,
    EditTree,
    UpdateTrees,
    OperatorPanel,
    AddUser
} from "../views/components";

const ProtectedRoute = ({ children, allowedRoles = [] }) => {
    const isAuthenticated = () => {
        const token = localStorage.getItem("authToken");
        return token !== null;
    };

    const hasRequiredRole = () => {
        if (allowedRoles.length === 0) return true; 
        const userRole = localStorage.getItem("userRole");
        return allowedRoles.includes(userRole); // Verifica el rol.
    };
    if (!isAuthenticated()) {
        return <Navigate to="/login" replace />;
    }
    if (!hasRequiredRole()) {
        return <Navigate to="/" replace />;
    }
    return children;
};
export const App = () => {
    const location = useLocation();

    const navbarRoutes = ["/", "/adminPanel", "/friendPanel", "/operatorPanel"];

    return (
        <>
            {navbarRoutes.includes(location.pathname) && <Navbar />}

            <Routes>
                <Route path="/" element={<HomePage />} />
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route
                    path="/updateTrees/:id"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <UpdateTrees/>
                        </ProtectedRoute>
                    }
                />
                 <Route
                    path="/friendTrees"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <FriendTrees/>
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/editTree/:id"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <EditTree/>
                        </ProtectedRoute>
                    }
                />
                 <Route
                    path="/editSpecie/:id"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <EditSpecie/>
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/addSpecie"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <AddSpecie />
                        </ProtectedRoute>
                    }
                />
                 <Route
                    path="/addUser"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <AddUser />
                        </ProtectedRoute>
                    }
                />
                 <Route
                    path="/addTree"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <AddTree />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/adminPanel"
                    element={
                        <ProtectedRoute allowedRoles={["admin"]}>
                            <AdminPanel />
                        </ProtectedRoute>
                    }
                />


                <Route
                    path="/friendPanel"
                    element={
                        <ProtectedRoute allowedRoles={["friend"]}>
                            <FriendPanel />
                        </ProtectedRoute>
                    }
                />
                 <Route
                    path="/operatorPanel"
                    element={
                        <ProtectedRoute allowedRoles={["operator"]}>
                            <OperatorPanel />
                        </ProtectedRoute>
                    }
                />
                
                
            </Routes>
        </>
    );
};

const container = document.getElementById("app");
const root = createRoot(container);
root.render(
    <Router>
        <App />
    </Router>
);

export default App;
