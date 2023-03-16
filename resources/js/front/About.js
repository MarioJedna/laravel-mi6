import React, { useContext } from "react";
import UserContext from "../context/UserContext";

const About = () => {
    const { user } = useContext(UserContext);

    if (!user) return null;

    const handleLogout = async () => {
        await logout();
        setUser(null);
    };

    return (
        <article>
            <h1>Hi, {user.name || "N/A"}</h1>

            <p>Your email address is {user.email}</p>

            <button type="button" onClick={() => handleLogout()}>
                Log out
            </button>
        </article>
    );
};

export default About;
