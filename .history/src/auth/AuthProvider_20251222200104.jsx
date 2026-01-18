import React, { createContext, useEffect, useState } from "react";
import PropTypes from "prop-types";
import authService from "../services/authService";
import { getLocalToken, setLocalToken } from "../hooks/useLocalStorage";

const AuthContext = createContext();

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null); // {id, name, role, ...}
    const [token, setToken] = useState(() => getLocalToken());
    const [loading, setLoading] = useState(!!token);

    useEffect(() => {
        async function restore() {
            if (!token) {
                setLoading(false);
                return;
            }
            try {
                const data = await authService.me(token); // call backend /me
                setUser(data.user);
            } catch (err) {
                console.error("Failed to restore session", err);
                setToken(null);
                setUser(null);
            } finally {
                setLoading(false);
            }
        }
        restore();
    }, [token]);

    const login = async (credentials) => {
        const { token: newToken, user: u } = await authService.login(
            credentials
        );
        setToken(newToken);
        setLocalToken(newToken);
        setUser(u);
        return u;
    };

    const logout = async () => {
        await authService.logout(token);
        setToken(null);
        setUser(null);
        setLocalToken(null);
    };

    return (
        <AuthContext.Provider
            value={{ user, token, login, logout, loading, setUser }}
        >
            {children}
        </AuthContext.Provider>
    );
}

AuthProvider.propTypes = { children: PropTypes.node };
export default AuthContext;
