import React, { createContext, useEffect, useState } from 'react';
import PropTypes from 'prop-types';

// Simple AuthContext (localStorage-based)
const STORAGE_KEY = 'ufo_auth';
const AuthContext = createContext();

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  });

  const [loading, setLoading] = useState(false);

  useEffect(() => {
    // no-op for now, kept for parity
  }, []);

  // Simple login: store minimal user object in localStorage
  const login = async ({ email, role, name }) => {
    const u = { email: email || null, role: role || null, name: name || null };
    setUser(u);
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(u));
    } catch (e) {
      // ignore storage errors
    }
    return u;
  };

  const logout = async () => {
    setUser(null);
    try {
      localStorage.removeItem(STORAGE_KEY);
    } catch (e) {}
  };

  const isAuthenticated = !!user;

  return (
    <AuthContext.Provider
      value={{ user, isAuthenticated, login, logout, loading, setUser }}
    >
      {children}
    </AuthContext.Provider>
  );
}

AuthProvider.propTypes = { children: PropTypes.node };
export default AuthContext;
