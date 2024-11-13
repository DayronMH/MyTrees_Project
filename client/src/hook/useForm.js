import { useState } from "react";

export const useForm = (initialForm = {}) => {

    const [formState, setFormState] = useState(initialForm);

    const onInputChange = ({ target }) => {
        const { name, value } = target;
        setFormState({
            ...formState,     
            [name]: value    
        });
    }
    const onResetForm = () => {
        setFormState(initialForm);
    }

    // Retorna un objeto con todo lo necesario para manejar el formulario
    return {
        ...formState,        // Todos los valores del formulario
        formState,           // El estado completo del formulario
        onInputChange,       // Función para manejar cambios
        onResetForm         // Función para resetear el formulario
    }
    }