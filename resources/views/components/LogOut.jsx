import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

export const LogOut = () => {
    const navigate = useNavigate();

    useEffect(() => {
        localStorage.removeItem("userName");
        localStorage.removeItem("userId");
        localStorage.removeItem("authToken");

        navigate("/");
    }, [navigate]);

    return null; 
};

export default LogOut;
