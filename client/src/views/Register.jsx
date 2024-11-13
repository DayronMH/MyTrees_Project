import React from "react";
import { useForm } from "../hook/useForm";
import { useNavigate } from "react-router-dom";

export const Register = () => {
  const { name, email, password, phone, address, country } = useForm({
    name: "",
    email: "",
    password: "",
    phone: "",
    address: "",
    country: "",
  });
  const onRegister  = ()=>{

  }

  return (
    <div className="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
      <form onSubmit= {onRegister}>
        <h1>Registro</h1>
        <div className="input-group">
          <input
            type="name"
            name="name"
            id="name"
            value={name}
            onChange={onInputChange}
            autoComplete="off"
          />
          <label htmlFor="name">name:</label>
          <input
            type="email"
            name="email"
            id="email"
            value={email}
            onChange={onInputChange}
            autoComplete="off"
          />
          <label htmlFor="email">email:</label>
          <input
            type="password"
            name="password"
            id="password"
            value={password}
            onChange={onInputChange}
            autoComplete="off"
          />
          <label htmlFor="password">password:</label>
          <input
            type="name"
            name="name"
            id="name"
            value={name}
            onChange={onInputChange}
            autoComplete="off"
          />
          <label htmlFor="name">name:</label>
        </div>
      </form>
    </div>
  );
};

export default Register;
