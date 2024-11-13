import { Routes, Route } from 'react-router-dom';
import { Navbar } from "../NavBar";
import { HomePage, Login, Register } from "../views";

export const AppRouter = () => {
  return (
    <Routes>
        <Route path = "/" element = {<Navbar />} >
        <Route index element={<HomePage />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        </Route>
    </Routes>
  );
};